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

//Route::middleware(['auth:sanctum' , 'auth.check'])->prefix('admin')->group(function (){
Route::prefix('admin')->group(function (){

    Route::get('/' ,[AdminController::class , 'indexAdmin']);

//    Route::middleware('permission:PermissionCategory')->prefix('category')->group(function (){
     Route::prefix('category')->group(function (){
        Route::get('/' , [CategoryController::class , 'index']);
        Route::get('single-category/{category}' , [CategoryController::class , 'singleCategory']);
        Route::post('store' , [CategoryController::class , 'store']);
        Route::put('update/{category}' , [CategoryController::class , 'update']);
        Route::delete('delete/{category}' , [CategoryController::class , 'delete']);
        Route::get('status/{category}' , [CategoryController::class , 'status']);
    });

    Route::get('active-categories-edit/{category}' , [CategoryController::class , 'editActiveCategories']);
    Route::get('active-categories' , [CategoryController::class , 'activeCategories']);

//    Route::middleware('permission:PermissionPost')->prefix('post')->group(function (){
    Route::prefix('post')->group(function (){
        Route::get('/' , [PostController::class , 'index']);
        Route::get('single-post/{post}' , [PostController::class , 'singlePost']);
        Route::post('store' , [PostController::class , 'store']);
        Route::put('update/{post}' , [PostController::class , 'update']);
        Route::delete('delete/{post}' , [PostController::class , 'delete']);
        Route::get('status/{post}' , [PostController::class , 'status']);
    });

    //     setting
    Route::middleware('permission:PermissionSetting')->prefix('setting')->group(function (){
        Route::get('/', [SettingeController::class, 'index'])->name('admin.setting.index');
        Route::put('update/{setting}', [SettingeController::class, 'update'])->name('admin.setting.update');
    });

    //      sms setting
    Route::middleware('permission:PermissionSmsSetting')->prefix('sms-setting')->group(function () {
        Route::get('/', [SmsSettingController::class, 'index'])->name('admin.setting.sms-setting.index');
        Route::put('update/{smsSetting}', [SmsSettingController::class, 'update'])->name('admin.setting.sms-setting.update');
    });

    Route::middleware('permission:PermissionComment')->prefix('comments')->group(function (){
        Route::get('/' , [CommentController::class , 'index']);
        Route::delete('delete/{comment}' , [CommentController::class , 'delete']);
        Route::get('status/{comment}' , [CommentController::class , 'status']);
    });

    Route::middleware('permission:PermissionCustomer')->prefix('customer')->group(function (){
        Route::get('/', [CustomerController::class, 'index']);
        Route::get('/activation/{user}', [CustomerController::class, 'activation']);
    });

//    Route::middleware('permission:PermissionUserAdmin')->prefix('user-admin')->group(function (){
    Route::prefix('user-admin')->group(function (){
        Route::get('/' , [AdminController::class , 'index']);
        Route::get('single-admin/{user}' , [AdminController::class , 'singleUserAdmin']);
        Route::post('store' , [AdminController::class , 'store']);
        Route::put('update/{user}' , [AdminController::class , 'update']);
        Route::delete('delete/{user}' , [AdminController::class , 'destroy']);
        Route::get('activation/{user}' , [AdminController::class , 'activation']);
        Route::post('role/{user}' , [AdminController::class , 'roleStore']);
        Route::get('role-delete/{user}/{role}' , [AdminController::class , 'roleDelete']);
    });

    Route::middleware('permission:PermissionRole')->prefix('role')->group(function (){
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
