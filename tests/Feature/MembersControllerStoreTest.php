<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembersControllerStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_invitation_can_be_added_as_member()
    {
        $user       = User::factory()->create();
        $account    = Account::factory()->create();
        $invitation = Invitation::factory()->create([
            'email' => $user->email,
            'account_id' => $account->id,
        ]);

        /** @disregard P1009 */
        $this->actingAs($user);

        $response = $this->post(route('accounts.members.store', $account));

        $response->assertStatus(201);

        $this->assertTrue($invitation->fresh()->accepted);
        $this->assertTrue($account->fresh()->members->contains($user->id));
    }

    public function test_user_without_invitation_cannot_be_added_as_member()
    {
        $user    = User::factory()->create();
        $account = Account::factory()->create();
        
        /** @disregard P1009 */
        $this->actingAs($user);

        $response = $this->post(route('accounts.members.store', $account));

        $response->assertStatus(404);

        $this->assertTrue(!$account->fresh()->members->contains($user->id));
    }
}
