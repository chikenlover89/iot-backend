<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventBlockedUsers
{
    /**
     * Handle an incoming request.
     *
     * If the user is blocked, revoke their current token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->blocked === User::BLOCKED) {
            $request->user('sanctum')->currentAccessToken()->delete();

            return response()->json(['message' => 'Your account is blocked.'], 403);
        }

        return $next($request);
    }
}

