<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Jobs\PostJob;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    public $wpurl;
    public $user_key;
    public $pw_key;
    public function __construct()
    {
        $this->user_key = env("API_WP_USER");
        $this->pw_key = env("API_WP_PW");
        $this->wpurl = env('API_WP_URL');
    }

    // Post List
    public function get(Request $request)
    {
        $limit = $request->limit;
        $data = Post::paginate($limit);
        //ngirim data, guuzle
        $wpGet = Http::withBasicAuth(env("API_WP_USER"), env('API_WP_PW'))->get(env("API_WP_URL") . "posts");
        return response()->json([
            "db_laravel" => $data,
            "db_wordpress" => json_decode($wpGet)
        ]);
    }

    // Post Create
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

        //get author
        $author = $request->author === null ? Auth::user() : $request->author;

        //request post to wordpress
        $wpPost = Http::withBasicAuth($this->user_key, $this->pw_key)->post($this->wpurl . "posts", [
            'title' => $request->title,
            'content' => $request->content,
            'status' => "publish",
            'author' => $author->wp_user_id,
        ]);


        //get post value id from wordpress db
        $wpPostId = json_decode($wpPost, true);

        //creating  data to db local laravel
        $post = Post::create([
            "title" => $request->title,
            "url_key" => $request->url_key,
            "content" => $request->content,
            "author" => $author->name,
            "wp_post_id" => $wpPostId["id"],
        ]);

        //show messages response
        return response()->json([
            "message" => "success",
            "db_local" => $post,
            "db_wordpress" => json_decode($wpPost)
        ]);

        if($wpPost->failed()){
            PostJob::dispatch($wpPost)->onConnection('sync');
        };
    }

    // Post Detail
    public function show($id) {
        $article = Post::findOrFail($id);

        //deleting data from db wordpress
        Http::withBasicAuth($this->user_key, $this->pw_key)->get($this->wpurl . "posts/" . $article->wp_post_id);
        return response()->json(
            [
                "message" => "success",
                "data" => $article
            ]
        );
    }

    // Post Update
    public function edit($id, Request $request)
    {
        // find article id
        $article = Post::findOrFail($id);

        // get author
        $author = $request->author === null ? Auth::user() : $request->author;

        // validating data
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => $validate->messages()
            ]);
        }

        // find article id from db wp and then updating data
        $wpArticle = Http::withBasicAuth($this->user_key, $this->pw_key)->post($this->wpurl . "posts/" . $article->wp_post_id, [
            "title" => $request->title,
            "content" => $request->content,
        ]);

        // update data local db
        $article->update([
            "title" => $request->title,
            "content" => $request->content,
        ]);

        // show message
        return response()->json([
            "message" => "data berhasil di update",
            "db_local" => $article,
            "db_wordpress" => $wpArticle
        ]);
    }

    // Post Delete
    public function delete($id)
    {
        //deleting data from local
        $article = Post::findOrFail($id);
        $article->delete();

        //deleting data from db wordpress
        Http::withBasicAuth($this->user_key, $this->pw_key)->delete($this->wpurl . "posts/" . $article->wp_post_id);

        //show message
        return response()->json([
            "message" => "Data berhasil di hapus"
        ]);

    }

}
