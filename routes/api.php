<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkersController;
use App\Http\Controllers\WalletController;

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

//Authentification
Route::post('register',[UserController::class, 'register']);
Route::post('login',[UserController::class, 'login']);
Route::middleware('auth:api')->group(function(){
    Route::get('userDetail', [UserController::class,'userDetail']);
});

Route::post('/workers/init', [WorkersController::class, 'initWorkers']);
Route::post('/workers/checkExist', [WorkersController::class, 'ifExist']);
Route::put('get/workers/url', [WorkersController::class, 'getURL']);
Route::middleware('auth:api')->group(function() {
    Route::put('workers/connect', [WorkersController::class, 'workersConnect']);
    Route::get('/workers/{id}', [WorkersController::class, 'listWorkers']);
});

Route::middleware('auth:api')->group(function() {
    Route::post('wallet', [WalletController::class, 'wallet']);
    Route::get('/wallet/{id}', [WalletController::class, 'listWallet']);
});
