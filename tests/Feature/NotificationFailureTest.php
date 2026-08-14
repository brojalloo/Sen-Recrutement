<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Support\Facades\Log;
use Tests\Support\FailingMailChannel;
use Tests\TestCase;

/**
 * Quand l'envoi d'email échoue.
 *
 * Les trois appels à `notify()` étaient enveloppés dans un `catch` vide
 * commenté « Ignorer les erreurs d'envoi d'email ». Un SMTP en panne passait
 * donc totalement inaperçu : le recruteur n'était jamais prévenu d'une
 * candidature, l'interface affichait quand même « un email a été envoyé », et
 * rien n'était consigné nulle part.
 *
 * L'action métier doit continuer d'aboutir — perdre une candidature parce que
 * le serveur mail tousse serait pire. Mais l'échec doit être tracé, et
 * l'interface doit cesser d'affirmer le contraire.
 */
class NotificationFailureTest extends TestCase
{
    use RefreshDatabase;

    private function failTheMailChannel(): void
    {
        $this->app->instance(MailChannel::class, new FailingMailChannel);
    }

    /**
     * Capture les entrées de journal émises pendant le callback.
     *
     * @return list<MessageLogged>
     */
    private function captureLogs(callable $callback): array
    {
        $entries = [];
        Log::listen(function (MessageLogged $entry) use (&$entries) {
            $entries[] = $entry;
        });

        $callback();

        return $entries;
    }

    /**
     * @param  list<MessageLogged>  $entries
     */
    private function assertLoggedAnError(array $entries): void
    {
        $errors = array_filter($entries, fn (MessageLogged $entry) => $entry->level === 'error');

        $this->assertNotEmpty($errors, "Aucune erreur n'a été consignée alors que l'envoi a échoué.");
    }

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

    private function application(User $candidate, User $recruiter): Application
    {
        return Application::create([
            'job_id' => $this->job($recruiter)->id,
            'user_id' => $candidate->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);
    }

    public function test_an_application_is_still_recorded_when_the_email_fails(): void
    {
        $this->failTheMailChannel();

        $candidate = $this->user('candidate', 'candidat@example.com');
        $job = $this->job($this->user('recruiter', 'recruteur@example.com'));

        $this->actingAs($candidate)
            ->post("/jobs/{$job->id}/apply", ['message' => 'Bonjour'])
            ->assertRedirect(route('candidate.dashboard'));

        $this->assertSame(1, Application::count());
    }

    public function test_a_failed_application_email_is_logged(): void
    {
        $this->failTheMailChannel();

        $candidate = $this->user('candidate', 'candidat@example.com');
        $job = $this->job($this->user('recruiter', 'recruteur@example.com'));

        $entries = $this->captureLogs(function () use ($candidate, $job) {
            $this->actingAs($candidate)->post("/jobs/{$job->id}/apply", ['message' => 'Bonjour']);
        });

        $this->assertLoggedAnError($entries);
    }

    public function test_the_candidate_is_not_told_an_email_was_sent_when_it_failed(): void
    {
        $this->failTheMailChannel();

        $recruiter = $this->user('recruiter', 'recruteur@example.com');
        $application = $this->application($this->user('candidate', 'candidat@example.com'), $recruiter);

        $this->actingAs($recruiter)
            ->put("/recruiter/applications/{$application->id}/accept")
            ->assertSessionHas('status', fn (string $status) => ! str_contains($status, 'email a été envoyé'));

        $this->assertSame('accepted', $application->fresh()->status);
    }

    public function test_the_status_change_is_still_applied_when_the_email_fails(): void
    {
        $this->failTheMailChannel();

        $recruiter = $this->user('recruiter', 'recruteur@example.com');
        $application = $this->application($this->user('candidate', 'candidat@example.com'), $recruiter);

        $this->actingAs($recruiter)
            ->put("/recruiter/applications/{$application->id}/reject")
            ->assertRedirect(route('recruiter.dashboard'));

        $this->assertSame('rejected', $application->fresh()->status);
    }

    public function test_a_failed_status_email_is_logged(): void
    {
        $this->failTheMailChannel();

        $recruiter = $this->user('recruiter', 'recruteur@example.com');
        $application = $this->application($this->user('candidate', 'candidat@example.com'), $recruiter);

        $entries = $this->captureLogs(function () use ($recruiter, $application) {
            $this->actingAs($recruiter)->put("/recruiter/applications/{$application->id}/accept");
        });

        $this->assertLoggedAnError($entries);
    }

    public function test_the_success_message_still_mentions_the_email_when_it_goes_through(): void
    {
        $recruiter = $this->user('recruiter', 'recruteur@example.com');
        $application = $this->application($this->user('candidate', 'candidat@example.com'), $recruiter);

        $this->actingAs($recruiter)
            ->put("/recruiter/applications/{$application->id}/accept")
            ->assertSessionHas('status', fn (string $status) => str_contains($status, 'email a été envoyé'));
    }
}
