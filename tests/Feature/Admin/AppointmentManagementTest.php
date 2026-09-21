<?php

namespace Tests\Feature\Admin;

use App\Models\Appointment;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    /**
     * The list shows every request with patient details.
     */
    public function test_appointments_are_listed(): void
    {
        $appointment = $this->appointment(['full_name' => 'Priya Raman']);

        $this->get(route('admin.appointments.index'))
            ->assertOk()
            ->assertSee('Priya Raman')
            ->assertSee($appointment->phone);
    }

    /**
     * The status filter narrows the list down.
     */
    public function test_appointments_can_be_filtered_by_status(): void
    {
        $this->appointment(['full_name' => 'New Request', 'status' => Appointment::STATUS_NEW]);
        $this->appointment(['full_name' => 'Booked Request', 'status' => Appointment::STATUS_BOOKED]);

        $this->get(route('admin.appointments.index', ['status' => Appointment::STATUS_NEW]))
            ->assertOk()
            ->assertSee('New Request')
            ->assertDontSee('Booked Request');
    }

    /**
     * The search box matches patient contact details.
     */
    public function test_appointments_can_be_searched(): void
    {
        $this->appointment(['full_name' => 'Marcus Whitfield', 'phone' => '0412 884 219']);
        $this->appointment(['full_name' => 'Helen Prescott', 'phone' => '0433 771 045']);

        $this->get(route('admin.appointments.index', ['q' => 'Helen']))
            ->assertOk()
            ->assertSee('Helen Prescott')
            ->assertDontSee('Marcus Whitfield');

        $this->get(route('admin.appointments.index', ['q' => '0412 884']))
            ->assertOk()
            ->assertSee('Marcus Whitfield')
            ->assertDontSee('Helen Prescott');
    }

    /**
     * An unknown status filter is ignored rather than erroring.
     */
    public function test_an_invalid_status_filter_is_ignored(): void
    {
        $this->appointment(['full_name' => 'Priya Raman']);

        $this->get(route('admin.appointments.index', ['status' => 'nonsense']))
            ->assertOk()
            ->assertSee('Priya Raman');
    }

    /**
     * The detail screen shows the full request.
     */
    public function test_the_detail_screen_shows_the_request(): void
    {
        $appointment = $this->appointment([
            'full_name' => 'Daniel Osei',
            'notes' => 'GP arranging referral this week.',
        ]);

        $this->get(route('admin.appointments.show', $appointment))
            ->assertOk()
            ->assertSee('Daniel Osei')
            ->assertSee('GP arranging referral this week.');
    }

    /**
     * Status and internal notes can be updated from the detail screen.
     */
    public function test_status_and_internal_notes_can_be_updated(): void
    {
        $appointment = $this->appointment();

        $this->put(route('admin.appointments.update', $appointment), [
            'status' => Appointment::STATUS_BOOKED,
            'admin_notes' => 'Confirmed for Tuesday 10am.',
        ])->assertRedirect();

        $appointment->refresh();

        $this->assertSame(Appointment::STATUS_BOOKED, $appointment->status);
        $this->assertSame('Confirmed for Tuesday 10am.', $appointment->admin_notes);
    }

    /**
     * The table offers a quick status change.
     */
    public function test_status_can_be_changed_from_the_table(): void
    {
        $appointment = $this->appointment();

        $this->patch(route('admin.appointments.status', $appointment), [
            'status' => Appointment::STATUS_CONTACTED,
        ])->assertRedirect();

        $this->assertSame(Appointment::STATUS_CONTACTED, $appointment->refresh()->status);
        $this->assertSame('Marked as Contacted.', session('success'));
    }

    /**
     * An unsupported status is rejected.
     */
    public function test_an_invalid_status_is_rejected(): void
    {
        $appointment = $this->appointment();

        $this->patch(route('admin.appointments.status', $appointment), [
            'status' => 'archived',
        ])->assertSessionHasErrors('status');

        $this->assertSame(Appointment::STATUS_NEW, $appointment->refresh()->status);
    }

    /**
     * Requests can be deleted.
     */
    public function test_a_request_can_be_deleted(): void
    {
        $appointment = $this->appointment();

        $this->delete(route('admin.appointments.destroy', $appointment))
            ->assertRedirect(route('admin.appointments.index'));

        $this->assertDatabaseCount('appointments', 0);
    }

    /**
     * Removing a specialist keeps the historical request.
     */
    public function test_removing_a_specialist_preserves_their_requests(): void
    {
        $specialist = Specialist::create([
            'name' => 'Dr Test Subject',
            'qualifications' => 'MBBS',
            'role' => 'Consultant',
        ]);

        $appointment = $this->appointment(['specialist_id' => $specialist->id, 'specialty' => 'Neurology']);

        $specialist->delete();

        $this->assertDatabaseCount('appointments', 1);

        $appointment->refresh();

        $this->assertNull($appointment->specialist_id);
        $this->assertSame('Neurology', $appointment->specialty);
    }

    /**
     * Several requests can have their status changed at once.
     */
    public function test_requests_can_be_bulk_updated_to_a_status(): void
    {
        $first = $this->appointment();
        $second = $this->appointment();
        $untouched = $this->appointment();

        $this->post(route('admin.appointments.bulk'), [
            'ids' => [$first->id, $second->id],
            'action' => 'status',
            'status' => Appointment::STATUS_CONTACTED,
        ])->assertRedirect();

        $this->assertSame(Appointment::STATUS_CONTACTED, $first->refresh()->status);
        $this->assertSame(Appointment::STATUS_CONTACTED, $second->refresh()->status);
        $this->assertSame(Appointment::STATUS_NEW, $untouched->refresh()->status);
        $this->assertSame('2 requests marked as Contacted.', session('success'));
    }

    /**
     * Several requests can be deleted at once.
     */
    public function test_requests_can_be_bulk_deleted(): void
    {
        $first = $this->appointment();
        $second = $this->appointment();
        $kept = $this->appointment();

        $this->post(route('admin.appointments.bulk'), [
            'ids' => [$first->id, $second->id],
            'action' => 'delete',
        ])->assertRedirect();

        $this->assertDatabaseCount('appointments', 1);
        $this->assertDatabaseHas('appointments', ['id' => $kept->id]);
        $this->assertSame('2 requests deleted.', session('success'));
    }

    /**
     * Bulk actions are rejected when nothing is selected.
     */
    public function test_bulk_actions_require_a_selection(): void
    {
        $this->appointment();

        $this->from(route('admin.appointments.index'))
            ->post(route('admin.appointments.bulk'), ['action' => 'delete'])
            ->assertRedirect(route('admin.appointments.index'))
            ->assertSessionHasErrors('ids');

        $this->assertDatabaseCount('appointments', 1);
    }

    /**
     * A bulk status change must target a real status.
     */
    public function test_bulk_status_change_rejects_an_invalid_status(): void
    {
        $appointment = $this->appointment();

        $this->post(route('admin.appointments.bulk'), [
            'ids' => [$appointment->id],
            'action' => 'status',
            'status' => 'archived',
        ])->assertSessionHasErrors('status');

        $this->assertSame(Appointment::STATUS_NEW, $appointment->refresh()->status);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function appointment(array $attributes = []): Appointment
    {
        return Appointment::create([
            'full_name' => 'Test Patient',
            'phone' => '0400 000 000',
            'specialty' => 'Neurology',
            'referral_status' => 'yes',
            ...$attributes,
        ]);
    }
}
