<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Post;

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//auth API
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::get('/posts', [PostController::class, 'get']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::post('/posts/create', [PostController::class, 'post']);
    Route::put('/posts/{id}', [PostController::class, 'edit']);
    Route::delete('/posts/{id}', [PostController::class, 'delete']);
});
