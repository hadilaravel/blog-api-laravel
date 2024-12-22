<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Access\Http\Controllers\AccessController;

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

Route::prefix('role')->group(function (){
    Route::get('/' , [AccessController::class , 'index']);
    Route::post('store' , [AccessController::class , 'store']);
    Route::put('update/{role}' , [AccessController::class , 'update']);
    Route::delete('destroy/{role}' , [AccessController::class , 'destroy']);
    Route::get('permission/{role}' , [AccessController::class , 'permissionShow']);
    Route::post('permission/{role}' , [AccessController::class , 'permissionStore']);
    Route::get('delete/permission/{role}/{permission}' , [AccessController::class , 'permissionDelete']);
});
