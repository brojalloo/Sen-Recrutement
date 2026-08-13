<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_attempts_are_throttled(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'victime@example.com',
                'password' => 'mauvais-mot-de-passe',
            ])->assertStatus(302);
        }

        $this->post('/login', [
            'email' => 'victime@example.com',
            'password' => 'mauvais-mot-de-passe',
        ])->assertStatus(429);
    }

    public function test_registration_attempts_are_throttled(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/register', [
                'email' => "spam{$i}@example.com",
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'candidate',
            ]);
        }

        $this->post('/register', [
            'email' => 'spam99@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'candidate',
        ])->assertStatus(429);
    }

    public function test_password_reset_requests_are_throttled(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/mot-de-passe-email', ['email' => 'victime@example.com']);
        }

        $this->post('/mot-de-passe-email', ['email' => 'victime@example.com'])
            ->assertStatus(429);
    }

    public function test_the_contact_form_is_throttled(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post('/contact', [
                'name' => 'Spam',
                'email' => 'spam@example.com',
                'subject' => 'Spam',
                'message' => 'Spam',
            ]);
        }

        $this->post('/contact', [
            'name' => 'Spam',
            'email' => 'spam@example.com',
            'subject' => 'Spam',
            'message' => 'Spam',
        ])->assertStatus(429);
    }

    public function test_a_normal_login_is_not_throttled(): void
    {
        $this->post('/login', [
            'email' => 'quelquun@example.com',
            'password' => 'mauvais-mot-de-passe',
        ])->assertStatus(302);
    }
}
