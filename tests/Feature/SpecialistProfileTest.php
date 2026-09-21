<?php

namespace Tests\Feature;

use App\Models\Specialist;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecialistProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Each card on the homepage links to that specialist's own page.
     */
    public function test_homepage_cards_link_to_the_profile_pages(): void
    {
        $sadasivan = $this->specialist(['name' => 'Dr Sadasivan']);
        $thomas = $this->specialist(['name' => 'Dr Sarah Tanusha Thomas']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee($sadasivan->profileUrl(), false);
        $response->assertSee($thomas->profileUrl(), false);

        // Profiles are real URLs, so the homepage no longer needs the modal.
        $response->assertDontSee('Close profile');
    }

    /**
     * The page shows the doctor's identity and biography.
     */
    public function test_profile_page_renders_the_doctor_identity(): void
    {
        $specialty = $this->specialty(['name' => 'Neurology']);
        $specialist = $this->specialist([
            'name' => 'Dr Sarah Tanusha Thomas',
            'qualifications' => 'MBBS, MMed (Clin Epi), FRACP (Neurology)',
            'role' => 'Neurologist – Stroke & Cognitive Neurology',
            'specialty_id' => $specialty->id,
            'image' => '/images/specialists/dr-sarah-tanusha-thomas.jpeg',
            'bio' => 'Dr Sarah Thomas is a neurologist with subspecialty expertise in stroke and cognitive neurology.',
            'consulting_days' => 'Mondays, Wednesdays, Fridays',
            'special_interests' => ['Stroke care', 'Cognitive neurology and memory disorders'],
        ]);

        $response = $this->get($specialist->profileUrl());

        $response->assertOk();
        $response->assertSee('Dr Sarah Tanusha Thomas');
        $response->assertSee('MBBS, MMed (Clin Epi), FRACP (Neurology)');
        $response->assertSee('Neurologist – Stroke &amp; Cognitive Neurology', false);
        $response->assertSee('subspecialty expertise in stroke and cognitive neurology', false);
        $response->assertSee('Consulting Mondays, Wednesdays, Fridays');
        $response->assertSee('/images/specialists/dr-sarah-tanusha-thomas.jpeg', false);

        $response->assertSee('Special Interests');
        $response->assertSee('Stroke care');
        $response->assertSee('Cognitive neurology and memory disorders');
    }

    /**
     * Profile sections render under their headings, in the stored order.
     */
    public function test_profile_sections_render_in_order(): void
    {
        $specialist = $this->specialist([
            'name' => 'Dr Sadasivan',
            'profile_sections' => [
                [
                    'heading' => 'Training and Experience',
                    'type' => 'list',
                    'items' => ['Trained in Mersey Deanery (UK)', 'Fellow of the RANZCP'],
                ],
                [
                    'heading' => 'Approach to Care',
                    'type' => 'paragraphs',
                    'items' => ['She follows a biopsychosocial model.', 'She works closely with GPs.'],
                ],
            ],
        ]);

        $response = $this->get($specialist->profileUrl());

        $response->assertOk();
        $response->assertSee('Training and Experience');
        $response->assertSee('Trained in Mersey Deanery (UK)');
        $response->assertSee('Fellow of the RANZCP');
        $response->assertSee('Approach to Care');
        $response->assertSee('She follows a biopsychosocial model.');
        $response->assertSee('She works closely with GPs.');

        $this->assertRendersInOrder($response->getContent(), 'Training and Experience', 'Approach to Care');
    }

    /**
     * A page with no extra sections still renders the biography.
     */
    public function test_profile_page_works_without_extra_sections(): void
    {
        $specialist = $this->specialist([
            'name' => 'Dr Harish Venugopal',
            'bio' => 'Dr Harish Venugopal is a Consultant Endocrinologist.',
            'profile_sections' => null,
        ]);

        $this->get($specialist->profileUrl())
            ->assertOk()
            ->assertSee('Dr Harish Venugopal')
            ->assertSee('Dr Harish Venugopal is a Consultant Endocrinologist.');
    }

    /**
     * Hidden specialists are not reachable, even by direct URL.
     */
    public function test_hidden_specialists_are_not_reachable(): void
    {
        $specialist = $this->specialist([
            'name' => 'Dr Hidden',
            'is_active' => false,
        ]);

        $this->get('/specialists/'.$specialist->slug)->assertNotFound();
    }

    /**
     * Unknown slugs 404 rather than erroring.
     */
    public function test_unknown_slug_returns_not_found(): void
    {
        $this->get('/specialists/dr-nobody')->assertNotFound();
    }

    /**
     * Other doctors in the same department are offered at the foot of the page.
     */
    public function test_colleagues_in_the_same_department_are_listed(): void
    {
        $neurology = $this->specialty(['name' => 'Neurology']);
        $psychiatry = $this->specialty(['name' => 'Psychiatry']);

        $subject = $this->specialist(['name' => 'Dr Thomas', 'specialty_id' => $neurology->id]);
        $colleague = $this->specialist(['name' => 'Dr Colleague', 'specialty_id' => $neurology->id]);
        $unrelated = $this->specialist(['name' => 'Dr Psychiatrist', 'specialty_id' => $psychiatry->id]);
        $hidden = $this->specialist([
            'name' => 'Dr Hidden',
            'specialty_id' => $neurology->id,
            'is_active' => false,
        ]);

        $response = $this->get($subject->profileUrl());

        $response->assertOk();
        $response->assertSee('More in Neurology');
        $response->assertSee('Dr Colleague');

        // The colleague's card links through to their own page, and only
        // same-department doctors are linked here. (Other names may still
        // appear in the booking form's dropdown, so assert on the URLs.)
        $response->assertSee($colleague->profileUrl(), false);
        $response->assertDontSee($unrelated->profileUrl(), false);
        $response->assertDontSee($hidden->profileUrl(), false);
    }

    /**
     * Booking from a profile pre-selects that doctor in the appointment form.
     */
    public function test_profile_page_preloads_the_booking_form(): void
    {
        $specialty = $this->specialty(['name' => 'Nephrology']);
        $specialist = $this->specialist([
            'name' => 'Dr Thomas Titus',
            'specialty_id' => $specialty->id,
        ]);

        $response = $this->get($specialist->profileUrl());

        $response->assertOk();
        $response->assertSee('Book with Dr Thomas Titus');

        // @js escapes quotes to \u0022 for safe use inside the attribute.
        $response->assertSee('\u0022specialistId\u0022:'.$specialist->id, false);
        $response->assertSee('\u0022specialty\u0022:\u0022Nephrology\u0022', false);
    }

    /**
     * The profile page carries the shared navigation and booking modal.
     */
    public function test_profile_page_includes_the_shared_layout(): void
    {
        $specialist = $this->specialist(['name' => 'Dr Sadasivan']);

        $response = $this->get($specialist->profileUrl());

        $response->assertOk();
        $response->assertSee('Main navigation');
        $response->assertSee('name="full_name"', false);
        $response->assertSee(route('appointments.store'), false);

        // Valid, unescaped structured data for search engines.
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@context":"https://schema.org"', false);
        $response->assertSee('"@type":"Physician"', false);
    }

    /**
     * Assert that the first string appears before the second in the markup.
     */
    protected function assertRendersInOrder(string $content, string $first, string $second): void
    {
        $firstPosition = strpos($content, $first);
        $secondPosition = strpos($content, $second);

        $this->assertNotFalse($firstPosition, "Expected to find [{$first}] in the page.");
        $this->assertNotFalse($secondPosition, "Expected to find [{$second}] in the page.");

        $this->assertTrue(
            $firstPosition < $secondPosition,
            "Expected [{$first}] to render before [{$second}].",
        );
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
