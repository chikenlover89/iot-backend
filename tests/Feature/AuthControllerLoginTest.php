<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create();
        $request = [
            'email'    => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $request);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);
    }

    public function test_login_with_invalid_credentials()
    {
        $user = User::factory()->create();
        $request = [
            'email'    => $user->email,
            'password' => 'invalid',
        ];

        $response = $this->postJson('/api/login', $request);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Login information invalid',
            ]);
    }
}
