<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Accessibilité des pages publiques.
 *
 * Constats de l'audit navigateur : aucun lien d'évitement, le bouton
 * hamburger et le bouton œil des mots de passe n'ont aucun nom accessible, et
 * les champs de recherche et de contact ne sont associés à aucune étiquette —
 * un lecteur d'écran n'annonce donc rien d'exploitable. La page de connexion
 * n'a pas de h1 et commence par un h3.
 */
class AccessibilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie que chaque champ est relié à une étiquette par `for`/`id`.
     *
     * @param  list<string>  $names
     */
    private function assertFieldsAreLabelled(string $html, array $names, string $page): void
    {
        foreach ($names as $name) {
            $matched = preg_match(
                '/<(?:input|select|textarea)[^>]*name="'.preg_quote($name, '/').'"[^>]*>/i',
                $html,
                $matches
            );

            $this->assertSame(1, $matched, "Champ « {$name} » introuvable sur {$page}.");

            $hasId = preg_match('/\bid="([^"]+)"/', $matches[0], $idMatch);
            $this->assertSame(1, $hasId, "Le champ « {$name} » de {$page} n'a pas d'id, il ne peut donc pas être étiqueté.");

            $this->assertMatchesRegularExpression(
                '/<label[^>]*for="'.preg_quote($idMatch[1], '/').'"/',
                $html,
                "Aucune étiquette ne pointe vers le champ « {$name} » de {$page}."
            );
        }
    }

    public function test_every_page_offers_a_skip_link_to_the_main_content(): void
    {
        $response = $this->get('/');

        $response->assertSee('href="#contenu-principal"', false);
        $response->assertSee('id="contenu-principal"', false);
    }

    public function test_the_menu_button_has_an_accessible_name(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertMatchesRegularExpression(
            '/<button[^>]*navbar-toggler[^>]*aria-label="[^"]+"/',
            $html,
            'Le bouton du menu mobile est une icône seule, sans nom accessible.'
        );
    }

    public function test_the_menu_button_declares_what_it_controls(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertMatchesRegularExpression('/<button[^>]*navbar-toggler[^>]*aria-controls="mainNav"/', $html);
        $this->assertMatchesRegularExpression('/<button[^>]*navbar-toggler[^>]*aria-expanded="false"/', $html);
    }

    public function test_the_password_reveal_button_has_an_accessible_name(): void
    {
        $html = $this->get('/login')->getContent();

        $this->assertMatchesRegularExpression(
            '/<button[^>]*data-toggle-password[^>]*aria-label="[^"]+"/',
            $html,
            "Le bouton œil n'annonce rien à un lecteur d'écran."
        );
    }

    public function test_the_login_page_starts_at_heading_level_one(): void
    {
        $html = $this->get('/login')->getContent();

        $this->assertSame(1, preg_match_all('/<h1[\s>]/', $html), 'La page de connexion doit avoir exactement un h1.');
    }

    public function test_the_home_search_fields_are_labelled(): void
    {
        $this->assertFieldsAreLabelled(
            $this->get('/')->getContent(),
            ['keyword', 'location'],
            "la page d'accueil"
        );
    }

    public function test_the_job_filter_fields_are_labelled(): void
    {
        $this->assertFieldsAreLabelled(
            $this->get('/jobs')->getContent(),
            ['keyword', 'location', 'type'],
            'la page des offres'
        );
    }

    public function test_the_contact_fields_are_labelled(): void
    {
        $this->assertFieldsAreLabelled(
            $this->get('/contact')->getContent(),
            ['name', 'email', 'subject', 'message'],
            'la page de contact'
        );
    }

    public function test_the_theme_toggle_has_an_accessible_name(): void
    {
        $user = User::create([
            'name' => 'candidat@example.com',
            'email' => 'candidat@example.com',
            'password' => 'password123',
            'role' => 'candidate',
            'status' => 'active',
        ]);

        $html = $this->actingAs($user)->get('/')->getContent();

        $this->assertMatchesRegularExpression('/<button[^>]*id="themeToggle"[^>]*aria-label="[^"]+"/', $html);
    }
}
