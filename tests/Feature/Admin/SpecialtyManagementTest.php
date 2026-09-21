<?php

namespace Tests\Feature\Admin;

use App\Models\Specialist;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecialtyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    /**
     * The list shows specialties with their specialist count.
     */
    public function test_specialties_are_listed_with_specialist_counts(): void
    {
        $specialty = $this->specialty(['name' => 'Nephrology']);

        Specialist::create([
            'name' => 'Dr Thomas Titus',
            'qualifications' => 'MBBS, FRACP',
            'role' => 'Nephrologist',
            'specialty_id' => $specialty->id,
        ]);

        $this->get(route('admin.specialties.index'))
            ->assertOk()
            ->assertSee('Nephrology')
            ->assertSee('1 specialist');
    }

    /**
     * The edit screen renders with the existing values.
     */
    public function test_the_edit_screen_renders(): void
    {
        $specialty = $this->specialty([
            'name' => 'Nephrology',
            'conditions' => ['Chronic Kidney Disease'],
        ]);

        $this->get(route('admin.specialties.edit', $specialty))
            ->assertOk()
            ->assertSee('Nephrology')
            ->assertSee('Chronic Kidney Disease');
    }

    /**
     * A specialty can be created with conditions and services from textareas.
     */
    public function test_a_specialty_can_be_created(): void
    {
        $this->post(route('admin.specialties.store'), [
            'name' => 'Neurology',
            'icon' => 'brain',
            'short_description' => 'Stroke, cognitive neurology and brain health.',
            'full_description' => 'Comprehensive neurological evaluation.',
            'conditions' => "Stroke & TIA\nCognitive Impairment & Dementia\n\n",
            'diagnostic_services' => "Cognitive Screening\nNeurovascular Workup\n",
            'is_active' => '1',
        ])->assertRedirect(route('admin.specialties.index'));

        $specialty = Specialty::firstOrFail();

        $this->assertSame('neurology', $specialty->slug);
        $this->assertSame('brain', $specialty->icon);
        $this->assertSame(['Stroke & TIA', 'Cognitive Impairment & Dementia'], $specialty->conditions);
        $this->assertSame(['Cognitive Screening', 'Neurovascular Workup'], $specialty->diagnostic_services);
        $this->assertTrue($specialty->is_active);
        $this->assertSame(1, $specialty->sort_order);
    }

    /**
     * The icon must be one of the supported line-art options.
     */
    public function test_the_icon_must_be_supported(): void
    {
        $this->from(route('admin.specialties.create'))
            ->post(route('admin.specialties.store'), [
                'name' => 'Neurology',
                'icon' => 'unicorn',
                'short_description' => 'Brain health.',
            ])
            ->assertSessionHasErrors('icon');

        $this->assertDatabaseCount('specialties', 0);
    }

    /**
     * Required fields are validated.
     */
    public function test_required_fields_are_validated(): void
    {
        $this->from(route('admin.specialties.create'))
            ->post(route('admin.specialties.store'), [])
            ->assertSessionHasErrors(['name', 'icon', 'short_description']);
    }

    /**
     * A specialty can be updated.
     */
    public function test_a_specialty_can_be_updated(): void
    {
        $specialty = $this->specialty(['name' => 'Old Department', 'sort_order' => 2]);

        $this->put(route('admin.specialties.update', $specialty), [
            'name' => 'New Department',
            'icon' => 'nephrology',
            'short_description' => 'Updated summary.',
            'conditions' => 'Condition One',
            'diagnostic_services' => 'Service One',
            'sort_order' => 2,
            'is_active' => '1',
        ])->assertRedirect(route('admin.specialties.index'));

        $specialty->refresh();

        $this->assertSame('New Department', $specialty->name);
        $this->assertSame('nephrology', $specialty->icon);
        $this->assertSame(['Condition One'], $specialty->conditions);
        $this->assertSame(['Service One'], $specialty->diagnostic_services);
    }

    /**
     * Removing a specialty unassigns its specialists without deleting them.
     */
    public function test_removing_a_specialty_keeps_its_specialists(): void
    {
        $specialty = $this->specialty();

        $specialist = Specialist::create([
            'name' => 'Dr Thomas Titus',
            'qualifications' => 'MBBS, FRACP',
            'role' => 'Nephrologist',
            'specialty_id' => $specialty->id,
        ]);

        $this->delete(route('admin.specialties.destroy', $specialty))
            ->assertRedirect(route('admin.specialties.index'));

        $this->assertDatabaseCount('specialties', 0);
        $this->assertDatabaseCount('specialists', 1);
        $this->assertNull($specialist->refresh()->specialty_id);
    }

    /**
     * Several specialties can be hidden at once.
     */
    public function test_specialties_can_be_bulk_hidden(): void
    {
        $first = $this->specialty(['name' => 'First Department']);
        $second = $this->specialty(['name' => 'Second Department']);
        $untouched = $this->specialty(['name' => 'Untouched Department']);

        $this->post(route('admin.specialties.bulk'), [
            'ids' => [$first->id, $second->id],
            'action' => 'deactivate',
        ])->assertRedirect();

        $this->assertFalse($first->refresh()->is_active);
        $this->assertFalse($second->refresh()->is_active);
        $this->assertTrue($untouched->refresh()->is_active);
    }

    /**
     * Several specialties can be published at once.
     */
    public function test_specialties_can_be_bulk_shown(): void
    {
        $specialty = $this->specialty(['is_active' => false]);

        $this->post(route('admin.specialties.bulk'), [
            'ids' => [$specialty->id],
            'action' => 'activate',
        ])->assertRedirect();

        $this->assertTrue($specialty->refresh()->is_active);
        $this->assertSame('1 specialty shown on the website.', session('success'));
    }

    /**
     * Bulk removal keeps the specialists assigned to those departments.
     */
    public function test_specialties_can_be_bulk_removed_keeping_specialists(): void
    {
        $specialty = $this->specialty();

        $specialist = Specialist::create([
            'name' => 'Dr Thomas Titus',
            'qualifications' => 'MBBS, FRACP',
            'role' => 'Nephrologist',
            'specialty_id' => $specialty->id,
        ]);

        $this->post(route('admin.specialties.bulk'), [
            'ids' => [$specialty->id],
            'action' => 'delete',
        ])->assertRedirect();

        $this->assertDatabaseCount('specialties', 0);
        $this->assertDatabaseCount('specialists', 1);
        $this->assertNull($specialist->refresh()->specialty_id);
    }

    /**
     * Bulk actions are rejected when nothing is selected.
     */
    public function test_bulk_actions_require_a_selection(): void
    {
        $this->specialty();

        $this->from(route('admin.specialties.index'))
            ->post(route('admin.specialties.bulk'), ['action' => 'delete'])
            ->assertSessionHasErrors('ids');

        $this->assertDatabaseCount('specialties', 1);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function specialty(array $attributes = []): Specialty
    {
        return Specialty::create([
            'name' => 'Neurology',
            'short_description' => 'Brain and nervous system care.',
            'icon' => 'brain',
            ...$attributes,
        ]);
    }
}
