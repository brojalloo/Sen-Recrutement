<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingRecipientsTest extends TestCase
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

    private function application(User $candidate, User $recruiter): Application
    {
        $job = Job::create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Développeur',
            'description' => 'Poste de développeur',
            'company' => 'TechCorp',
            'status' => 'active',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        return Application::create([
            'job_id' => $job->id,
            'user_id' => $candidate->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);
    }

    public function test_a_candidate_only_sees_recruiters_they_applied_to(): void
    {
        $candidate = $this->user('candidate', 'candidat@example.com');
        $connected = $this->user('recruiter', 'connecte@example.com');
        $stranger = $this->user('recruiter', 'inconnu@example.com');

        $this->application($candidate, $connected);

        $response = $this->actingAs($candidate)->get('/messages/compose');

        $response->assertOk();
        $response->assertSee('connecte@example.com');
        $response->assertDontSee('inconnu@example.com');
    }

    public function test_a_candidate_does_not_see_other_candidates(): void
    {
        $candidate = $this->user('candidate', 'candidat@example.com');
        $recruiter = $this->user('recruiter', 'connecte@example.com');
        $otherCandidate = $this->user('candidate', 'autre-candidat@example.com');

        $this->application($candidate, $recruiter);
        $this->application($otherCandidate, $recruiter);

        $response = $this->actingAs($candidate)->get('/messages/compose');

        $response->assertDontSee('autre-candidat@example.com');
    }

    public function test_a_recruiter_only_sees_candidates_who_applied_to_their_jobs(): void
    {
        $recruiter = $this->user('recruiter', 'recruteur@example.com');
        $applicant = $this->user('candidate', 'postulant@example.com');
        $stranger = $this->user('candidate', 'inconnu@example.com');
        $otherRecruiter = $this->user('recruiter', 'autre-recruteur@example.com');

        $this->application($applicant, $recruiter);
        $this->application($stranger, $otherRecruiter);

        $response = $this->actingAs($recruiter)->get('/messages/compose');

        $response->assertSee('postulant@example.com');
        $response->assertDontSee('inconnu@example.com');
    }

    public function test_an_admin_sees_every_user(): void
    {
        $admin = $this->user('admin', 'admin@example.com');
        $this->user('candidate', 'candidat@example.com');
        $this->user('recruiter', 'recruteur@example.com');

        $response = $this->actingAs($admin)->get('/messages/compose');

        $response->assertSee('candidat@example.com');
        $response->assertSee('recruteur@example.com');
    }

    public function test_sending_to_an_unrelated_user_is_rejected(): void
    {
        $candidate = $this->user('candidate', 'candidat@example.com');
        $stranger = $this->user('recruiter', 'inconnu@example.com');

        $response = $this->actingAs($candidate)->post('/messages/send', [
            'recipient_id' => $stranger->id,
            'subject' => 'Bonjour',
            'message' => 'Message non sollicité',
        ]);

        $response->assertSessionHasErrors('recipient_id');
        $this->assertSame(0, Message::count());
    }

    public function test_sending_to_a_related_user_still_works(): void
    {
        $candidate = $this->user('candidate', 'candidat@example.com');
        $recruiter = $this->user('recruiter', 'recruteur@example.com');

        $this->application($candidate, $recruiter);

        $this->actingAs($candidate)->post('/messages/send', [
            'recipient_id' => $recruiter->id,
            'subject' => 'Question',
            'message' => 'Bonjour, une question sur le poste.',
        ])->assertRedirect(route('messages.outbox'));

        $this->assertSame(1, Message::count());
    }
}
