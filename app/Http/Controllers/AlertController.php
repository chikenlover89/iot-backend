<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAlertRequest;
use App\Http\Resources\AlertCollection;
use App\Http\Resources\AlertResource;
use App\Models\Alert;
use Auth;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Display a listing of the all account alerts.
     */
    public function index()
    {
        return new AlertCollection(Auth::user()->account->alerts);
    }

    /**
     * Resolve alerts manually
     */
    public function update(UpdateAlertRequest $request, Alert $alert)
    {
        $alert->update($request->validated());

        return new AlertResource($alert);
    }
}
