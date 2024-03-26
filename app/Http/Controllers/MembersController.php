<?php

namespace App\Http\Controllers;

use App\Http\Requests\RemoveMemberRequest;
use App\Http\Resources\UserCollection;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    /**
     * Get a list of members for an account.
     *
     * @param Request $request
     * @param Account $account
     * @return UserCollection
     */
    public function index(Request $request, Account $account)
    {
        $this->authorize('accessMembers', $account);

        $members = $account->members;

        return new UserCollection($members);
    }

    /**
     * Remove a member(user) from an account.
     *
     * @param RemoveMemberRequest $request
     * @param Account $account
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RemoveMemberRequest $request, Account $account, User $user)
    {
        $this->authorize('accessMembers', $account);

        $account->members()->detach([$user->id]);

        return response()->json(['message' => 'User removed from account.'], 201);
    }
}
