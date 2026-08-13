<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Garde-fou de refactor : les compteurs du profil recruteur étaient calculés
 * dans la vue en chargeant toutes les offres en mémoire pour les sommer en
 * PHP. Le calcul passe en SQL ; ce test vérifie que les chiffres affichés ne
 * changent pas. Il passe avant comme après — c'est son rôle.
 */
class RecruiterProfileStatsTest extends TestCase
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

    private function job(User $recruiter, bool $active): Job
    {
        return Job::create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Développeur',
            'description' => 'Poste',
            'company' => 'TechCorp',
            'status' => 'active',
            'approval_status' => 'approved',
            'is_active' => $active,
        ]);
    }

    public function test_it_counts_active_jobs_and_received_applications(): void
    {
        $recruiter = $this->user('recruiter', 'recruteur@example.com');

        $first = $this->job($recruiter, active: true);
        $second = $this->job($recruiter, active: true);
        $this->job($recruiter, active: false);

        foreach ([$first, $first, $second] as $job) {
            Application::create([
                'job_id' => $job->id,
                'user_id' => $this->user('candidate', uniqid().'@example.com')->id,
                'status' => 'pending',
                'applied_at' => now(),
            ]);
        }

        $response = $this->actingAs($recruiter)->get('/recruiter/profile');

        $response->assertOk();
        $response->assertSee('<h4 class="mb-0 fw-bold text-primary">2</h4>', false);
        $response->assertSee('<h4 class="mb-0 fw-bold text-success">3</h4>', false);
    }

    public function test_it_ignores_jobs_and_applications_of_other_recruiters(): void
    {
        $recruiter = $this->user('recruiter', 'recruteur@example.com');
        $other = $this->user('recruiter', 'autre@example.com');

        $this->job($recruiter, active: true);

        $foreignJob = $this->job($other, active: true);
        Application::create([
            'job_id' => $foreignJob->id,
            'user_id' => $this->user('candidate', 'candidat@example.com')->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        $response = $this->actingAs($recruiter)->get('/recruiter/profile');

        $response->assertSee('<h4 class="mb-0 fw-bold text-primary">1</h4>', false);
        $response->assertSee('<h4 class="mb-0 fw-bold text-success">0</h4>', false);
    }
}
