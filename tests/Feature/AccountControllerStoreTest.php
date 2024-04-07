<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;

class AccountControllerStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_account()
    {
        $user = User::factory()->create();
        $accountPostRequest = [
            'name' => Faker::create()->company,
        ];

        /** @disregard P1006*/
        $this->actingAs($user)
             ->postJson(route('accounts.store'), $accountPostRequest)
             ->assertCreated()
             ->assertJsonFragment(['name' => $accountPostRequest['name']]);
    }

    public function test_user_cannot_create_2_accounts()
    {
        $account = Account::factory()->create();
        $accountPostRequest = [
            'name' => Faker::create()->company,
        ];

        /** @disregard P1006*/
        $this->actingAs($account->creator)
             ->postJson(route('accounts.store'), $accountPostRequest)
             ->assertUnprocessable();
    }
}
