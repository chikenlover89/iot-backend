<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountControllerShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_created_account()
    {
        $account = Account::factory()->create();
        /** @disregard P1006*/
        $this->actingAs($account->creator)
             ->getJson(route('accounts.show', $account->id))
             ->assertOk()
             ->assertJsonFragment(['id' => $account->id]);
    }

    public function test_user_cannot_access_other_accounts()
    {
        $account1 = Account::factory()->create();
        $account2 = Account::factory()->create();
        /** @disregard P1006*/
        $this->actingAs($account1->creator)
             ->getJson(route('accounts.show', $account2->id))
             ->assertNotFound();
    }

    
}
