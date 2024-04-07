<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountControllerIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_account_list()
    {
        $user = User::factory()->create();
        /** @disregard P1006*/
        $this->actingAs($user)
             ->getJson(route('accounts.index'))
             ->assertOk();
    }

    public function test_user_has_accounts_in_list()
    {
        $account1 = Account::factory()->create();
        $account2 = Account::factory()->create();
        $account2->members()->attach($account1->creator);
        /** @disregard P1006*/
        $this->actingAs($account1->creator)
            ->getJson(route('accounts.index'))
            ->assertJsonCount(2, 'data');
    }
}
