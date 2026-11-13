<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InactiveAccountAccessTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $email, string $role = 'candidate', string $status = 'active'): User
    {
        return User::create([
            'name' => $email,
            'email' => $email,
            'password' => 'password123',
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function test_a_disabled_account_cannot_log_in(): void
    {
        $this->user('inactive@example.com', 'candidate', 'inactive');

        $this->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_disabling_an_account_revokes_an_existing_session(): void
    {
        $candidate = $this->user('candidate@example.com');

        $this->actingAs($candidate)->get('/candidate/dashboard')->assertOk();

        $candidate->update(['status' => 'inactive']);

        $this->get('/candidate/dashboard')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_a_disabled_account_cannot_access_shared_routes(): void
    {
        $candidate = $this->user('candidate@example.com', 'candidate', 'inactive');

        $this->actingAs($candidate)
            ->get('/messages')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_a_disabled_account_receives_a_forbidden_response_for_json_requests(): void
    {
        $candidate = $this->user('candidate@example.com', 'candidate', 'inactive');

        $this->actingAs($candidate)
            ->getJson('/messages')
            ->assertForbidden();

        $this->assertGuest();
    }

    public function test_an_admin_cannot_deactivate_their_own_account(): void
    {
        $admin = $this->user('admin@example.com', 'admin');

        $this->actingAs($admin)
            ->put("/admin/users/{$admin->id}/toggle-status")
            ->assertSessionHasErrors('user');

        $this->assertSame('active', $admin->fresh()->status);
    }

    public function test_an_admin_can_deactivate_another_account_and_block_access(): void
    {
        $admin = $this->user('admin@example.com', 'admin');
        $candidate = $this->user('candidate@example.com');

        $this->actingAs($admin)
            ->put("/admin/users/{$candidate->id}/toggle-status")
            ->assertRedirect();

        $this->assertSame('inactive', $candidate->fresh()->status);

        $this->actingAs($candidate)
            ->get('/candidate/dashboard')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
