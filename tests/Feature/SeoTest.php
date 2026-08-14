<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Référencement.
 *
 * Pour un site d'emploi, les données structurées `JobPosting` sont ce qui fait
 * apparaître les offres dans Google for Jobs. Sans elles, les offres
 * n'existent pas pour le principal canal d'acquisition du secteur.
 *
 * Le sitemap ne doit exposer que les offres publiquement visibles : y lister
 * une offre en attente ou rejetée reviendrait à la divulguer, et à envoyer les
 * robots sur une 404.
 */
class SeoTest extends TestCase
{
    use RefreshDatabase;

    private ?User $recruiter = null;

    private function recruiter(): User
    {
        // Réutilisé : plusieurs offres dans un même test, un seul recruteur,
        // sinon l'email unique fait échouer la seconde insertion.
        return $this->recruiter ??= User::create([
            'name' => 'recruteur@example.com',
            'email' => 'recruteur@example.com',
            'password' => 'password123',
            'role' => 'recruiter',
            'status' => 'active',
            'company_name' => 'TechCorp',
        ]);
    }

    private function job(string $approval = 'approved', array $attributes = []): Job
    {
        return Job::create(array_merge([
            'recruiter_id' => $this->recruiter()->id,
            'title' => 'Développeur Full Stack',
            'description' => 'Nous recherchons un développeur expérimenté en Laravel.',
            'company' => 'TechCorp',
            'location' => 'Dakar, Sénégal',
            'type' => 'CDI',
            'status' => 'active',
            'approval_status' => $approval,
            'is_active' => true,
            'posted_at' => now(),
        ], $attributes));
    }

    /**
     * @return array<string, mixed>
     */
    private function structuredData(string $html): array
    {
        $matched = preg_match(
            '#<script type="application/ld\+json">(.*?)</script>#s',
            $html,
            $matches
        );

        $this->assertSame(1, $matched, 'Aucun bloc JSON-LD dans la page.');

        $decoded = json_decode(trim($matches[1]), true);

        $this->assertIsArray($decoded, 'Le JSON-LD produit est invalide : '.json_last_error_msg());

        return $decoded;
    }

    public function test_a_job_page_publishes_job_posting_structured_data(): void
    {
        $job = $this->job();

        $data = $this->structuredData($this->get("/jobs/{$job->id}")->getContent());

        $this->assertSame('https://schema.org', $data['@context']);
        $this->assertSame('JobPosting', $data['@type']);
        $this->assertSame('Développeur Full Stack', $data['title']);
        $this->assertSame('TechCorp', $data['hiringOrganization']['name']);
        $this->assertSame('Dakar, Sénégal', $data['jobLocation']['address']['addressLocality']);
        $this->assertSame('SN', $data['jobLocation']['address']['addressCountry']);
        $this->assertArrayHasKey('datePosted', $data);
        $this->assertSame('FULL_TIME', $data['employmentType']);
    }

    public function test_the_salary_range_is_published_when_it_is_known(): void
    {
        $job = $this->job('approved', ['salary_min' => 400000, 'salary_max' => 800000]);

        $data = $this->structuredData($this->get("/jobs/{$job->id}")->getContent());

        // Comparaison souple : JSON n'a qu'un type numérique, 400000.0 est
        // réencodé en 400000. Ce qui compte est la valeur, pas le type PHP.
        $this->assertSame('XOF', $data['baseSalary']['currency']);
        $this->assertEquals(400000, $data['baseSalary']['value']['minValue']);
        $this->assertEquals(800000, $data['baseSalary']['value']['maxValue']);
        $this->assertSame('MONTH', $data['baseSalary']['value']['unitText']);
    }

    public function test_no_salary_is_invented_when_none_was_given(): void
    {
        $job = $this->job();

        $data = $this->structuredData($this->get("/jobs/{$job->id}")->getContent());

        $this->assertArrayNotHasKey('baseSalary', $data);
    }

    public function test_a_job_page_carries_a_meta_description(): void
    {
        $job = $this->job();

        $this->get("/jobs/{$job->id}")
            ->assertSee('<meta name="description"', false);
    }

    public function test_the_home_page_carries_description_and_open_graph_tags(): void
    {
        $response = $this->get('/');

        $response->assertSee('<meta name="description"', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:description"', false);
        $response->assertSee('property="og:type"', false);
    }

    public function test_the_sitemap_lists_visible_jobs(): void
    {
        $job = $this->job();

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee(route('jobs.show', $job->id), false);
    }

    public function test_the_sitemap_hides_jobs_that_are_not_approved(): void
    {
        $pending = $this->job('pending');
        $rejected = $this->job('rejected');

        $response = $this->get('/sitemap.xml');

        $response->assertDontSee(route('jobs.show', $pending->id), false);
        $response->assertDontSee(route('jobs.show', $rejected->id), false);
    }

    public function test_the_sitemap_lists_the_public_pages(): void
    {
        $response = $this->get('/sitemap.xml');

        // Sans ce assertOk, le test passait sur la page 404, qui contient
        // elle aussi un lien vers l'accueil.
        $response->assertOk();
        $response->assertSee(route('home'), false);
        $response->assertSee(route('jobs.index'), false);
        $response->assertSee(route('contact.index'), false);
    }

    public function test_robots_points_crawlers_at_the_sitemap(): void
    {
        $this->get('/robots.txt')->assertSee('Sitemap:');
    }

    public function test_private_areas_are_not_offered_to_crawlers(): void
    {
        $robots = $this->get('/robots.txt')->getContent();

        $this->assertStringContainsString('Disallow: /admin', $robots);
        $this->assertStringContainsString('Disallow: /candidate', $robots);
        $this->assertStringContainsString('Disallow: /recruiter', $robots);
        $this->assertStringContainsString('Disallow: /messages', $robots);
    }
}
