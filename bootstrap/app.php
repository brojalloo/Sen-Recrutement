<?php

use App\Http\Middleware\EnsureActiveUser;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware
        $middleware->append(SecurityHeaders::class);

        // Vérifier le statut à chaque requête web, même pour les sessions existantes
        // et les routes partagées (messagerie, téléchargements, etc.).
        $middleware->appendToGroup('web', EnsureActiveUser::class);

        // Route middleware aliases
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
