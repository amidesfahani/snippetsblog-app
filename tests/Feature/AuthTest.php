<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_user_can_register()
    {
        $response = $this->json('POST', '/api/auth/register', [
            'username' => 'amid',
            'password' => 'password',
        ]);

        $response->assertStatus(201)->assertJsonStructure(['user', 'token']);
    }

    /** @test */
    public function registration_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/auth/register', [
            'username' => '',
            'password' => 'short',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['username', 'password']);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        factory(User::class)->create([
            'username' => 'amid',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'username' => 'amid',
            'password' => 'password'
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token']);
    }

    /** @test */
    public function login_fails_with_invalid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'username' => 'laravel',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)->assertJson(['error' => 'Unauthorized']);
    }
}
