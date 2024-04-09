<?php

namespace App\Http\Controllers;

use App\Http\Resources\PeripheralDataCollection;
use App\Models\Account;
use App\Models\Peripheral;
use Illuminate\Http\Request;

class PeripheralDataController extends Controller
{
    /**
     * Display all peripheral data.
     */
    public function index(Account $account, Peripheral $peripheral)
    {
        $this->authorize('viewDevices', $account);

        return new PeripheralDataCollection($peripheral->data);
    }
}
