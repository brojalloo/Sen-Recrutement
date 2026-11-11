<?php

namespace Tests\Feature;

use App\Models\AdminLog;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuditLogTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    private function job(): Job
    {
        $recruiter = User::create([
            'name' => 'Recruteur',
            'email' => 'recruteur@example.com',
            'password' => 'password123',
            'role' => 'recruiter',
            'status' => 'active',
        ]);

        return Job::create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Développeur',
            'description' => 'Description',
            'company' => 'TechCorp',
            'status' => 'active',
            'approval_status' => 'pending',
            'is_active' => true,
        ]);
    }

    public function test_approving_a_job_records_which_admin_did_it(): void
    {
        $job = $this->job();

        $this->actingAs($this->admin)->put("/admin/jobs/{$job->id}/approve");

        $log = AdminLog::latest('id')->firstOrFail();

        $this->assertSame($this->admin->id, $log->user_id);
    }

    public function test_rejecting_a_job_records_which_admin_did_it(): void
    {
        $job = $this->job();

        $this->actingAs($this->admin)->put("/admin/jobs/{$job->id}/reject");

        $this->assertSame($this->admin->id, AdminLog::latest('id')->firstOrFail()->user_id);
    }

    public function test_toggling_a_user_status_records_which_admin_did_it(): void
    {
        $target = User::create([
            'name' => 'Cible',
            'email' => 'cible@example.com',
            'password' => 'password123',
            'role' => 'candidate',
            'status' => 'active',
        ]);

        $this->actingAs($this->admin)->put("/admin/users/{$target->id}/toggle-status");

        $this->assertSame($this->admin->id, AdminLog::latest('id')->firstOrFail()->user_id);
    }

    public function test_the_log_export_contains_the_admin_id(): void
    {
        $job = $this->job();

        $this->actingAs($this->admin)->put("/admin/jobs/{$job->id}/approve");

        $response = $this->actingAs($this->admin)->get('/admin/logs/export');

        $response->assertOk();

        $csv = $response->streamedContent();

        $this->assertStringContainsString('approve_job', $csv);
        $this->assertMatchesRegularExpression(
            '/^\d+,'.$this->admin->id.',approve_job/m',
            $csv
        );
    }

    public function test_the_log_export_names_the_admin_and_keeps_the_description(): void
    {
        $job = $this->job();

        $this->actingAs($this->admin)->put("/admin/jobs/{$job->id}/approve");

        $csv = $this->actingAs($this->admin)->get('/admin/logs/export')->streamedContent();

        // Un identifiant numérique n'apprend rien à qui relit un journal
        // d'audit six mois plus tard. Les colonnes sont ajoutées en fin de
        // ligne : les quatre premières gardent leur position, pour ne rien
        // casser en aval.
        $this->assertStringContainsString($this->admin->email, $csv);
        $this->assertStringContainsString('Offre approuvée', $csv);
        // PHP entoure de guillemets les champs contenant une espace.
        $this->assertStringContainsString('ID,"Admin ID",Action,"Created At",Admin,Description,IP', $csv);
    }
}
