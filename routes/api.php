<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\PeripheralController;
use App\Http\Controllers\PeripheralDataController;
use App\Http\Handlers\DeviceDataHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'prevent.blocked'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register/{token?}', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'prevent.blocked'])->group(function () {
    Route::apiResource('accounts', AccountController::class);
    Route::put('accounts/{account}/activate', [AccountController::class, 'activate'])->name('accounts.activate');

    Route::apiResource('accounts.members', MembersController::class)->only(['index', 'store']);
    Route::delete('accounts/{account}/members/{user}', [MembersController::class, 'destroy'])->name('accounts.members.destroy');

    Route::get('invitations', [InvitationController::class, 'index']);
    Route::post('invitations/{account}', [InvitationController::class, 'store']);

    Route::apiResource('devices', DeviceController::class);
    Route::apiResource('devices.peripherals', PeripheralController::class)->only(['index', 'destroy']);
    Route::apiResource('devices.peripherals.data', PeripheralDataController::class)->only(['index']);
});

Route::middleware(['auth.device'])->group(function () {
    Route::post('/devicehandler', [DeviceDataHandler::class, 'store']);
});
