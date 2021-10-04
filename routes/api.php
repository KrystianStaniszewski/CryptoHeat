<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkersController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\OverclockController;
use App\Http\Controllers\GpuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function (Request $request) {
    return 'test';
});

//Authentification
Route::post('register',[UserController::class, 'register']);
Route::post('login',[UserController::class, 'login']);
Route::middleware('auth:api')->group(function(){
    Route::get('userDetail', [UserController::class,'userDetail']);
    Route::put('user/update', [UserController::class,'userUpdate']);
});

Route::post('/workers/init', [WorkersController::class, 'initWorkers']);
Route::post('/workers/checkExist', [WorkersController::class, 'ifExist']);
Route::put('get/workers/url', [WorkersController::class, 'getURL']);
Route::middleware('auth:api')->group(function() {
    Route::delete('workers/{id}', [WorkersController::class, 'deleteWorkers']);
    Route::put('workers/connect', [WorkersController::class, 'workersConnect']);
    Route::put('workers/disconnect', [WorkersController::class, 'workersDisconnect']);
    Route::get('/workers', [WorkersController::class, 'listWorkers']);
    Route::put('/workers/{id}/update', [WorkersController::class, 'updateWorkers']);
});

Route::middleware('auth:api')->group(function() {
    Route::post('wallet', [WalletController::class, 'wallet']);
    Route::get('wallets', [WalletController::class, 'listWallet']);
    Route::delete('wallet/{id}', [WalletController::class, 'delete']);
});

Route::get('/worker/gpu/overclock', [OverclockController::class, 'getWorkerGpuOverclock']);
Route::middleware('auth:api')->group(function() {
    Route::post('overclock', [OverclockController::class, 'create']);
    Route::get('overclock', [OverclockController::class, 'get']);
    Route::put('overclock', [OverclockController::class, 'update']);
});

Route::post('/gpu/init', [GpuController::class, 'initGpu']);
Route::middleware('auth:api')->group(function() {
    Route::get('/gpu/{id}', [GpuController::class, 'getGpu']);
    Route::put('/gpu/{id}', [GpuController::class, 'updateGpu']);
    Route::get('/gpus', [GpuController::class, 'getList']);
    Route::get('/hardware/list', [GpuController::class, 'getHardwareList']);
});
