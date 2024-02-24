<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;



class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    public function test_create_user()
    {
      $username = $this->faker->name;
      $email = $this->faker->unique()->safeEmail;
      $plainPassword = bin2hex(random_bytes(4));
    
      $roles = ['Admin','User'];
      $role = $roles[array_rand($roles)];
    
      $payload = [
         'username' => $username,
         'email' => $email,   
         'password' => $plainPassword,
         'role' => $role
      ];
    
      $response = $this->postJson('/api/users', $payload);
    
      $response->assertStatus(201);
    
      $response->assertJson([
        'username' => $username,
        'email' => $email,
        'role' => $role
      ]);
    
      $this->assertDatabaseHas('users', [
        'username' => $username,
        'email' => $email, 
        'role' => $role
      ]);
    
      $user = User::first();
    
      $this->assertEquals($username, $user->username);
    
      $this->assertTrue(Hash::check($plainPassword, $user->password));
    
      return [
        'email' => $email,
        'password' => $plainPassword
      ];    
    }

    public function testReturnsAValidationErrorWithInvalidData()
    {
        $response = $this->postJson('/api/users', []);
        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['username', 'email', 'password', 'role']);
    }

    public function testAuthenticatesAndReturnsAJWT()
    {
        $response= $this->test_create_user();
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => $response['email'],
            'password' => $response['password']
          ]);
        
        $loginResponse->assertStatus(200);
        $this->assertArrayHasKey('token', $loginResponse->json());
        $this->assertTrue(Hash::check($response['password'], User::first()->password));

    }

    public function testUpdatesUser()
    {
        $userCredentials = $this->test_create_user();
        $user = User::where('email', $userCredentials['email'])->first();

        $updatedData = [
            'username' => 'newusername',
            'email' => 'newemail@example.com'
        ];
        
        $response = $this->actingAs($user)
            ->putJson("/api/users/{$user->id}", $updatedData);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'newusername',
            'email' => 'newemail@example.com'
        ]);
    }

    public function testDeletesUser()
    {
        $userCredentials = $this->test_create_user();
        $user = User::where('email', $userCredentials['email'])->first();

        $response = $this->actingAs($user)
            ->deleteJson("/api/users/{$user->id}");
        
        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function testListsUsers()
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
    }

    public function testUpdateUserWithIncompleteData()
    {
        $userCredentials = $this->test_create_user();
        $user = User::where('email', $userCredentials['email'])->first();

        $response = $this->actingAs($user)
            ->putJson("/api/users/{$user->id}", ['username' => '', 'email' => '']);

        $response->assertStatus(400);
    }


}
