<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_responses_carry_the_baseline_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy');
    }

    public function test_hsts_is_not_advertised_over_plain_http(): void
    {
        // Envoyer HSTS sur du HTTP en clair n'apporte rien et casse les
        // environnements de développement servis sans TLS.
        $this->get('/')->assertHeaderMissing('Strict-Transport-Security');
    }

    public function test_hsts_is_advertised_over_https(): void
    {
        $response = $this->get('https://localhost/');

        $response->assertHeader('Strict-Transport-Security');
    }

    public function test_a_content_security_policy_is_sent(): void
    {
        $this->get('/')->assertHeader('Content-Security-Policy');
    }

    public function test_inline_scripts_are_not_allowed(): void
    {
        // Tout le JavaScript est passe dans le bundle Vite : plus aucun bloc
        // <script> ni attribut onclick dans les vues. C'est ce qui permet a
        // script-src de rester strict, et donc a la CSP de servir a quelque
        // chose contre le XSS.
        $csp = $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("script-src 'self'", $csp);
        $this->assertStringNotContainsString("script-src 'self' 'unsafe-inline'", $csp);
    }

    public function test_the_page_cannot_be_framed_or_rebased(): void
    {
        $csp = $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("frame-ancestors 'none'", $csp);
        $this->assertStringContainsString("base-uri 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
    }

    public function test_no_external_origin_is_allowed_for_scripts_or_styles(): void
    {
        $csp = $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertStringNotContainsString('cdn.jsdelivr.net', $csp);
        $this->assertStringNotContainsString('fonts.googleapis.com', $csp);
    }
}
