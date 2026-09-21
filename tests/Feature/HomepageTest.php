<?php

namespace Tests\Feature;

use App\Models\Specialist;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The homepage renders with the clinic content and booking form.
     */
    public function test_homepage_renders_the_clinic_content(): void
    {
        $specialty = $this->specialty(['name' => 'Neurology']);
        $this->specialist(['name' => 'Dr Ada Lovelace', 'specialty_id' => $specialty->id]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Premier Specialist Clinic');
        $response->assertSee('Meet Our Specialists');
        $response->assertSee('Dr Ada Lovelace');
        $response->assertSee('Comprehensive Specialist Care');
        $response->assertSee('A Simple Appointment Process');
        $response->assertSee('Our Location');

        // The booking modal markup is present on the public page.
        $response->assertSee('Request an Appointment');
        $response->assertSee('name="full_name"', false);
        $response->assertSee(route('appointments.store'), false);
    }

    /**
     * Specialists hidden from the website must not be listed.
     */
    public function test_inactive_specialists_are_hidden_from_the_homepage(): void
    {
        $this->specialist(['name' => 'Dr Visible']);
        $this->specialist(['name' => 'Dr Hidden', 'is_active' => false]);

        $this->get('/')
            ->assertSee('Dr Visible')
            ->assertDontSee('Dr Hidden');
    }

    /**
     * Specialties hidden from the website must not be listed.
     */
    public function test_inactive_specialties_are_hidden_from_the_homepage(): void
    {
        $this->specialty(['name' => 'Neurology']);
        $this->specialty(['name' => 'Retired Care', 'is_active' => false]);

        $this->get('/')
            ->assertSee('Neurology')
            ->assertDontSee('Retired Care');
    }

    /**
     * Specialists and specialties appear in the order set in the admin panel.
     */
    public function test_specialists_and_specialties_respect_display_order(): void
    {
        $this->specialty(['name' => 'Second Department', 'sort_order' => 2]);
        $this->specialty(['name' => 'First Department', 'sort_order' => 1]);

        $this->specialist(['name' => 'Dr Second Listed', 'sort_order' => 2]);
        $this->specialist(['name' => 'Dr First Listed', 'sort_order' => 1]);

        $content = $this->get('/')->getContent();

        $this->assertRendersInOrder($content, 'First Department', 'Second Department');
        $this->assertRendersInOrder($content, 'Dr First Listed', 'Dr Second Listed');
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
