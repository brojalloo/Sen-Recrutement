<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecruiterApplicationCvViewTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $email, string $role): User
    {
        return User::create([
            'name' => $email,
            'email' => $email,
            'password' => 'password123',
            'role' => $role,
            'status' => 'active',
        ]);
    }

    private function application(User $candidate, User $recruiter, ?string $cvPath): Application
    {
        $job = Job::create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Développeur',
            'description' => 'Développement web',
            'company' => 'TechCorp',
            'status' => 'active',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        return Application::create([
            'job_id' => $job->id,
            'user_id' => $candidate->id,
            'status' => 'pending',
            'cv_path' => $cvPath,
        ]);
    }

    public function test_recruiter_dashboard_links_to_the_cv_attached_to_the_application(): void
    {
        Storage::fake('local');

        $candidate = $this->user('candidate@example.com', 'candidate');
        $recruiter = $this->user('recruiter@example.com', 'recruiter');
        $application = $this->application($candidate, $recruiter, 'cvs/attached.pdf');
        Storage::disk('local')->put('cvs/attached.pdf', 'CV de candidature');

        $response = $this->actingAs($recruiter)->get('/recruiter/dashboard');

        $response->assertOk();
        $response->assertSee(route('cv.download', $application->id), false);
        $response->assertDontSee(route('download.cv', $candidate->id), false);

        $this->get(route('cv.download', $application->id))->assertOk();
    }

    public function test_profile_cv_does_not_masquerade_as_an_application_attachment(): void
    {
        Storage::fake('local');

        $candidate = $this->user('candidate@example.com', 'candidate');
        $candidate->update(['cv_path' => 'cvs/profile.pdf']);
        Storage::disk('local')->put('cvs/profile.pdf', 'CV général');

        $recruiter = $this->user('recruiter@example.com', 'recruiter');
        $application = $this->application($candidate, $recruiter, null);

        $response = $this->actingAs($recruiter)->get('/recruiter/dashboard');

        $response->assertOk();
        $response->assertSee('Pas de CV joint');
        $response->assertDontSee(route('cv.download', $application->id), false);
        $response->assertDontSee(route('download.cv', $candidate->id), false);
    }
}
