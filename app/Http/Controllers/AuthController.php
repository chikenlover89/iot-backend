<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Login information invalid',
            ], 401);
        }
        $user = User::where('email', $validated['email'])->first();

        return response()->json([
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type'   => 'Bearer',
        ]);
    }

    public function register(Request $request, $token = null)
    {
        $validated = $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|max:255|email|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        $invitation = null;
        if(null !== $token) {
            $invitation = Invitation::where('email', $validated['email'])
                ->where('token', $token)
                ->where('accepted', false)
                ->first();
            if (null === $invitation) {
                return response()->json(['message' => 'Invalid invitation token.'], 404);
            }
        }

        $validated['password'] = Hash::make($validated['password']);
        $user                  = User::create($validated);

        if(null !== $invitation) {
            $invitation->accepted = true;
            $invitation->account->members()->attach($user->id);
            $invitation->save();

            $user->setAccount($invitation->account);
        }

        return response()->json([
            'data'         => $user,
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type'   => 'Bearer',
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user('sanctum')->currentAccessToken()->delete();

        return response()->json([
           'message' => 'Successfully logged out'
        ], 200);
    }
}
