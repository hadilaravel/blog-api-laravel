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

Route::get('posts' , [\Modules\Admin\Http\Controllers\Blog\PostController::class , 'postActive']);

Route::get('categories' , [\Modules\Admin\Http\Controllers\Blog\CategoryController::class , 'categoryActive']);

Route::middleware('throttle:2,2')->post('comment/post/{post}' , [\Modules\Admin\Http\Controllers\Blog\CommentController::class , 'storeComment']);
