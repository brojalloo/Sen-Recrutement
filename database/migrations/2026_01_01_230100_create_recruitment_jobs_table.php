<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruiter_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('company');
            $table->string('company_logo')->nullable();
            $table->string('location')->nullable();
            $table->string('type')->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->string('experience')->nullable();
            $table->text('skills')->nullable();
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('expiration_date')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_jobs');
    }
};
