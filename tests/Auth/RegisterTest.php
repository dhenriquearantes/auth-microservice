<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase 
{
    use RefreshDatabase;
    
    public function testRegisterUser()
    {
        $userData = [
            'name' => 'Pedro Junqueira',
            'email' => 'pedrojuquin@email.com',
            'password' => 'tabajara123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'User registered successfully',
            'token' => $response->json('token'),
        ]);
        
        $this->assertDatabaseHas('auth_users', [
            'name' => 'Pedro Junqueira',
            'email' => 'pedrojuquin@email.com',
        ]);
    }
}