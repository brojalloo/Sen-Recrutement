<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Les CV sont des données personnelles. Le disque `public` est exposé sans
 * authentification via le lien symbolique `public/storage` : y déposer un CV
 * rend l'URL téléchargeable par n'importe qui et court-circuite les contrôles
 * d'accès des routes de téléchargement.
 */
class CvStoragePrivacyTest extends TestCase
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

    public function test_a_cv_sent_with_an_application_is_stored_out_of_public_reach(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $candidate = $this->user('candidate', 'candidat@example.com');
        $job = $this->job($this->user('recruiter', 'recruteur@example.com'));

        $this->actingAs($candidate)->post("/jobs/{$job->id}/apply", [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $path = Application::firstOrFail()->cv_path;

        $this->assertNotNull($path);
        Storage::disk('local')->assertExists($path);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_a_cv_uploaded_from_the_profile_is_stored_out_of_public_reach(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $candidate = $this->user('candidate', 'candidat@example.com');

        $this->actingAs($candidate)->put('/candidate/profile', [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $path = $candidate->fresh()->cv_path;

        $this->assertNotNull($path);
        Storage::disk('local')->assertExists($path);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_deleting_a_cv_removes_it_from_private_storage(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $candidate = $this->user('candidate', 'candidat@example.com');

        $this->actingAs($candidate)->put('/candidate/profile', [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $path = $candidate->fresh()->cv_path;

        $this->actingAs($candidate)->delete('/candidate/profile/cv');

        Storage::disk('local')->assertMissing($path);
        $this->assertNull($candidate->fresh()->cv_path);
    }

    public function test_the_applicant_can_download_their_own_application_cv(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $candidate = $this->user('candidate', 'candidat@example.com');
        $job = $this->job($this->user('recruiter', 'recruteur@example.com'));

        $this->actingAs($candidate)->post("/jobs/{$job->id}/apply", [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $application = Application::firstOrFail();

        $this->actingAs($candidate)
            ->get("/cv/{$application->id}")
            ->assertOk();
    }

    public function test_the_hiring_recruiter_can_download_the_application_cv(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $candidate = $this->user('candidate', 'candidat@example.com');
        $recruiter = $this->user('recruiter', 'recruteur@example.com');
        $job = $this->job($recruiter);

        $this->actingAs($candidate)->post("/jobs/{$job->id}/apply", [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $application = Application::firstOrFail();

        $this->actingAs($recruiter)
            ->get("/cv/{$application->id}")
            ->assertOk();
    }

    public function test_an_unrelated_recruiter_cannot_download_the_application_cv(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $candidate = $this->user('candidate', 'candidat@example.com');
        $job = $this->job($this->user('recruiter', 'recruteur@example.com'));
        $stranger = $this->user('recruiter', 'inconnu@example.com');

        $this->actingAs($candidate)->post("/jobs/{$job->id}/apply", [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $application = Application::firstOrFail();

        $this->actingAs($stranger)
            ->get("/cv/{$application->id}")
            ->assertForbidden();
    }

    public function test_a_guest_cannot_download_an_application_cv(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $candidate = $this->user('candidate', 'candidat@example.com');
        $job = $this->job($this->user('recruiter', 'recruteur@example.com'));

        $this->actingAs($candidate)->post("/jobs/{$job->id}/apply", [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $application = Application::firstOrFail();

        $this->post('/logout');

        $this->get("/cv/{$application->id}")->assertRedirect('/login');
    }

    public function test_the_candidate_profile_never_exposes_a_public_cv_url(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $candidate = $this->user('candidate', 'candidat@example.com');

        $this->actingAs($candidate)->put('/candidate/profile', [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $response = $this->actingAs($candidate)->get('/candidate/profile');

        $response->assertOk();
        $response->assertDontSee('/storage/cvs', false);
    }

    /**
     * Le disque `local` est déclaré `serve => true`, ce qui fait enregistrer à
     * Laravel une route `GET /storage/{path}`. Elle n'est sûre que tant que le
     * disque reste en visibilité privée : y mettre `visibility => public`
     * rouvrirait la fuite en grand sans toucher une ligne de contrôleur.
     */
    public function test_the_private_disk_route_refuses_an_unsigned_url(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        Storage::disk('local')->put('cvs/confidentiel.pdf', 'données personnelles');

        $response = $this->get('/storage/cvs/confidentiel.pdf');

        $this->assertContains($response->status(), [403, 404]);
        $response->assertDontSee('données personnelles');
    }

    public function test_the_profile_cv_download_is_refused_to_an_unrelated_recruiter(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $candidate = $this->user('candidate', 'candidat@example.com');
        $stranger = $this->user('recruiter', 'inconnu@example.com');

        $this->actingAs($candidate)->put('/candidate/profile', [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $this->actingAs($stranger)
            ->get("/download/cv/{$candidate->id}")
            ->assertForbidden();
    }

    public function test_the_profile_cv_download_works_for_the_owner(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $candidate = $this->user('candidate', 'candidat@example.com');

        $this->actingAs($candidate)->put('/candidate/profile', [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $this->actingAs($candidate)
            ->get("/download/cv/{$candidate->id}")
            ->assertOk();
    }
}
