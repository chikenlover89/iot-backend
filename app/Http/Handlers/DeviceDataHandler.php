<?php

namespace App\Http\Handlers;

use App\Models\RawDeviceData;
use Illuminate\Http\Request;

class DeviceDataHandler extends \App\Http\Controllers\Controller
{
    /**
     * Store data from device.
     */
    public function store(Request $request)
    {
        RawDeviceData::create([
            'device_id'  => $request->attributes->get('device_id'),
            'data'       => json_encode($request->all()),
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['message' => 'Data recieved']);
    }
}
