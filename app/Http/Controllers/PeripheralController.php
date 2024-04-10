<?php

namespace App\Http\Controllers;

use App\Http\Resources\PeripheralCollection;
use App\Models\Account;
use App\Models\Device;
use App\Models\Peripheral;
use Auth;
use Illuminate\Http\Request;

class PeripheralController extends Controller
{
    /**
     * Display all peripherals.
     */
    public function index(Device $device)
    {
        $this->authorize('viewDevices', Auth::user()->account);

        return new PeripheralCollection($device->peripherals);
    }

     /**
     * Destroy peripheral.
     */
    public function destroy(Peripheral $peripheral)
    {
        $this->authorize('accessDevices', Auth::user()->account);

        $peripheral->delete();

        return response()->json(['message' => 'Peripheral deleted successfully.']);
    }
}
