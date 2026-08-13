<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rien n'empêchait jusqu'ici un candidat de postuler plusieurs fois à la
        // même offre. Les doublons déjà en base feraient échouer la création de
        // l'index : on ne garde que la première candidature de chaque paire.
        $duplicates = DB::table('applications')
            ->select('job_id', 'user_id')
            ->groupBy('job_id', 'user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $keep = DB::table('applications')
                ->where('job_id', $duplicate->job_id)
                ->where('user_id', $duplicate->user_id)
                ->min('id');

            DB::table('applications')
                ->where('job_id', $duplicate->job_id)
                ->where('user_id', $duplicate->user_id)
                ->where('id', '!=', $keep)
                ->delete();
        }

        Schema::table('applications', function (Blueprint $table) {
            $table->unique(['job_id', 'user_id'], 'applications_job_user_unique');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique('applications_job_user_unique');
        });
    }
};
