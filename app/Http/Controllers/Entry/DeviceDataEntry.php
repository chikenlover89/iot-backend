<?php

namespace App\Http\Controllers\Entry;

use App\Models\RawDeviceData;
use Illuminate\Http\Request;

class DeviceDataEntry extends \App\Http\Controllers\Controller
{
    /**
     * Store data from device.
     */
    public function store(Request $request)
    {
        $data = json_encode($request->all());
        if(strlen($data) > 256) {
            return response()->json(['message' => 'Too much data'], 400);
        }

        RawDeviceData::create([
            'device_id'  => $request->attributes->get('device_id'),
            'data'       => $data,
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['message' => 'Data recieved']);
    }
}
