<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountControllerDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_creator_can_destroy_account()
    {
        $account = Account::factory()->create();
        /** @disregard P1006*/
        $this->actingAs($account->creator)
             ->deleteJson(route('accounts.destroy', ['account' => $account->id]))
             ->AssertOk();
    }

    public function test_user_cannot_destroy_attached_account()
    {
        $account = Account::factory()->create();
        $user    = User::factory()->create();
        $account->members()->attach($user->id);

        /** @disregard P1006*/
        $this->actingAs($user)
             ->deleteJson(route('accounts.destroy', ['account' => $account->id]))
             ->assertForbidden();
    }
}
