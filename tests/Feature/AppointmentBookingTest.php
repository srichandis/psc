<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Specialist;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A valid request is stored in SQLite and confirmed to the patient.
     */
    public function test_a_valid_request_is_stored_and_confirmed(): void
    {
        $specialty = $this->specialty();
        $specialist = $this->specialist(['specialty_id' => $specialty->id]);

        $response = $this->from('/')->post(route('appointments.store'), [
            'full_name' => 'Jordan Blake',
            'phone' => '0412 345 678',
            'email' => 'jordan@example.com',
            'specialty' => $specialty->name,
            'specialist_id' => $specialist->id,
            'referral_status' => 'yes',
            'preferred_date' => now()->addWeek()->toDateString(),
            'preferred_time' => 'Morning (9:00am - 12:00pm)',
            'notes' => 'Referred by Dr Nguyen.',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('appointment_booked', 'Jordan Blake');

        $this->assertDatabaseHas('appointments', [
            'full_name' => 'Jordan Blake',
            'phone' => '0412 345 678',
            'specialty' => 'Neurology',
            'specialist_id' => $specialist->id,
            'referral_status' => 'yes',
            'status' => Appointment::STATUS_NEW,
        ]);
    }

    /**
     * A request without a specialist is still accepted.
     */
    public function test_a_request_without_a_specialist_is_accepted(): void
    {
        $specialty = $this->specialty();

        $this->post(route('appointments.store'), [
            'full_name' => 'Sam Ellery',
            'phone' => '0400 111 222',
            'specialty' => $specialty->name,
            'referral_status' => 'pending',
        ])->assertRedirect(route('home'));

        $this->assertDatabaseHas('appointments', [
            'full_name' => 'Sam Ellery',
            'specialist_id' => null,
            'status' => Appointment::STATUS_NEW,
        ]);
    }

    /**
     * Required patient details are enforced.
     */
    public function test_patient_name_and_phone_are_required(): void
    {
        $specialty = $this->specialty();

        $this->from('/')
            ->post(route('appointments.store'), [
                'specialty' => $specialty->name,
                'referral_status' => 'yes',
            ])
            ->assertRedirect('/')
            ->assertSessionHasErrors(['full_name', 'phone']);

        $this->assertDatabaseCount('appointments', 0);
    }

    /**
     * A specialty that does not exist is rejected.
     */
    public function test_an_unknown_specialty_is_rejected(): void
    {
        $this->from('/')
            ->post(route('appointments.store'), [
                'full_name' => 'Casey Doyle',
                'phone' => '0400 000 000',
                'specialty' => 'Astrology',
                'referral_status' => 'yes',
            ])
            ->assertSessionHasErrors('specialty');

        $this->assertDatabaseCount('appointments', 0);
    }

    /**
     * A specialist hidden from the website cannot be booked directly.
     */
    public function test_a_hidden_specialist_cannot_be_booked(): void
    {
        $specialty = $this->specialty();
        $hidden = $this->specialist(['is_active' => false]);

        $this->from('/')
            ->post(route('appointments.store'), [
                'full_name' => 'Robin Ellis',
                'phone' => '0400 000 001',
                'specialty' => $specialty->name,
                'specialist_id' => $hidden->id,
                'referral_status' => 'yes',
            ])
            ->assertSessionHasErrors('specialist_id');

        $this->assertDatabaseCount('appointments', 0);
    }

    /**
     * Preferred dates must not be in the past.
     */
    public function test_a_preferred_date_in_the_past_is_rejected(): void
    {
        $specialty = $this->specialty();

        $this->from('/')
            ->post(route('appointments.store'), [
                'full_name' => 'Alex Moreau',
                'phone' => '0400 000 002',
                'specialty' => $specialty->name,
                'referral_status' => 'yes',
                'preferred_date' => now()->subMonth()->toDateString(),
            ])
            ->assertSessionHasErrors('preferred_date');

        $this->assertDatabaseCount('appointments', 0);
    }

    /**
     * The referral answer must be one of the supported options.
     */
    public function test_the_referral_answer_must_be_valid(): void
    {
        $specialty = $this->specialty();

        $this->from('/')
            ->post(route('appointments.store'), [
                'full_name' => 'Alex Moreau',
                'phone' => '0400 000 003',
                'specialty' => $specialty->name,
                'referral_status' => 'maybe',
            ])
            ->assertSessionHasErrors('referral_status');
    }

    /**
     * The homepage re-opens the modal and repopulates it after a failure.
     */
    public function test_the_booking_modal_reopens_with_old_input_after_an_error(): void
    {
        $specialty = $this->specialty();

        $this->from('/')->post(route('appointments.store'), [
            'full_name' => 'Miriam Cho',
            'phone' => '0400 000 004',
            'specialty' => $specialty->name,
            'referral_status' => 'yes',
            'preferred_date' => now()->subWeek()->toDateString(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Miriam Cho')
            ->assertSee('Please choose today or a future date.');
    }

    protected function specialty(): Specialty
    {
        return Specialty::create([
            'name' => 'Neurology',
            'short_description' => 'Brain and nervous system care.',
            'icon' => 'brain',
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
