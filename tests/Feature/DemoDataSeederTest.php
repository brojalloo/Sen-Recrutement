<?php

namespace Tests\Feature;

use App\Models\Job;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Le jeu de démonstration ne renseignait jamais `approval_status`. Toutes les
 * offres restaient donc en attente, et une installation de démonstration
 * s'ouvrait sur une page d'accueil et une liste d'offres vides — la pire
 * première impression possible pour qui découvre le projet.
 */
class DemoDataSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_demo_shows_jobs_on_the_public_pages(): void
    {
        $this->seed(DemoDataSeeder::class);

        $this->assertGreaterThan(0, Job::visible()->count(), 'La démonstration ne publie aucune offre visible.');

        $this->get('/')->assertOk()->assertSee('Développeur Full Stack Laravel');
        $this->get('/jobs')->assertOk()->assertSee('Développeur Full Stack Laravel');
    }

    public function test_the_demo_leaves_something_to_moderate(): void
    {
        $this->seed(DemoDataSeeder::class);

        // Sans offre en attente, le tableau de bord admin affiche une file de
        // modération vide : la fonctionnalité principale de l'admin n'est pas
        // démontrable.
        $this->assertGreaterThan(
            0,
            Job::where('approval_status', 'pending')->count(),
            'La démonstration ne laisse aucune offre à modérer.'
        );
    }
}
