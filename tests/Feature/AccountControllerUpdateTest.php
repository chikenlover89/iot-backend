<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;

class AccountControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_account()
    {
        $account = Account::factory()->create();
        $accountPostRequest = [
            'name'       => Faker::create()->company,
            'utc_offset' => '+00:00',
        ];

        /** @disregard P1006*/
        $this->actingAs($account->creator)
             ->putJson(route('accounts.update', ['account' => $account->id]), $accountPostRequest)
             ->AssertOk()
             ->assertJsonFragment(['name' => $accountPostRequest['name']]);
    }

    public function test_user_cannot_update_other_account()
    {
        $account1 = Account::factory()->create();
        $account2 = Account::factory()->create();
        $accountPostRequest = [
            'name'       => Faker::create()->company,
            'utc_offset' => '+00:00',
        ];

        /** @disregard P1006*/
        $this->actingAs($account1->creator)
             ->putJson(route('accounts.update', ['account' => $account2->id]), $accountPostRequest)
             ->assertNotFound();
    }
}
