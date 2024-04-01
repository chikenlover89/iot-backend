<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_returns_201_and_user_data_upon_successful_registration()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
    
        $response = $this->postJson('api/register', $userData);
    
        $response->assertStatus(201);
    
        $response->assertJsonStructure([
            'data' => [
                'name',
                'email',
            ],
            'access_token',
            'token_type',
        ]);
    
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
    }
    

    public function test_register_returns_422_and_validation_errors_upon_validation_errors()
    {
        $userData = [
            'name' => 'John',
            'email' => 'johndoe',
        ];
    
        $response = $this->postJson('api/register', $userData);
    
        $response->assertStatus(422);
    
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
                'password'
            ],
        ]);
    
        $this->assertDatabaseMissing('users', [
            'name' => 'John',
        ]);
    }
    

    public function test_register_returns_422_and_an_error_message_upon_a_conflict()
    {
        $user = User::factory()->create();
    
        $registrationData = [
            'name'                  => 'John Doe',
            'email'                 => $user->email,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];
    
        $response = $this->postJson('api/register', $registrationData);
    
        $response->assertStatus(422);
    
        $response->assertJsonStructure(['message', 'errors']);
    
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }
    

    public function test_register_returns_422_and_an_error_message_upon_an_invalid_invitation_token()
    {
        $invalidToken = 'invalid-token';
    
        $registrationData = [
            'name'     => 'John Doe',
            'email'    => 'john.doe@example.com',
            'password' => 'password',
        ];
    
        $response = $this->postJson("api/register/$invalidToken", $registrationData);
    
        $response->assertStatus(422);
    
        $response->assertJsonStructure(['message']);
    
        $this->assertDatabaseMissing('users', [
            'email' => 'john.doe@example.com',
        ]);
    }    
}