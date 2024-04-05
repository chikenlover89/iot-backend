<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembersIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_user_can_access_members_list()
    {
        $account = Account::factory()->create();

        /** @disregard P1006 */
        $this->actingAs($account->creator);

        $response = $this->get(route('accounts.members.index', $account));

        $response->assertStatus(200);
    }

    public function test_unauthorized_user_cannot_access_members_list()
    {
        $account = Account::factory()->create();

        $user = User::factory()->create();
        /** @disregard P1009 */
        $this->actingAs($user);

        $response = $this->get(route('accounts.members.index', $account));

        $response->assertStatus(404);
    }
}
