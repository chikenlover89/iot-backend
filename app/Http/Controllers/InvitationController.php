<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvitationCollection;
use App\Mail\InvitationEmail;
use App\Models\Account;
use App\Models\Invitation;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Str;

class InvitationController extends Controller
{
    /**
     * Get a list of pending invitations for the authenticated user.
     *
     * @param Request $request
     * @return InvitationCollection
     */
    public function index(Request $request)
    {
        return new InvitationCollection(
            Auth::user()->invitations,
        );
    }

    /**
     * Invite a new member to an account.
     *
     * @param Request $request
     * @param Account $account
     * @return \Illuminate\Http\JsonResponse
     **/
    public function store(Request $request, Account $account)
    {
        $this->authorize('sendInvitations', $account);

        $request->validate([
            'email' => 'required|email',
        ]);

        $invitation = Invitation::where('email', $request->email)
            ->where('account_id', $account->id)
            ->where('accepted', false)
            ->first();

        if (null !== $invitation) {
            return response()->json(['message' => 'Invitation already sent.'], 201);
        }

        $type = User::where('email', $request->email)->exists()
            ? Invitation::TYPE_INVITE
            : Invitation::TYPE_LOGIN;

        try {
            $token      = Str::random(60);
            $invitation = new Invitation($request->only('email'));

            $invitation->token      = $token;
            $invitation->accepted   = false;
            $invitation->account_id = $account->id;
            $invitation->save();

            \Mail::to($request->email)->send(new InvitationEmail($token, $type, $account->name));
        } catch (\Exception $e) {
            $invitation->delete();

            \Log::error('Failed to send invitation email: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to send the invitation.'], 500);
        }

        return response()->json(['message' => 'Invitation sent successfully.'], 201);
    }
}
