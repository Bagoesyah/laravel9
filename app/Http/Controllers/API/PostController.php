<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    //Post Create
    function post(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'url_key' => 'required|unique:post,url_key|alpha_dash',
            'content' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => $validate->messages()
            ]);
        }

        $post = Post::create([
            "title" => $request->title,
            "url_key" => $request->url_key,
            "content" => $request->content,
            "author" => $request->author === null ? Auth::user()->name : $request->author,
        ]);
        // $post = new Post;
        // $post->title = $request->title;
        // $post->url_key = str::slug($request->url_key);
        // $post->content = $request->content;
        // $post->author = $request->author === null ? Auth::user()->name : $request->author;

        // $post->save();

        return response()->json(
            [
                "message" => "success",
                "data" => $post
            ]
        );
    }


    //Post List
    public function get(Request $request)
    {
        $limit = $request->limit;
        $data = Post::paginate($limit);
        return $data;
    }

    //Post Detail
    public function show($id) {
        $post = Post::find($id);

        return response()->json(
            [
                "message" => "success",
                "data" => $post
            ]
        );
    }

    //Post Update
    public function edit($id, Request $request) {
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
    }

    //Post Delete
    public function delete($id) {
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
    }

}
