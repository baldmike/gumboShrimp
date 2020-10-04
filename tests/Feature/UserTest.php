<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testLoginRandomAdmin()
    {
        $response = $this->loginRandomAdmin();

        $response->assertStatus(200);
        $this->assertStringContainsString('token', $response->content());
        $this->assertStringContainsString('user', $response->content());
    }

    public function testCurrentUser()
    {
        $response = $this->loginRandomAdmin();
        $response->assertStatus(200);

        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer ' . $this->token;

        $currentUser = $this->get("/api/user", $header);
        $currentUser->assertStatus(200);
    }

    public function testLogout()
    {
        $response = $this->logoutUser();
        $response->assertStatus(204);
    }

    public function testRegisterUser()
    {
        $user = factory(User::class)->make();
        
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'email_verified_at' => $user->email_verified_at,
            'password' => $user->password,
            'remember_token' => $user->remember_token
        ];

        $response = $this->json('POST', '/api/register', $data);
        $response->assertStatus(201);

        // delete the user we just made
        User::where('email', $user->email)->first()->delete();
    }

    public function testRegisterFailsWithNoEmail()
    {
        $user = factory(User::class)->make();
        
        $data = [
            'name' => $user->name,
            'email' => "THIS IS NOT A VALID EMAIL",
            'role' => $user->role,
            'email_verified_at' => $user->email_verified_at,
            'password' => $user->password,
            'remember_token' => $user->remember_token
        ];

        $response = $this->json('POST', '/api/register', $data);
        $response->assertStatus(422);
    }

    public function testRegisterFailsWithNoName()
    {
        $user = factory(User::class)->make();
        
        $data = [
            
            'email' => $user->email,
            'role' => $user->role,
            'email_verified_at' => $user->email_verified_at,
            'password' => $user->password,
            'remember_token' => $user->remember_token
        ];

        $response = $this->json('POST', '/api/register', $data);
        $response->assertStatus(422);
    }
}
