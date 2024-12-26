<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Home\Http\Controllers\Post\PostController;
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

Route::get('posts' , [PostController::class , 'postActive']);
Route::get('post/detail/{post:slug}' , [PostController::class , 'postDetail']);
Route::middleware('auth:sanctum')->get('post/like/{post}' , [PostController::class , 'likePost']);

Route::get('categories' , [\Modules\Admin\Http\Controllers\Blog\CategoryController::class , 'categoryActive']);

Route::middleware(['throttle:2,2' , 'auth:sanctum'])->post('comment/post/{post}' , [\Modules\Admin\Http\Controllers\Blog\CommentController::class , 'storeComment']);
