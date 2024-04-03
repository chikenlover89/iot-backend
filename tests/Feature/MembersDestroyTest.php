<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembersDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorized_user_can_remove_member()
    {
        $member  = User::factory()->create();
        $account = Account::factory()->create();
        $account->members()->attach($member->id);
        
        /** @disregard P1009 */
        $this->actingAs($account->creator);

        $response = $this->delete(route('accounts.members.destroy', [$account, $member]));

        $response->assertStatus(201);
        
        $this->assertDatabaseMissing('member', ['account_id' => $account->id, 'user_id' => $member->id]);
    }

    /** @test */
    public function unauthorized_user_cannot_remove_member()
    {
        $user = User::factory()->create();
        
        $account = Account::factory()->create();

        /** @disregard P1009 */
        $this->actingAs($user);

        $response = $this->delete(route('accounts.members.destroy', [$account, $account->creator]));

        $response->assertStatus(403);
    }
}