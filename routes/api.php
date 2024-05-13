<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\Entry\DeviceDataEntry;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\PeripheralAlertConfigController;
use App\Http\Controllers\PeripheralController;
use App\Http\Controllers\PeripheralDataController;
use App\Http\Controllers\UserConfigController;
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

Route::post('login', [AuthController::class, 'login'])->middleware('throttle:3,1');
Route::post('register/{token?}', [AuthController::class, 'register'])->middleware('throttle:10,1');
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

    Route::apiResource('user/config', UserConfigController::class)->only(['index', 'store']);

    Route::apiResource('alerts', AlertController::class)->only(['index', 'update']);
    Route::get('alerts/config/peripheral', [PeripheralAlertConfigController::class, 'index'])->name('alerts.config.peripheral');
    Route::post('alerts/config/peripheral', [PeripheralAlertConfigController::class, 'store'])->name('alerts.config.peripheral.store');
    Route::put('alerts/config/peripheral/{peripheralAlert}', [PeripheralAlertConfigController::class, 'update'])->name('alerts.config.peripheral.update');
    Route::delete('alerts/config/peripheral/{peripheralAlert}', [PeripheralAlertConfigController::class, 'destroy'])->name('alerts.config.peripheral.destroy');
});

Route::middleware(['auth.device', 'throttle:10,1'])->group(function () {
    Route::post('/entry', [DeviceDataEntry::class, 'store']);
});
