<?php

namespace Tests\Feature;

use App\Models\AdminLog;
use App\Models\Application;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Détection des N+1.
 *
 * Plutôt que de figer un nombre exact de requêtes — fragile, il change au
 * moindre ajout de colonne — on vérifie la vraie définition d'un N+1 : le
 * nombre de requêtes ne doit pas augmenter quand le nombre de lignes
 * affichées augmente.
 */
class QueryCountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Compte les requêtes SQL émises pendant l'exécution du callback.
     */
    private function countQueries(callable $callback): int
    {
        $count = 0;
        DB::listen(function () use (&$count) {
            $count++;
        });

        $callback();

        DB::flushQueryLog();

        return $count;
    }

    /**
     * Exécute le scénario avec peu puis beaucoup de lignes et exige que le
     * nombre de requêtes soit identique.
     *
     * @param  callable(int): void  $seed  crée N lignes
     * @param  callable(): void  $visit  consulte la page
     */
    private function assertQueryCountDoesNotGrow(callable $seed, callable $visit, string $page): void
    {
        $seed(2);
        $few = $this->countQueries($visit);

        $seed(10);
        $many = $this->countQueries($visit);

        $this->assertSame(
            $few,
            $many,
            "N+1 sur {$page} : {$few} requêtes pour 2 lignes, {$many} pour 12. ".
            'Le nombre de requêtes doit être indépendant du nombre de lignes.'
        );
    }

    private function user(string $role, ?string $email = null): User
    {
        $email ??= $role.'-'.uniqid().'@example.com';

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

    public function test_the_inbox_does_not_query_per_message(): void
    {
        $recipient = $this->user('candidate', 'destinataire@example.com');

        $this->assertQueryCountDoesNotGrow(
            seed: function (int $n) use ($recipient) {
                for ($i = 0; $i < $n; $i++) {
                    Message::create([
                        'sender_id' => $this->user('recruiter')->id,
                        'recipient_id' => $recipient->id,
                        'subject' => 'Sujet',
                        'message' => 'Contenu',
                        'type' => 'contact',
                        'is_read' => false,
                    ]);
                }
            },
            visit: fn () => $this->actingAs($recipient)->get('/messages')->assertOk(),
            page: 'la boîte de réception',
        );
    }

    public function test_the_outbox_does_not_query_per_message(): void
    {
        $sender = $this->user('candidate', 'expediteur@example.com');

        $this->assertQueryCountDoesNotGrow(
            seed: function (int $n) use ($sender) {
                for ($i = 0; $i < $n; $i++) {
                    Message::create([
                        'sender_id' => $sender->id,
                        'recipient_id' => $this->user('recruiter')->id,
                        'subject' => 'Sujet',
                        'message' => 'Contenu',
                        'type' => 'contact',
                        'is_read' => false,
                    ]);
                }
            },
            visit: fn () => $this->actingAs($sender)->get('/messages/outbox')->assertOk(),
            page: "la boîte d'envoi",
        );
    }

    public function test_the_candidate_dashboard_does_not_query_per_application(): void
    {
        $candidate = $this->user('candidate', 'candidat@example.com');
        $recruiter = $this->user('recruiter', 'recruteur@example.com');

        $this->assertQueryCountDoesNotGrow(
            seed: function (int $n) use ($candidate, $recruiter) {
                for ($i = 0; $i < $n; $i++) {
                    Application::create([
                        'job_id' => $this->job($recruiter)->id,
                        'user_id' => $candidate->id,
                        'status' => 'pending',
                        'applied_at' => now(),
                    ]);
                }
            },
            visit: fn () => $this->actingAs($candidate)->get('/candidate/dashboard')->assertOk(),
            page: 'le tableau de bord candidat',
        );
    }

    public function test_the_recruiter_dashboard_does_not_query_per_application(): void
    {
        $recruiter = $this->user('recruiter', 'recruteur@example.com');

        $this->assertQueryCountDoesNotGrow(
            seed: function (int $n) use ($recruiter) {
                for ($i = 0; $i < $n; $i++) {
                    Application::create([
                        'job_id' => $this->job($recruiter)->id,
                        'user_id' => $this->user('candidate')->id,
                        'status' => 'pending',
                        'applied_at' => now(),
                    ]);
                }
            },
            visit: fn () => $this->actingAs($recruiter)->get('/recruiter/dashboard')->assertOk(),
            page: 'le tableau de bord recruteur',
        );
    }

    public function test_the_admin_log_page_does_not_query_per_entry(): void
    {
        $admin = $this->user('admin', 'admin@example.com');

        $this->assertQueryCountDoesNotGrow(
            seed: function (int $n) {
                for ($i = 0; $i < $n; $i++) {
                    AdminLog::create([
                        'user_id' => $this->user('admin')->id,
                        'action' => 'approve_job',
                        'description' => 'Offre approuvée',
                        'ip_address' => '127.0.0.1',
                    ]);
                }
            },
            visit: fn () => $this->actingAs($admin)->get('/admin/logs')->assertOk(),
            page: 'le journal admin',
        );
    }

    public function test_the_admin_dashboard_does_not_query_per_pending_job(): void
    {
        $admin = $this->user('admin', 'admin@example.com');

        $this->assertQueryCountDoesNotGrow(
            seed: function (int $n) {
                for ($i = 0; $i < $n; $i++) {
                    Job::create([
                        'recruiter_id' => $this->user('recruiter')->id,
                        'title' => 'Développeur',
                        'description' => 'Poste',
                        'company' => 'TechCorp',
                        'status' => 'active',
                        'approval_status' => 'pending',
                        'is_active' => true,
                    ]);
                }
            },
            visit: fn () => $this->actingAs($admin)->get('/admin/dashboard')->assertOk(),
            page: 'le tableau de bord admin',
        );
    }

    public function test_the_recruiter_profile_does_not_load_every_job_to_count_them(): void
    {
        $recruiter = $this->user('recruiter', 'recruteur@example.com');

        $this->assertQueryCountDoesNotGrow(
            seed: function (int $n) use ($recruiter) {
                for ($i = 0; $i < $n; $i++) {
                    $job = $this->job($recruiter);
                    Application::create([
                        'job_id' => $job->id,
                        'user_id' => $this->user('candidate')->id,
                        'status' => 'pending',
                        'applied_at' => now(),
                    ]);
                }
            },
            visit: fn () => $this->actingAs($recruiter)->get('/recruiter/profile')->assertOk(),
            page: 'le profil recruteur',
        );
    }
}
