<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Propriété des ressources : ce que chaque rôle a le droit de toucher.
 *
 * Ces règles vivaient éparpillées dans les contrôleurs, sans aucun test. Elles
 * sont fixées ici avant d'être déplacées, pour que le déplacement ne puisse pas
 * les changer en silence.
 *
 * Le CRUD des offres répond 404 — et non 403 — sur l'offre d'un autre
 * recruteur : la requête est filtrée avant le `findOrFail`, ce qui évite de
 * révéler qu'une offre existe. C'est volontaire, et testé comme tel.
 */
class OwnershipTest extends TestCase
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

    private function job(User $recruiter): Job
    {
        return Job::create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Développeur',
            'description' => 'Poste de développeur',
            'company' => 'TechCorp',
            'status' => 'active',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
    }

    public function test_a_recruiter_cannot_open_the_edit_form_of_another_recruiters_job(): void
    {
        $job = $this->job($this->user('recruiter', 'proprietaire@example.com'));
        $intruder = $this->user('recruiter', 'intrus@example.com');

        $this->actingAs($intruder)
            ->get("/recruiter/jobs/{$job->id}/edit")
            ->assertNotFound();
    }

    public function test_a_recruiter_cannot_update_another_recruiters_job(): void
    {
        $job = $this->job($this->user('recruiter', 'proprietaire@example.com'));
        $intruder = $this->user('recruiter', 'intrus@example.com');

        $this->actingAs($intruder)
            ->put("/recruiter/jobs/{$job->id}", [
                'title' => 'Titre détourné',
                'description' => 'Description détournée',
                'company' => 'Intrus SARL',
            ])
            ->assertNotFound();

        $this->assertSame('Développeur', $job->fresh()->title);
    }

    public function test_a_recruiter_cannot_delete_another_recruiters_job(): void
    {
        $job = $this->job($this->user('recruiter', 'proprietaire@example.com'));
        $intruder = $this->user('recruiter', 'intrus@example.com');

        $this->actingAs($intruder)
            ->delete("/recruiter/jobs/{$job->id}")
            ->assertNotFound();

        $this->assertNotNull($job->fresh());
    }

    public function test_a_recruiter_cannot_replace_the_logo_of_another_recruiters_job(): void
    {
        Storage::fake('public');

        $job = $this->job($this->user('recruiter', 'proprietaire@example.com'));
        $intruder = $this->user('recruiter', 'intrus@example.com');

        $this->actingAs($intruder)
            ->post("/recruiter/jobs/{$job->id}/logo", [
                'logo' => UploadedFile::fake()->image('logo.png'),
            ])
            ->assertNotFound();

        $this->assertNull($job->fresh()->company_logo);
    }

    public function test_a_recruiter_can_update_their_own_job(): void
    {
        $recruiter = $this->user('recruiter', 'proprietaire@example.com');
        $job = $this->job($recruiter);

        $this->actingAs($recruiter)
            ->put("/recruiter/jobs/{$job->id}", [
                'title' => 'Développeur senior',
                'description' => 'Poste de développeur senior',
                'company' => 'TechCorp',
            ])
            ->assertRedirect(route('recruiter.jobs.index'));

        $this->assertSame('Développeur senior', $job->fresh()->title);
    }

    public function test_a_recruiter_cannot_accept_an_application_on_another_recruiters_job(): void
    {
        $job = $this->job($this->user('recruiter', 'proprietaire@example.com'));
        $intruder = $this->user('recruiter', 'intrus@example.com');

        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => $this->user('candidate', 'candidat@example.com')->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        $this->actingAs($intruder)
            ->put("/recruiter/applications/{$application->id}/accept")
            ->assertNotFound();

        $this->assertSame('pending', $application->fresh()->status);
    }

    public function test_a_recruiter_cannot_reject_an_application_on_another_recruiters_job(): void
    {
        $job = $this->job($this->user('recruiter', 'proprietaire@example.com'));
        $intruder = $this->user('recruiter', 'intrus@example.com');

        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => $this->user('candidate', 'candidat@example.com')->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        $this->actingAs($intruder)
            ->put("/recruiter/applications/{$application->id}/reject")
            ->assertNotFound();

        $this->assertSame('pending', $application->fresh()->status);
    }

    public function test_the_hiring_recruiter_can_accept_an_application(): void
    {
        $recruiter = $this->user('recruiter', 'proprietaire@example.com');
        $job = $this->job($recruiter);

        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => $this->user('candidate', 'candidat@example.com')->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        $this->actingAs($recruiter)
            ->put("/recruiter/applications/{$application->id}/accept")
            ->assertRedirect(route('recruiter.dashboard'));

        $this->assertSame('accepted', $application->fresh()->status);
    }

    public function test_only_the_recipient_can_mark_a_message_as_read(): void
    {
        $sender = $this->user('recruiter', 'expediteur@example.com');
        $recipient = $this->user('candidate', 'destinataire@example.com');
        $stranger = $this->user('candidate', 'tiers@example.com');

        $message = Message::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'subject' => 'Sujet',
            'message' => 'Contenu',
            'type' => 'contact',
            'is_read' => false,
        ]);

        $this->actingAs($stranger)
            ->post("/messages/{$message->id}/read")
            ->assertForbidden();

        $this->assertFalse((bool) $message->fresh()->is_read);

        $this->actingAs($recipient)
            ->post("/messages/{$message->id}/read")
            ->assertRedirect();

        $this->assertTrue((bool) $message->fresh()->is_read);
    }
}
