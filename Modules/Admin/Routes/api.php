<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\Blog\CategoryController;
use Modules\Admin\Http\Controllers\Blog\CommentController;
use Modules\Admin\Http\Controllers\Blog\PostController;
use Modules\Admin\Http\Controllers\Setting\SettingeController;
use Modules\Admin\Http\Controllers\User\AdminController;
use Modules\Admin\Http\Controllers\User\CustomerController;
use Modules\Admin\Http\Controllers\Setting\SmsSettingController;
use Modules\Access\Http\Controllers\AccessController;
use Modules\Admin\Http\Controllers\User\AdminLoginController;

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

Route::middleware(['auth:sanctum' , 'auth.check'])->prefix('admin')->group(function (){

    Route::prefix('category')->group(function (){
        Route::get('/' , [CategoryController::class , 'index']);
        Route::post('store' , [CategoryController::class , 'store']);
        Route::put('update/{category}' , [CategoryController::class , 'update']);
        Route::delete('delete/{category}' , [CategoryController::class , 'delete']);
        Route::get('status/{category}' , [CategoryController::class , 'status']);
    });

    Route::prefix('post')->group(function (){
        Route::get('/' , [PostController::class , 'index']);
        Route::get('active-categories' , [CategoryController::class , 'activeCategories']);
        Route::post('store' , [PostController::class , 'store']);
        Route::put('update/{post}' , [PostController::class , 'update']);
        Route::delete('delete/{post}' , [PostController::class , 'delete']);
        Route::get('status/{post}' , [PostController::class , 'status']);
    });

    //     setting
    Route::prefix('setting')->group(function (){
        Route::get('/', [SettingeController::class, 'index'])->name('admin.setting.index');
        Route::put('update/{setting}', [SettingeController::class, 'update'])->name('admin.setting.update');
    });

    //      sms setting
    Route::prefix('sms-setting')->group(function () {
        Route::get('/', [SmsSettingController::class, 'index'])->name('admin.setting.sms-setting.index');
        Route::put('update/{smsSetting}', [SmsSettingController::class, 'update'])->name('admin.setting.sms-setting.update');
    });

    Route::prefix('comments')->group(function (){
        Route::get('/' , [CommentController::class , 'index']);
        Route::delete('delete/{comment}' , [CommentController::class , 'delete']);
        Route::get('status/{comment}' , [CommentController::class , 'status']);
    });

    Route::prefix('customer')->group(function (){
        Route::get('/', [CustomerController::class, 'index']);
        Route::get('/activation/{user}', [CustomerController::class, 'activation']);
    });

    Route::prefix('user-admin')->group(function (){
        Route::get('/' , [AdminController::class , 'index']);
        Route::post('store' , [AdminController::class , 'store']);
        Route::put('update/{user}' , [AdminController::class , 'update']);
        Route::delete('delete/{user}' , [AdminController::class , 'destroy']);
        Route::get('activation/{user}' , [AdminController::class , 'activation']);
        Route::post('role/{user}' , [AdminController::class , 'roleStore']);
        Route::get('role-delete/{user}/{role}' , [AdminController::class , 'roleDelete']);
    });

    Route::prefix('role')->group(function (){
        Route::get('/' , [AccessController::class , 'index']);
        Route::post('store' , [AccessController::class , 'store']);
        Route::put('update/{role}' , [AccessController::class , 'update']);
        Route::delete('destroy/{role}' , [AccessController::class , 'destroy']);
        Route::get('permission/{role}' , [AccessController::class , 'permissionShow']);
        Route::post('permission/{role}' , [AccessController::class , 'permissionStore']);
        Route::get('delete/permission/{role}/{permission}' , [AccessController::class , 'permissionDelete']);
    });

});

Route::prefix('admin-login')->group(function (){
    Route::post('store' , [AdminLoginController::class , 'store'])->name('admin.admin-login.store');
});
