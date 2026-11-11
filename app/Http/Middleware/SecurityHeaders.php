<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * En-têtes de sécurité appliqués à toutes les réponses.
 *
 * Pas de Content-Security-Policy ici : le layout charge encore Bootstrap et
 * Google Fonts depuis des CDN et embarque du CSS inline. Une CSP stricte
 * casserait le site, et une CSP permissive (`unsafe-inline`) ne protégerait de
 * rien. Elle sera ajoutée une fois les assets rapatriés dans le build Vite.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), interest-cohort=()'
        );

        // HSTS n'a de sens que sur une connexion déjà chiffrée ; l'envoyer en
        // HTTP simple ne protège pas et casse les environnements sans TLS.
        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
