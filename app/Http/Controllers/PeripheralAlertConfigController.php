<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeripheralAlertRequest;
use App\Http\Requests\UpdatePeripheralAlertRequest;
use App\Http\Resources\PeripheralAlertCollection;
use App\Http\Resources\PeripheralAlertResource;
use App\Models\Peripheral;
use App\Models\PeripheralAlert;
use Auth;
use Illuminate\Http\Request;

class PeripheralAlertConfigController extends Controller
{
    public function index(Request $request)
    {
        return new PeripheralAlertCollection(Auth::user()->account->peripheral_alerts);
    }

    public function store(StorePeripheralAlertRequest $request)
    {
        $account = Auth::user()->account;
        $this->authorize('accessDevices', $account);

        $validated = $request->validated();

        $peripheralAlert             = new PeripheralAlert($validated);
        $peripheralAlert->account_id = $account->id;
        $peripheralAlert->device_id  = (Peripheral::find($validated['peripheral_id']))->device_id;
        $peripheralAlert->save();

        return new PeripheralAlertResource($peripheralAlert);
    }

    public function update(UpdatePeripheralAlertRequest $request, PeripheralAlert $peripheralAlert)
    {
        $this->authorize('accessDevices', Auth::user()->account);

        $peripheralAlert->update($request->validated());

        return new PeripheralAlertResource($peripheralAlert);
    }

    public function destroy(Request $request, PeripheralAlert $peripheralAlert)
    {
        $this->authorize('accessDevices', Auth::user()->account);

        $peripheralAlert->delete();

        return response()->json(['message' => 'Peripheral alert config deleted successfully.']);
    }
}
