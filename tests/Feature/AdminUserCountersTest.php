<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Les cartes « Candidats / Recruteurs / Admins » comptaient les rôles sur la
 * collection paginée, donc sur les 20 utilisateurs de la page courante et non
 * sur l'ensemble. Passé le 21ᵉ inscrit, les chiffres affichés étaient faux.
 */
class AdminUserCountersTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role, string $email): User
    {
        return User::create([
            'name' => $email,
            'email' => $email,
            'password' => 'password123',
            'role' => $role,
            'status' => 'active',
        ]);
    }

    public function test_the_counters_cover_every_user_not_just_the_first_page(): void
    {
        $admin = $this->user('admin', 'admin@example.com');

        // Au-delà d'une page (20 par page) pour que le bug se manifeste.
        for ($i = 0; $i < 25; $i++) {
            $this->user('candidate', "candidat{$i}@example.com");
        }
        for ($i = 0; $i < 3; $i++) {
            $this->user('recruiter', "recruteur{$i}@example.com");
        }

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertOk();
        $response->assertViewHas('roleCounts', [
            'candidate' => 25,
            'recruiter' => 3,
            'admin' => 1,
        ]);
    }

    public function test_the_counters_hold_when_everything_fits_on_one_page(): void
    {
        $admin = $this->user('admin', 'admin@example.com');
        $this->user('candidate', 'candidat@example.com');
        $this->user('recruiter', 'recruteur@example.com');

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertViewHas('roleCounts', [
            'candidate' => 1,
            'recruiter' => 1,
            'admin' => 1,
        ]);
    }
}
