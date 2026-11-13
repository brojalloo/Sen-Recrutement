<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobReapprovalTest extends TestCase
{
    use RefreshDatabase;

    private function recruiter(string $email = 'recruiter@example.com'): User
    {
        return User::create([
            'name' => 'Recruteur',
            'email' => $email,
            'password' => 'password123',
            'role' => 'recruiter',
            'status' => 'active',
        ]);
    }

    private function job(User $recruiter): Job
    {
        return Job::create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Poste initial',
            'description' => 'Description initiale',
            'company' => 'TechCorp',
            'status' => 'active',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
    }

    private function updateData(string $title = 'Poste initial'): array
    {
        return [
            'title' => $title,
            'description' => 'Description initiale',
            'company' => 'TechCorp',
            'status' => 'active',
        ];
    }

    public function test_editing_an_approved_job_forces_another_review(): void
    {
        $recruiter = $this->recruiter();
        $job = $this->job($recruiter);

        $this->actingAs($recruiter)
            ->put("/recruiter/jobs/{$job->id}", $this->updateData('Poste modifié'))
            ->assertRedirect(route('recruiter.jobs.index'));

        $this->assertSame('pending', $job->fresh()->approval_status);
        $this->get("/jobs/{$job->id}")->assertNotFound();
        $this->get('/')->assertDontSee('Poste modifié');
    }

    public function test_reapproval_is_not_requested_for_an_unchanged_job(): void
    {
        $recruiter = $this->recruiter();
        $job = $this->job($recruiter);

        $this->actingAs($recruiter)
            ->put("/recruiter/jobs/{$job->id}", $this->updateData())
            ->assertRedirect(route('recruiter.jobs.index'));

        $this->assertSame('approved', $job->fresh()->approval_status);
        $this->get("/jobs/{$job->id}")->assertOk();
    }

    public function test_changing_an_approved_job_logo_forces_another_review(): void
    {
        Storage::fake('public');

        $recruiter = $this->recruiter();
        $job = $this->job($recruiter);

        $this->actingAs($recruiter)
            ->post("/recruiter/jobs/{$job->id}/logo", [
                'logo' => UploadedFile::fake()->image('logo.png'),
            ])
            ->assertRedirect();

        $this->assertSame('pending', $job->fresh()->approval_status);
        $this->get("/jobs/{$job->id}")->assertNotFound();
    }

    public function test_another_recruiter_cannot_modify_the_job_or_approval(): void
    {
        $job = $this->job($this->recruiter());
        $stranger = $this->recruiter('another@example.com');

        $this->actingAs($stranger)
            ->put("/recruiter/jobs/{$job->id}", $this->updateData('Modification interdite'))
            ->assertNotFound();

        $this->assertSame('approved', $job->fresh()->approval_status);
        $this->assertSame('Poste initial', $job->fresh()->title);
    }
}
