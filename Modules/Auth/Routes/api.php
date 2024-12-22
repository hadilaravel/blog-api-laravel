<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('auth')->group(function() {

    Route::prefix('register')->group(function() {
        Route::middleware('throttle:4,2')->post('store', [\Modules\Auth\Http\Controllers\RegisterController::class , 'storeRegister'])->name('auth.register.store');
        Route::get('confirm/{token}', [\Modules\Auth\Http\Controllers\RegisterController::class , 'viewConfirmRegister'])->name('auth.register.confirm.view');
        Route::post('confirm/store/{token}', [\Modules\Auth\Http\Controllers\RegisterController::class , 'storeConfirmRegister'])->name('auth.register.confirm.store');
        Route::middleware('throttle:1,2')->get('send/again/{token}', [\Modules\Auth\Http\Controllers\RegisterController::class , 'sendAgainCode'])->name('auth.register.send.code');

        Route::middleware('auth')->get('fill-information', [\Modules\Auth\Http\Controllers\RegisterController::class , 'fillInformation'])->name('auth.register.information');
        Route::middleware('auth')->post('fill-information/store', [\Modules\Auth\Http\Controllers\RegisterController::class , 'fillInformationStore'])->name('auth.register.information.store');
    });

    Route::prefix('login')->group(function() {
        Route::get('/', [\Modules\Auth\Http\Controllers\LoginController::class , 'viewLogin'])->name('auth.login.view');
        Route::post('store', [\Modules\Auth\Http\Controllers\LoginController::class , 'storeLogin'])->name('auth.login.store');

//        One time code
        Route::get('view-info-login', [\Modules\Auth\Http\Controllers\LoginController::class , 'viewInfoLogin'])->name('auth.login.information-login');
        Route::middleware('throttle:3,2')->post('view-info-store', [\Modules\Auth\Http\Controllers\LoginController::class , 'viewInfoLoginStore'])->name('auth.login.information-login.store');
        Route::get('confirm/{token}', [\Modules\Auth\Http\Controllers\LoginController::class , 'viewConfirmLogin'])->name('auth.login.confirm.view');
        Route::post('confirm/store/{token}', [\Modules\Auth\Http\Controllers\LoginController::class , 'storeConfirmLogin'])->name('auth.login.confirm.store');
    });

});
