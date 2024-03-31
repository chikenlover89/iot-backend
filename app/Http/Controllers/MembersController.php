<?php

namespace App\Http\Controllers;

use App\Http\Requests\RemoveMemberRequest;
use App\Http\Resources\UserCollection;
use App\Mail\InvitationEmail;
use App\Models\Account;
use App\Models\User;
use App\Models\Invitation;
use Auth;
use Illuminate\Http\Request;
use Str;

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
     * Add authenticated user for the given account if invitation exists.
     *
     * @param Request $request
     * @param Account $account
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Account $account)
    {
        $invitation = Auth::user()->invitations()->where('account_id', $account->id)->first();

        if (null === $invitation) {
            return response()->json(['message' => 'Invitation not found.'], 404);
        }

        $account->members()->attach(Auth::user()->id);

        $invitation->accepted = true;
        $invitation->save();

        return response()->json(['message' => 'Member added successfully.'], 201);
    }

    /**
     * Remove a member from an account.
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
