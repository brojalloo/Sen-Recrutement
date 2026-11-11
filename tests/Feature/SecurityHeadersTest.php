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
}
