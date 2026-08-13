<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (! Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable();
            }
            if (! Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active');
            }
            if (! Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
            if (! Schema::hasColumn('users', 'reset_token')) {
                $table->string('reset_token')->nullable();
            }
            if (! Schema::hasColumn('users', 'reset_token_expires')) {
                $table->timestamp('reset_token_expires')->nullable();
            }
            if (! Schema::hasColumn('users', 'reset_expires')) {
                $table->timestamp('reset_expires')->nullable();
            }
            if (! Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }
            if (! Schema::hasColumn('users', 'cv_path')) {
                $table->string('cv_path')->nullable();
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }
            if (! Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (! Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable();
            }
            if (! Schema::hasColumn('users', 'company_description')) {
                $table->text('company_description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'phone', 'role', 'status', 'avatar', 'reset_token', 'reset_token_expires', 'reset_expires', 'last_login_at', 'cv_path', 'address', 'bio', 'company_name', 'company_description',
            ]);
        });
    }
};
