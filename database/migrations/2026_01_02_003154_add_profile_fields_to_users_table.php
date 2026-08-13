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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'cv_path')) {
                $table->string('cv_path')->nullable()->after('avatar');
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('address');
            }
            if (! Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable()->after('bio');
            }
            if (! Schema::hasColumn('users', 'company_description')) {
                $table->text('company_description')->nullable()->after('company_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cv_path', 'address', 'bio', 'company_name', 'company_description']);
        });
    }
};
