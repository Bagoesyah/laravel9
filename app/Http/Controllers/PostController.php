<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Jsonable;
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

        // $variable = post::count();
        $post = new post;
        $post->title = $request->title;
        $post->url_key = str::slug($request->url_key); //. "-" . $variable + 1
        $post->content = $request->content;
        $post->author = auth::User()->name; //Error, Attempt to read property \"name\" on null
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
