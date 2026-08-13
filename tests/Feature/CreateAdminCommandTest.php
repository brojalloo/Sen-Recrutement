<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_admin_user(): void
    {
        $this->artisan('admin:create', [
            'email' => 'admin@sen-recrutement.sn',
            '--password' => 'motdepasse123',
            '--name' => 'Admin Principal',
        ])->assertExitCode(0);

        $admin = User::where('email', 'admin@sen-recrutement.sn')->firstOrFail();

        $this->assertSame('admin', $admin->role);
        $this->assertSame('active', $admin->status);
        $this->assertTrue(Hash::check('motdepasse123', $admin->password));
    }

    public function test_it_refuses_an_email_that_already_exists(): void
    {
        User::create([
            'name' => 'Existant',
            'email' => 'admin@sen-recrutement.sn',
            'password' => 'motdepasse123',
            'role' => 'candidate',
            'status' => 'active',
        ]);

        $this->artisan('admin:create', [
            'email' => 'admin@sen-recrutement.sn',
            '--password' => 'motdepasse123',
        ])->assertExitCode(1);

        $this->assertSame('candidate', User::where('email', 'admin@sen-recrutement.sn')->firstOrFail()->role);
    }

    public function test_it_refuses_a_password_shorter_than_twelve_characters(): void
    {
        $this->artisan('admin:create', [
            'email' => 'admin@sen-recrutement.sn',
            '--password' => 'court',
        ])->assertExitCode(1);

        $this->assertDatabaseMissing('users', ['email' => 'admin@sen-recrutement.sn']);
    }

    public function test_it_refuses_an_invalid_email(): void
    {
        $this->artisan('admin:create', [
            'email' => 'pas-un-email',
            '--password' => 'motdepasse123',
        ])->assertExitCode(1);

        $this->assertDatabaseMissing('users', ['email' => 'pas-un-email']);
    }
}
