<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuplicateApplicationTest extends TestCase
{
    use RefreshDatabase;

    private function recruiter(string $email = 'recruteur@example.com'): User
    {
        return User::create([
            'name' => 'Recruteur',
            'email' => $email,
            'password' => 'password123',
            'role' => 'recruiter',
            'status' => 'active',
        ]);
    }

    private function candidate(string $email = 'candidat@example.com'): User
    {
        return User::create([
            'name' => 'Candidat',
            'email' => $email,
            'password' => 'password123',
            'role' => 'candidate',
            'status' => 'active',
        ]);
    }

    private function job(User $recruiter, string $title = 'Développeur'): Job
    {
        return Job::create([
            'recruiter_id' => $recruiter->id,
            'title' => $title,
            'description' => 'Description',
            'company' => 'TechCorp',
            'status' => 'active',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
    }

    public function test_a_candidate_cannot_apply_twice_to_the_same_job(): void
    {
        $job = $this->job($this->recruiter());
        $candidate = $this->candidate();

        $this->actingAs($candidate)->post("/jobs/{$job->id}/apply", ['message' => 'Première']);
        $second = $this->actingAs($candidate)->post("/jobs/{$job->id}/apply", ['message' => 'Deuxième']);

        $second->assertSessionHasErrors();
        $this->assertSame(1, Application::count());
    }

    public function test_the_first_application_is_kept_intact(): void
    {
        $job = $this->job($this->recruiter());
        $candidate = $this->candidate();

        $this->actingAs($candidate)->post("/jobs/{$job->id}/apply", ['message' => 'Première']);
        $this->actingAs($candidate)->post("/jobs/{$job->id}/apply", ['message' => 'Deuxième']);

        $this->assertSame('Première', Application::firstOrFail()->message);
    }

    public function test_two_candidates_can_apply_to_the_same_job(): void
    {
        $job = $this->job($this->recruiter());

        $this->actingAs($this->candidate('un@example.com'))
            ->post("/jobs/{$job->id}/apply", ['message' => 'Candidature 1']);
        $this->actingAs($this->candidate('deux@example.com'))
            ->post("/jobs/{$job->id}/apply", ['message' => 'Candidature 2']);

        $this->assertSame(2, Application::count());
    }

    public function test_one_candidate_can_apply_to_two_jobs(): void
    {
        $recruiter = $this->recruiter();
        $first = $this->job($recruiter, 'Développeur');
        $second = $this->job($recruiter, 'Designer');
        $candidate = $this->candidate();

        $this->actingAs($candidate)->post("/jobs/{$first->id}/apply", ['message' => 'A']);
        $this->actingAs($candidate)->post("/jobs/{$second->id}/apply", ['message' => 'B']);

        $this->assertSame(2, Application::count());
    }

    public function test_the_database_refuses_a_duplicate_even_without_the_controller(): void
    {
        $job = $this->job($this->recruiter());
        $candidate = $this->candidate();

        $row = [
            'job_id' => $job->id,
            'user_id' => $candidate->id,
            'status' => 'pending',
            'applied_at' => now(),
        ];

        Application::create($row);

        $this->expectException(QueryException::class);

        Application::create($row);
    }
}
