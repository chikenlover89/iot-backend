<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerLogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_successfully()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200);

        $response->assertExactJson([
            'message' => 'Successfully logged out'
        ]);
    }
}
