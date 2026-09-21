<?php

namespace Tests\Feature\Admin;

use App\Models\Appointment;
use App\Models\Specialist;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecialistManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    /**
     * The list shows specialists and their visibility.
     */
    public function test_specialists_are_listed(): void
    {
        $this->specialist(['name' => 'Dr Sadasivan']);
        $this->specialist(['name' => 'Dr Hidden', 'is_active' => false]);

        $this->get(route('admin.specialists.index'))
            ->assertOk()
            ->assertSee('Dr Sadasivan')
            ->assertSee('Dr Hidden')
            ->assertSee('Hidden');
    }

    /**
     * Specialists can be searched by name and role.
     */
    public function test_specialists_can_be_searched(): void
    {
        $this->specialist(['name' => 'Dr Sadasivan', 'role' => 'Consultant Psychiatrist']);
        $this->specialist(['name' => 'Dr Titus', 'role' => 'Nephrologist']);

        $this->get(route('admin.specialists.index', ['q' => 'Nephrologist']))
            ->assertOk()
            ->assertSee('Dr Titus')
            ->assertDontSee('Dr Sadasivan');
    }

    /**
     * The create screen renders the form.
     */
    public function test_the_create_screen_renders(): void
    {
        $this->get(route('admin.specialists.create'))
            ->assertOk()
            ->assertSee('Add specialist');
    }

    /**
     * The edit screen renders with the existing values.
     */
    public function test_the_edit_screen_renders(): void
    {
        $specialist = $this->specialist([
            'name' => 'Dr Thomas Titus',
            'special_interests' => ['Chronic Kidney Disease'],
        ]);

        $this->get(route('admin.specialists.edit', $specialist))
            ->assertOk()
            ->assertSee('Dr Thomas Titus')
            ->assertSee('Chronic Kidney Disease');
    }

    /**
     * A specialist can be created with focus areas from a textarea.
     */
    public function test_a_specialist_can_be_created(): void
    {
        $specialty = Specialty::create([
            'name' => 'Neurology',
            'short_description' => 'Brain health.',
            'icon' => 'brain',
        ]);

        $this->post(route('admin.specialists.store'), [
            'name' => 'Dr Sarah Tanusha Thomas',
            'qualifications' => 'MBBS, MMed (Clin Epi), FRACP',
            'role' => 'Neurologist – Stroke & Cognitive Neurology',
            'specialty_id' => $specialty->id,
            'image' => 'https://example.com/photo.jpg',
            'bio' => 'Consultant Neurologist.',
            'consulting_days' => 'Mondays, Wednesdays',
            'special_interests' => "Stroke Prevention & Post-Stroke Care\n\n  Cognitive Assessment  \n",
            'is_active' => '1',
        ])->assertRedirect(route('admin.specialists.index'));

        $specialist = Specialist::firstOrFail();

        $this->assertSame('Dr Sarah Tanusha Thomas', $specialist->name);
        $this->assertSame('dr-sarah-tanusha-thomas', $specialist->slug);
        $this->assertSame($specialty->id, $specialist->specialty_id);
        $this->assertSame(
            ['Stroke Prevention & Post-Stroke Care', 'Cognitive Assessment'],
            $specialist->special_interests,
        );
        $this->assertTrue($specialist->is_active);
        $this->assertSame(1, $specialist->sort_order);
    }

    /**
     * A specialist saved without the visibility checkbox is hidden.
     */
    public function test_a_specialist_defaults_to_hidden_when_the_checkbox_is_absent(): void
    {
        $this->post(route('admin.specialists.store'), [
            'name' => 'Dr Draft Profile',
            'qualifications' => 'MBBS',
            'role' => 'Consultant',
        ])->assertRedirect();

        $this->assertFalse(Specialist::firstOrFail()->is_active);
    }

    /**
     * New specialists are appended to the end of the order.
     */
    public function test_new_specialists_are_appended_to_the_display_order(): void
    {
        $this->specialist(['name' => 'First', 'sort_order' => 4]);

        $this->post(route('admin.specialists.store'), [
            'name' => 'Second',
            'qualifications' => 'MBBS',
            'role' => 'Consultant',
        ]);

        $this->assertSame(5, Specialist::where('name', 'Second')->firstOrFail()->sort_order);
    }

    /**
     * Required fields are validated.
     */
    public function test_required_fields_are_validated(): void
    {
        $this->from(route('admin.specialists.create'))
            ->post(route('admin.specialists.store'), ['name' => ''])
            ->assertSessionHasErrors(['name', 'qualifications', 'role']);

        $this->assertDatabaseCount('specialists', 0);
    }

    /**
     * A headshot may be a hosted URL or a file shipped under public/.
     */
    public function test_the_headshot_accepts_a_url_or_a_local_path(): void
    {
        $this->post(route('admin.specialists.store'), [
            'name' => 'Dr Local Photo',
            'qualifications' => 'MBBS',
            'role' => 'Consultant',
            'image' => '/images/specialists/dr-local.png',
        ])->assertSessionHasNoErrors();

        $this->post(route('admin.specialists.store'), [
            'name' => 'Dr Remote Photo',
            'qualifications' => 'MBBS',
            'role' => 'Consultant',
            'image' => 'https://example.com/photo.jpg',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseCount('specialists', 2);
    }

    /**
     * A relative path without a leading slash is rejected.
     */
    public function test_the_headshot_must_be_a_url_or_a_root_relative_path(): void
    {
        $this->from(route('admin.specialists.create'))
            ->post(route('admin.specialists.store'), [
                'name' => 'Dr Test',
                'qualifications' => 'MBBS',
                'role' => 'Consultant',
                'image' => 'images/specialists/dr-test.png',
            ])
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('specialists', 0);
    }

    /**
     * A specialist can be updated.
     */
    public function test_a_specialist_can_be_updated(): void
    {
        $specialist = $this->specialist(['name' => 'Dr Old Name', 'sort_order' => 3]);

        $this->put(route('admin.specialists.update', $specialist), [
            'name' => 'Dr New Name',
            'qualifications' => 'MBBS, FRACP',
            'role' => 'Senior Consultant',
            'special_interests' => 'Memory Disorders',
            'sort_order' => 3,
            'is_active' => '1',
        ])->assertRedirect(route('admin.specialists.index'));

        $specialist->refresh();

        $this->assertSame('Dr New Name', $specialist->name);
        $this->assertSame('Senior Consultant', $specialist->role);
        $this->assertSame(['Memory Disorders'], $specialist->special_interests);
        $this->assertTrue($specialist->is_active);
    }

    /**
     * Removing a specialist deletes the profile.
     */
    public function test_a_specialist_can_be_removed(): void
    {
        $specialist = $this->specialist();

        $this->delete(route('admin.specialists.destroy', $specialist))
            ->assertRedirect(route('admin.specialists.index'));

        $this->assertDatabaseCount('specialists', 0);
    }

    /**
     * Slugs stay unique even when names collide.
     */
    public function test_slugs_are_made_unique(): void
    {
        $this->specialist(['name' => 'Dr Same Name']);
        $this->specialist(['name' => 'Dr Same Name']);

        $slugs = Specialist::pluck('slug')->all();

        $this->assertSame(['dr-same-name', 'dr-same-name-2'], $slugs);
    }

    /**
     * Several specialists can be hidden at once.
     */
    public function test_specialists_can_be_bulk_hidden(): void
    {
        $first = $this->specialist(['name' => 'Dr First']);
        $second = $this->specialist(['name' => 'Dr Second']);
        $untouched = $this->specialist(['name' => 'Dr Untouched']);

        $this->post(route('admin.specialists.bulk'), [
            'ids' => [$first->id, $second->id],
            'action' => 'deactivate',
        ])->assertRedirect();

        $this->assertFalse($first->refresh()->is_active);
        $this->assertFalse($second->refresh()->is_active);
        $this->assertTrue($untouched->refresh()->is_active);
        $this->assertSame('2 specialists hidden from the website.', session('success'));
    }

    /**
     * Several specialists can be published at once.
     */
    public function test_specialists_can_be_bulk_shown(): void
    {
        $specialist = $this->specialist(['is_active' => false]);

        $this->post(route('admin.specialists.bulk'), [
            'ids' => [$specialist->id],
            'action' => 'activate',
        ])->assertRedirect();

        $this->assertTrue($specialist->refresh()->is_active);
        $this->assertSame('1 specialist shown on the website.', session('success'));
    }

    /**
     * Bulk removal keeps the specialists' appointment requests.
     */
    public function test_specialists_can_be_bulk_removed_keeping_requests(): void
    {
        $first = $this->specialist(['name' => 'Dr First']);
        $second = $this->specialist(['name' => 'Dr Second']);

        Appointment::create([
            'full_name' => 'Test Patient',
            'phone' => '0400 000 000',
            'specialist_id' => $first->id,
            'specialty' => 'Neurology',
            'referral_status' => 'yes',
        ]);

        $this->post(route('admin.specialists.bulk'), [
            'ids' => [$first->id, $second->id],
            'action' => 'delete',
        ])->assertRedirect();

        $this->assertDatabaseCount('specialists', 0);
        $this->assertDatabaseCount('appointments', 1);
        $this->assertNull(Appointment::firstOrFail()->specialist_id);
    }

    /**
     * Bulk actions are rejected when nothing is selected.
     */
    public function test_bulk_actions_require_a_selection(): void
    {
        $this->specialist();

        $this->from(route('admin.specialists.index'))
            ->post(route('admin.specialists.bulk'), ['action' => 'delete'])
            ->assertSessionHasErrors('ids');

        $this->assertDatabaseCount('specialists', 1);
    }

    /**
     * Profile page sections are saved from the repeatable editor.
     */
    public function test_profile_sections_are_saved_from_the_form(): void
    {
        $this->post(route('admin.specialists.store'), [
            'name' => 'Dr Sadasivan',
            'qualifications' => 'MBBS, MRCPsych (UK), FRANZCP',
            'role' => 'Consultant Psychiatrist',
            'is_active' => '1',
            'profile_sections' => [
                [
                    'heading' => 'Training and Experience',
                    'type' => 'list',
                    'items' => "Trained in Mersey Deanery (UK)\nFellow of the RANZCP",
                ],
                [
                    'heading' => 'Approach to Care',
                    'type' => 'paragraphs',
                    'items' => 'She follows a biopsychosocial model.',
                ],
            ],
        ])->assertRedirect(route('admin.specialists.index'));

        $sections = Specialist::firstOrFail()->profile_sections;

        $this->assertCount(2, $sections);
        $this->assertSame('Training and Experience', $sections[0]['heading']);
        $this->assertSame('list', $sections[0]['type']);
        $this->assertSame(
            ['Trained in Mersey Deanery (UK)', 'Fellow of the RANZCP'],
            $sections[0]['items'],
        );
        $this->assertSame('paragraphs', $sections[1]['type']);
    }

    /**
     * Paragraph blocks are separated by a blank line, so a wrapped paragraph
     * inside the textarea stays a single paragraph.
     */
    public function test_paragraph_sections_split_on_blank_lines(): void
    {
        $this->post(route('admin.specialists.store'), [
            'name' => 'Dr Titus',
            'qualifications' => 'MBBS, MD',
            'role' => 'Nephrologist',
            'profile_sections' => [
                [
                    'heading' => 'Training and Career',
                    'type' => 'paragraphs',
                    'items' => "He trained in India and the UK,\nand completed a doctorate at Oxford.\n\nHe sees patients aged over 18.",
                ],
            ],
        ]);

        $this->assertSame(
            ['He trained in India and the UK, and completed a doctorate at Oxford.', 'He sees patients aged over 18.'],
            Specialist::firstOrFail()->profile_sections[0]['items'],
        );
    }

    /**
     * Empty sections are dropped rather than rendering a stray heading.
     */
    public function test_empty_profile_sections_are_discarded(): void
    {
        $this->post(route('admin.specialists.store'), [
            'name' => 'Dr Draft Profile',
            'qualifications' => 'MBBS',
            'role' => 'Consultant',
            'profile_sections' => [
                ['heading' => 'Empty Section', 'type' => 'list', 'items' => "   \n  "],
                ['heading' => 'Kept', 'type' => 'list', 'items' => 'A real bullet'],
            ],
        ]);

        $sections = Specialist::firstOrFail()->profile_sections;

        $this->assertCount(1, $sections);
        $this->assertSame('Kept', $sections[0]['heading']);
    }

    /**
     * A section saved without a heading is still preserved, since it renders
     * as a continuation of the content above it.
     */
    public function test_profile_sections_may_omit_a_heading(): void
    {
        $this->post(route('admin.specialists.store'), [
            'name' => 'Dr Headingless',
            'qualifications' => 'MBBS',
            'role' => 'Consultant',
            'profile_sections' => [
                ['heading' => '', 'type' => 'list', 'items' => 'A bullet with no heading'],
            ],
        ]);

        $this->assertSame('', Specialist::firstOrFail()->profile_sections[0]['heading']);
    }

    /**
     * The edit screen rehydrates the saved sections back into the editor.
     */
    public function test_the_edit_screen_shows_existing_profile_sections(): void
    {
        $specialist = $this->specialist([
            'name' => 'Dr Thomas Titus',
            'profile_sections' => [
                [
                    'heading' => 'Affiliations',
                    'type' => 'list',
                    'items' => ['Royal Australasian College of Physicians', 'International Society of Nephrology'],
                ],
            ],
        ]);

        $this->get(route('admin.specialists.edit', $specialist))
            ->assertOk()
            ->assertSee('Affiliations')
            ->assertSee('Royal Australasian College of Physicians')
            ->assertSee('International Society of Nephrology');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function specialist(array $attributes = []): Specialist
    {
        return Specialist::create([
            'name' => 'Dr Test Subject',
            'qualifications' => 'MBBS, FRACP',
            'role' => 'Consultant',
            ...$attributes,
        ]);
    }
}
