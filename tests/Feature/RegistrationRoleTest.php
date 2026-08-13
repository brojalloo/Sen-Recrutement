<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_rejects_the_admin_role(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Mallory',
            'last_name' => 'Attacker',
            'email' => 'mallory@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'mallory@example.com']);
        $this->assertGuest();
    }

    public function test_registration_accepts_the_candidate_role(): void
    {
        $this->post('/register', [
            'first_name' => 'Awa',
            'last_name' => 'Diop',
            'email' => 'awa@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'candidate',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'awa@example.com',
            'role' => 'candidate',
        ]);
    }

    public function test_registration_accepts_the_recruiter_role(): void
    {
        $this->post('/register', [
            'first_name' => 'Ibrahima',
            'last_name' => 'Fall',
            'email' => 'ibrahima@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'recruiter',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'ibrahima@example.com',
            'role' => 'recruiter',
        ]);
    }

    public function test_attempting_to_register_as_admin_leaves_the_attacker_unauthenticated(): void
    {
        $this->post('/register', [
            'first_name' => 'Mallory',
            'last_name' => 'Attacker',
            'email' => 'mallory2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $this->assertNull(User::where('email', 'mallory2@example.com')->first());
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_a_candidate_cannot_reach_the_admin_dashboard(): void
    {
        $this->post('/register', [
            'first_name' => 'Awa',
            'last_name' => 'Diop',
            'email' => 'awa2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'candidate',
        ]);

        $this->actingAs(User::where('email', 'awa2@example.com')->firstOrFail());

        $this->get('/admin/dashboard')->assertForbidden();
    }

    public function test_a_recruiter_cannot_reach_the_admin_dashboard(): void
    {
        $this->post('/register', [
            'first_name' => 'Ibrahima',
            'last_name' => 'Fall',
            'email' => 'ibrahima2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'recruiter',
        ]);

        $this->actingAs(User::where('email', 'ibrahima2@example.com')->firstOrFail());

        $this->get('/admin/dashboard')->assertForbidden();
    }
}
