<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Blog\PostController;
use Modules\Admin\Http\Controllers\Blog\CategoryController;

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

Route::prefix('admin')->group(function (){

    Route::prefix('category')->group(function (){
        Route::get('/' , [CategoryController::class , 'index']);
        Route::post('store' , [CategoryController::class , 'store']);
        Route::put('update/{category}' , [CategoryController::class , 'update']);
        Route::delete('delete/{category}' , [CategoryController::class , 'delete']);
        Route::get('status/{category}' , [CategoryController::class , 'status']);
    });

    Route::prefix('post')->group(function (){
        Route::get('/' , [PostController::class , 'index']);
        Route::post('store' , [PostController::class , 'store']);
        Route::put('update/{post}' , [PostController::class , 'update']);
        Route::delete('delete/{post}' , [PostController::class , 'delete']);
        Route::get('status/{post}' , [PostController::class , 'status']);
    });

});
