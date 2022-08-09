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
            'urk_key' => 'unique:posts,author',
            'content' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => $validate->messages()
            ]);
        }

        $post = new post;
        $post->title = $request->title;
        $post->url_key = str::slug($request->url_key);
        $post->content = $request->content;
        $post->author = $request->author === null ? Auth::user()->name : $request->author;

        $post->save();

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

}
