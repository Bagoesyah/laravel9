<?php

use App\Http\Controllers\postController;
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

Route::get('/posts', [postController::class, 'get']);

Route::get('/posts/{id}', [postController::class, 'get']);

Route::post('/posts', [postController::class, 'post']);

Route::put('/posts/{id}', [postController::class, 'put']);

Route::delete('/posts/{id}', [postController::class, 'delete']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
