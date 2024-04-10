<?php

namespace App\Http\Controllers;

use App\Http\Resources\PeripheralDataCollection;
use App\Models\Peripheral;
use Auth;

class PeripheralDataController extends Controller
{
    /**
     * Display all peripheral data.
     */
    public function index(Peripheral $peripheral)
    {
        $this->authorize('viewDevices', Auth::user()->account);

        return new PeripheralDataCollection($peripheral->data);
    }
}
