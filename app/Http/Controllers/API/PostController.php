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
            'url_key' => 'unique:post,author',
            'content' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => $validate->messages()
            ]);
        }

        $Post = new Post;
        $Post->title = $request->title;
        $Post->url_key = str::slug($request->url_key);
        $Post->content = $request->content;
        $Post->author = $request->author === null ? Auth::user()->name : $request->author;

        $Post->save();

        return response()->json(
            [
                "message" => "success",
                "data" => $Post
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
