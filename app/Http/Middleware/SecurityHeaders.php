<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * En-têtes de sécurité appliqués à toutes les réponses.
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

        $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy());

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

    /**
     * Tous les scripts et toutes les feuilles viennent maintenant du build
     * Vite, servi depuis la même origine : `script-src` peut donc rester
     * strict, ce qui est l'essentiel — c'est cette directive qui arrête un
     * XSS.
     *
     * `style-src` doit encore tolérer 'unsafe-inline' : les vues portent 59
     * attributs `style="..."` (dégradés, hauteurs). Les retirer demanderait de
     * toucher 18 fichiers sans filet de test visuel, pour un gain bien moindre
     * que celui de `script-src`. C'est un compromis assumé, pas un oubli.
     */
    private function contentSecurityPolicy(): string
    {
        $directives = [
            "default-src 'self'",
            'script-src '.implode(' ', $this->scriptSources()),
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data:",
            "font-src 'self'",
            'connect-src '.implode(' ', $this->connectSources()),
            "form-action 'self'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "object-src 'none'",
        ];

        return implode('; ', $directives);
    }

    /**
     * @return list<string>
     */
    private function scriptSources(): array
    {
        $sources = ["'self'"];

        if ($devServer = $this->viteDevServer()) {
            $sources[] = $devServer;
        }

        return $sources;
    }

    /**
     * @return list<string>
     */
    private function connectSources(): array
    {
        $sources = ["'self'"];

        if ($devServer = $this->viteDevServer()) {
            // Le rechargement à chaud de Vite passe par une WebSocket.
            $sources[] = $devServer;
            $sources[] = str_replace('http', 'ws', $devServer);
        }

        return $sources;
    }

    /**
     * L'origine du serveur de développement Vite, quand il tourne.
     *
     * En développement, `@vite` pointe les assets vers ce serveur : sans cette
     * exception la CSP bloquerait le rechargement à chaud. Le fichier `hot`
     * n'existe pas en production, la politique y reste donc stricte.
     */
    private function viteDevServer(): ?string
    {
        $hotFile = public_path('hot');

        if (! is_file($hotFile)) {
            return null;
        }

        $origin = trim((string) file_get_contents($hotFile));

        return $origin !== '' ? $origin : null;
    }
}
