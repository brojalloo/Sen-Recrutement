<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Les CV déjà déposés avant le passage au disque privé restent téléchargeables
 * publiquement tant qu'ils n'ont pas été déplacés. La migration fait ce
 * rattrapage au déploiement.
 */
class MoveCvsToPrivateDiskTest extends TestCase
{
    private function migration(): object
    {
        return require database_path('migrations/2026_08_13_140000_move_cvs_to_private_disk.php');
    }

    public function test_it_moves_existing_cvs_off_the_public_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        Storage::disk('public')->put('cvs/ancien.pdf', 'contenu du cv');

        $this->migration()->up();

        Storage::disk('local')->assertExists('cvs/ancien.pdf');
        Storage::disk('public')->assertMissing('cvs/ancien.pdf');
        $this->assertSame('contenu du cv', Storage::disk('local')->get('cvs/ancien.pdf'));
    }

    public function test_it_leaves_other_public_files_alone(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        Storage::disk('public')->put('avatars/photo.png', 'image');
        Storage::disk('public')->put('logos/entreprise.png', 'image');

        $this->migration()->up();

        Storage::disk('public')->assertExists('avatars/photo.png');
        Storage::disk('public')->assertExists('logos/entreprise.png');
    }

    public function test_it_runs_without_any_cv_to_move(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $this->migration()->up();

        $this->assertSame([], Storage::disk('local')->files('cvs'));
    }

    public function test_it_does_not_overwrite_a_cv_already_moved(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        Storage::disk('local')->put('cvs/doublon.pdf', 'version privée');
        Storage::disk('public')->put('cvs/doublon.pdf', 'version publique obsolète');

        $this->migration()->up();

        $this->assertSame('version privée', Storage::disk('local')->get('cvs/doublon.pdf'));
        Storage::disk('public')->assertMissing('cvs/doublon.pdf');
    }
}
