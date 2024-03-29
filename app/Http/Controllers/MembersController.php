<?php

namespace App\Http\Controllers;

use App\Http\Requests\RemoveMemberRequest;
use App\Http\Resources\UserCollection;
use App\Mail\InvitationEmail;
use App\Models\Account;
use App\Models\User;
use App\Models\Invitation;
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
     * Invite a member to an account.
     *
     * @param Request $request
     * @param Account $account
     * @return \Illuminate\Http\JsonResponse
     **/
    public function invite(Request $request, Account $account)
    {
        $this->authorize('accessMembers', $account);
    
        $request->validate([
            'email' => 'required|email|unique:invitations,email',
        ]);
    
        $token = Str::random(60);
    
        $invitation = Invitation::create([
            'email'      => $request->email,
            'token'      => $token,
            'accepted'   => false,
            'account_id' => $account->id,
        ]);
    
        try {
            \Mail::to($request->email)->send(new InvitationEmail($token));
        } catch (\Exception $e) {
            $invitation->delete();
            
            \Log::error('Failed to send invitation email: ' . $e->getMessage());
    
            return response()->json(['message' => 'Failed to send the invitation.'], 500);
        }
    
        return response()->json(['message' => 'Invitation sent successfully.'], 201);
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
