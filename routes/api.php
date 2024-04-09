<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\PeripheralController;
use App\Http\Controllers\PeripheralDataController;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('accounts.devices', DeviceController::class);
    Route::apiResource('accounts.devices.peripherals', PeripheralController::class)->only(['index', 'destroy']);
    Route::apiResource('accounts.devices.peripherals.data', PeripheralDataController::class)->only(['index', 'destroy']);

    Route::get('accounts/{account}/members', [MembersController::class, 'index'])->name('accounts.members.index');
    Route::post('accounts/{account}/members', [MembersController::class, 'store'])->name('accounts.members.store');
    Route::delete('accounts/{account}/members/{user}', [MembersController::class, 'destroy'])->name('accounts.members.destroy');
    
    Route::get('invitations', [InvitationController::class, 'index']);
    Route::post('invitations/{account}', [InvitationController::class, 'store']);
});
