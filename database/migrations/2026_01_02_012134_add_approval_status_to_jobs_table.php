<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recruitment_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('recruitment_jobs', 'approval_status')) {
                $table->string('approval_status')->default('pending')->after('status');
                // pending, approved, rejected
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruitment_jobs', function (Blueprint $table) {
            $table->dropColumn('approval_status');
        });
    }
};
