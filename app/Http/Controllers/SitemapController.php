<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Le sitemap s'appuie sur le scope `visible()`, la même source de vérité
     * que les pages publiques : une offre en attente ou rejetée n'y apparaît
     * donc pas. La lister reviendrait à divulguer une offre non publiée et à
     * envoyer les robots sur une 404.
     */
    public function index(): Response
    {
        $jobs = Job::visible()
            ->select('id', 'updated_at')
            ->orderByDesc('updated_at')
            ->get();

        return response()
            ->view('sitemap', compact('jobs'))
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            // Ces espaces exigent une authentification : les proposer aux
            // robots ne produit que des redirections vers la page de login.
            'Disallow: /admin',
            'Disallow: /candidate',
            'Disallow: /recruiter',
            'Disallow: /messages',
            'Disallow: /cv',
            'Disallow: /download',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ];

        return response(implode("\n", $lines))
            ->header('Content-Type', 'text/plain');
    }
}
