<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;

class AuthorizeDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $deviceId = $request->header('Device-ID');
        $token    = $request->header('Device-Token');

        $device = Device::where('id', $deviceId)->where('token', $token)->first();

        if (!$device) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->attributes->add(['device_id' => $device->id]);

        return $next($request);
    }
}
