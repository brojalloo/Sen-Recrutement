<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobApprovalVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private User $recruiter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->recruiter = User::create([
            'name' => 'Recruteur',
            'email' => 'recruteur@example.com',
            'password' => 'password123',
            'role' => 'recruiter',
            'status' => 'active',
        ]);
    }

    private function job(string $title, string $approval): Job
    {
        return Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => $title,
            'description' => 'Description du poste',
            'company' => 'TechCorp',
            'status' => 'active',
            'approval_status' => $approval,
            'is_active' => true,
        ]);
    }

    private function candidate(): User
    {
        return User::create([
            'name' => 'Candidat',
            'email' => 'candidat@example.com',
            'password' => 'password123',
            'role' => 'candidate',
            'status' => 'active',
        ]);
    }

    public function test_the_home_page_hides_jobs_awaiting_approval(): void
    {
        $this->job('Poste approuve', 'approved');
        $this->job('Poste en attente', 'pending');

        $response = $this->get('/');

        $response->assertSee('Poste approuve');
        $response->assertDontSee('Poste en attente');
    }

    public function test_the_home_page_hides_rejected_jobs(): void
    {
        $this->job('Poste approuve', 'approved');
        $this->job('Poste rejete', 'rejected');

        $response = $this->get('/');

        $response->assertDontSee('Poste rejete');
    }

    public function test_the_candidate_dashboard_hides_unapproved_jobs(): void
    {
        $this->job('Poste approuve', 'approved');
        $this->job('Poste rejete', 'rejected');

        $response = $this->actingAs($this->candidate())->get('/candidate/dashboard');

        $response->assertSee('Poste approuve');
        $response->assertDontSee('Poste rejete');
    }

    public function test_a_candidate_cannot_open_the_apply_form_of_an_unapproved_job(): void
    {
        $job = $this->job('Poste rejete', 'rejected');

        $this->actingAs($this->candidate())
            ->get("/jobs/{$job->id}/apply")
            ->assertNotFound();
    }

    public function test_a_candidate_cannot_apply_to_an_unapproved_job(): void
    {
        $job = $this->job('Poste en attente', 'pending');

        $this->actingAs($this->candidate())
            ->post("/jobs/{$job->id}/apply", ['message' => 'Ma candidature'])
            ->assertNotFound();

        $this->assertSame(0, Application::count());
    }

    public function test_a_candidate_can_still_apply_to_an_approved_job(): void
    {
        $job = $this->job('Poste approuve', 'approved');

        $this->actingAs($this->candidate())
            ->post("/jobs/{$job->id}/apply", ['message' => 'Ma candidature'])
            ->assertRedirect(route('candidate.dashboard'));

        $this->assertSame(1, Application::count());
    }
}
