<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\post;

class postController extends Controller
{
    function post(Request $request)
    {
        $post = new post;
        $post->title = $request->title;
        $post->url_post = Str::slug($request->title);
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
    function get()
    {
        $data = post::all();

        return response()->json(
            [
                "message" => "success",
                "data" => $data
            ]
        );
    }
    function getById($id)
    {
        $data = post::where('id', $id)->get();

        return response()->json(
            [
                "message" => "success",
                "data" => $data
            ]
        );
    }
    function put($id, Request $request)
    {
        $post = post::where('id', $id)->first();
        if($post){
            $post->title = $request->title ? $request->title : $post->title;
            $post->content = $request->content ? $request->content : $post->content;
            $post->author = $request->author ? $request->author : $post->author;

            $post->save();
            return response()->json(
                [
                    "message" => "update" . " " . $id . " " . "success",
                    "data" =>  $post
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
    function delete($id)
    {
        $post = post::where('id', $id)->first();
        if($post){
            $post->delete();
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
