<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobValidationTest extends TestCase
{
    use RefreshDatabase;

    private function recruiter(): User
    {
        return User::create([
            'name' => 'recruteur@example.com',
            'email' => 'recruteur@example.com',
            'password' => 'password123',
            'role' => 'recruiter',
            'status' => 'active',
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Développeur',
            'description' => 'Poste de développeur',
            'company' => 'TechCorp',
        ], $overrides);
    }

    public function test_it_rejects_a_maximum_salary_below_the_minimum(): void
    {
        $this->actingAs($this->recruiter())
            ->post('/recruiter/jobs', $this->payload([
                'salary_min' => 800000,
                'salary_max' => 400000,
            ]))
            ->assertSessionHasErrors('salary_max');

        $this->assertSame(0, Job::count());
    }

    public function test_it_rejects_a_negative_salary(): void
    {
        $this->actingAs($this->recruiter())
            ->post('/recruiter/jobs', $this->payload(['salary_min' => -1]))
            ->assertSessionHasErrors('salary_min');

        $this->assertSame(0, Job::count());
    }

    public function test_it_accepts_a_coherent_salary_range(): void
    {
        $this->actingAs($this->recruiter())
            ->post('/recruiter/jobs', $this->payload([
                'salary_min' => 400000,
                'salary_max' => 800000,
            ]))
            ->assertSessionHasNoErrors();

        $this->assertSame(1, Job::count());
    }

    public function test_the_same_rule_applies_when_editing_an_existing_job(): void
    {
        $recruiter = $this->recruiter();
        $job = Job::create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Développeur',
            'description' => 'Poste',
            'company' => 'TechCorp',
            'status' => 'active',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->actingAs($recruiter)
            ->put("/recruiter/jobs/{$job->id}", $this->payload([
                'salary_min' => 800000,
                'salary_max' => 400000,
            ]))
            ->assertSessionHasErrors('salary_max');
    }
}
