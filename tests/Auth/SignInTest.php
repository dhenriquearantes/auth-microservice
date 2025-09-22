<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class SignInTest extends TestCase 
{
    use RefreshDatabase;

    public function testSignInUser()
    {
        User::create([
            'name' => 'Pedro Junqueira',
            'email' => 'pedrojuquin@email.com',
            'password' => Hash::make('tabajara123'), 
        ]);

        $userData = [
            'email' => 'pedrojuquin@email.com',
            'password' => 'tabajara123',
        ];

        $response = $this->postJson('/api/signin', $userData);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'User signed in successfully',
            'token' => $response->json('token'),
        ]);
    }
}