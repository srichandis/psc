<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Every admin screen requires a signed in staff member.
     */
    public function test_guests_are_redirected_to_the_staff_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.appointments.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.specialists.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.specialties.index'))->assertRedirect(route('admin.login'));
    }

    /**
     * The login screen is reachable by guests.
     */
    public function test_the_login_screen_renders(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Staff sign in');
    }

    /**
     * Correct credentials sign the staff member in.
     */
    public function test_a_staff_member_can_sign_in(): void
    {
        $user = User::factory()->create([
            'email' => 'reception@example.com',
            'password' => Hash::make('secret-password'),
        ]);

        $this->post(route('admin.login.store'), [
            'email' => 'reception@example.com',
            'password' => 'secret-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Incorrect credentials are rejected.
     */
    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'reception@example.com',
            'password' => Hash::make('secret-password'),
        ]);

        $this->from(route('admin.login'))
            ->post(route('admin.login.store'), [
                'email' => 'reception@example.com',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    /**
     * Signed in staff can reach the dashboard.
     */
    public function test_a_signed_in_staff_member_can_view_the_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total requests')
            ->assertSee('Awaiting action');
    }

    /**
     * Signing out returns the visitor to the login screen.
     */
    public function test_a_staff_member_can_sign_out(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    /**
     * A signed in staff member visiting the login screen is sent onward.
     */
    public function test_signed_in_staff_are_redirected_away_from_the_login_screen(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.login'))
            ->assertRedirect(route('admin.dashboard'));
    }
}
