<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

/**
 * Déplace les CV déjà déposés du disque `public` (exposé sans authentification
 * via le lien symbolique `public/storage`) vers le disque privé.
 *
 * Les chemins stockés en base ne changent pas : seul le disque qui les sert
 * change. Les avatars et logos d'entreprise restent publics, c'est leur rôle.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (Storage::disk('public')->files('cvs') as $path) {
            // Un CV déjà présent côté privé fait autorité : la version publique
            // est une copie obsolète, on la supprime sans l'écraser.
            if (! Storage::disk('local')->exists($path)) {
                Storage::disk('local')->put($path, Storage::disk('public')->get($path));
            }

            Storage::disk('public')->delete($path);
        }
    }

    public function down(): void
    {
        // Pas de retour en arrière : remettre des CV sur le disque public
        // recréerait la fuite de données que cette migration corrige.
    }
};
