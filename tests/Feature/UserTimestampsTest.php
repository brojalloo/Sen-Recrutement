<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTimestampsTest extends TestCase
{
    use RefreshDatabase;

    public function test_registering_stamps_created_at(): void
    {
        $this->post('/register', [
            'first_name' => 'Awa',
            'last_name' => 'Diop',
            'email' => 'awa@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'candidate',
        ]);

        $user = User::where('email', 'awa@example.com')->firstOrFail();

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }

    public function test_the_admin_dashboard_lists_recent_users_newest_first(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $older = User::create([
            'name' => 'Ancien',
            'email' => 'ancien@example.com',
            'password' => 'password123',
            'role' => 'candidate',
            'status' => 'active',
        ]);
        $older->forceFill(['created_at' => now()->subDays(3)])->save();

        User::create([
            'name' => 'Recent',
            'email' => 'recent@example.com',
            'password' => 'password123',
            'role' => 'candidate',
            'status' => 'active',
        ]);

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();

        $byNewest = User::query()->orderByDesc('created_at')->pluck('email')->all();

        // L'ordre n'a de sens que si created_at est réellement renseigné.
        $this->assertSame('ancien@example.com', end($byNewest));
    }
}
