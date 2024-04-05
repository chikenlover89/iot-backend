<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Http\Resources\DeviceCollection;
use App\Http\Resources\DeviceResource;
use App\Models\Account;
use App\Models\Device;
use Str;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Account $account)
    {
        $this->authorize('viewDevices', $account);

        return new DeviceCollection($account->devices);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account, Device $device)
    {
        $this->authorize('viewDevices', $account);
        if ($device->account_id !== $account->id) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        return new DeviceResource($device);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeviceRequest $request, Account $account)
    {
        $this->authorize('accessDevices', $account);
        $validated = $request->validated();

        $device = new Device($validated);
        $device->account_id  = $account->id;
        $device->handler_key = Str::random(60);
        $device->save();

        return new DeviceResource($device);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeviceRequest $request, Account $account, Device $device)
    {
        $this->authorize('accessDevices', $account);
        if ($device->account_id !== $account->id) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $device->update($request->validated());

        return new DeviceResource($device);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account, Device $device)
    {
        $this->authorize('accessDevices', $account);

        $device->delete();

        return response()->json(['message' => 'Device deleted successfully.']);
    }
}