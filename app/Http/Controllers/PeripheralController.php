<?php

namespace App\Http\Controllers;

use App\Http\Resources\PeripheralCollection;
use App\Models\Account;
use App\Models\Device;
use App\Models\Peripheral;
use Illuminate\Http\Request;

class PeripheralController extends Controller
{
    /**
     * Display all peripherals.
     */
    public function index(Account $account, Device $device)
    {
        $this->authorize('viewDevices', $account);

        return new PeripheralCollection($device->peripherals);
    }

     /**
     * Destroy peripheral.
     */
    public function destroy(Account $account, Peripheral $peripheral)
    {
        $this->authorize('accessDevices', $account);

        $peripheral->delete();

        return response()->json(['message' => 'Peripheral deleted successfully.']);
    }
}
