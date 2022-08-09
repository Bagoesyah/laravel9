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
    Route::post('/posts/create', [PostController::class, 'post']);});


//show all
Route::get('/posts', [PostController::class, 'get']);

//show detail
Route::get('/posts/{id}', function (Post $id) {
    $post = Post::find($id);

    return response()->json(
        [
            "message" => "success",
            "data" => $post
        ]
    );
});

//update
Route::put('/posts/{id}', function ($id, Request $request) {
    $post = Post::where('id', $id)->first();
    if($post){
        $post->title = $request->title ? $request->title : $post->title;
        $post->content = $request->content ? $request->content : $post->content;
        $post->author = $request->author ? $request->author : $post->author;

        $post->save();
        return response()->json(
            [
                "message" => "update " . $id . " success",
                "data" =>  $post
            ]
        );
    }
    else {
        return response()->json(
            [
                "message" => "post with id " . $id . " not found"
            ], $status = 400
        );
    }
});

//delete
Route::delete('/posts/{id}', function ($id) {
    $post = Post::where('id', $id)->first();
    if($post){
        $post->delete();
        return response()->json(
            [
                "message" => "delete product id " . $id . " success"
            ]
        );
    }
    else{
        return response()->json(
            [
                "message" => "post with id " . $id . " not found"
            ], $status = 400
        );
    }
});

