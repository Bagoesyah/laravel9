<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    //Post Create
    function post(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'author' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => $validate->messages()
            ]);
        }

        $post = new post;
        $post->title = $request->title;
        $post->url_key = Str::slug(rand());
        $post->content = $request->content;
        $post->author = $request->author;

        $post->save();

        return response()->json(
            [
                "message" => "success",
                "data" => $post
            ]
        );
    }


    //Post List
    public function get()
    {
        $data = DB::table('posts')->simplePaginate(1);

        return response()->json(
            [
                "message" => "success",
                "data" => $data
            ]
        );
    }

    //Post Detail
    public function show($id)
    {
        $Post = Post::where('id', $id)->first();

        return response()->json(
            [
                "message" => "success",
                "data" => $Post
            ]
        );
    }

    //Post Update
    public function edit($id, Request $request)
    {
        $Post = Post::where('id', $id)->first();
        if($Post){
            $Post->title = $request->title ? $request->title : $Post->title;
            $Post->content = $request->content ? $request->content : $Post->content;
            $Post->author = $request->author ? $request->author : $Post->author;

            $Post->save();
            return response()->json(
                [
                    "message" => "update" . " " . $id . " " . "success",
                    "data" =>  $Post
                ]
            );
        }
        else {
            return response()->json(
                [
                    "message" => "post with id" . " " . $id . " " . "not found"
                ], $status = 400
            );
        }

    }

    //Post Delete
    public function delete($id)
    {
        $Post = Post::where('id', $id)->first();
        if($Post){
            $Post->delete();
            return response()->json(
                [
                    "message" => "delete product id" . " " . $id . " " . "success"
                ]
            );
        }
        else{
            return response()->json(
                [
                    "message" => "post with id" . " " . $id . " " . "not found"
                ], $status = 400
            );
        }
    }
}
