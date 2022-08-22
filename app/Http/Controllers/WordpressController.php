<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WordpressController extends Controller
{
    protected $url = 'http://wordpress.local/wp-json/wp/v2/';
    public function importPosts($page = 1)
    {
        $posts = collect($this->getJson($this->url . 'posts/?_embed&filter[orderby]=modified&page=' . $page));
        foreach ($posts as $post) {
            $this->syncPost($post);
        }
    }
    protected function getJson($url)
    {
        $response = file_get_contents($url, false);
        return json_decode( $response );
    }

    protected function syncPost($data)
    {
        $found = Post::where('wp_id', $data->id)->first();
        if (! $found) {
            return $this->createPost($data);
        }
        if ($found and $found->updated_at->format("Y-m-d H:i:s") < $this->carbonDate($data->modified)->format("Y-m-d H:i:s")) {
            return $this->updatePost($found, $data);
        }
    }
    protected function carbonDate($date)
    {
        return Carbon::parse($date);
    }

    protected function createPost($data)
    {
        $post = new Post();
        $post->id = $data->id;
        $post->wp_id = $data->id;
        $post->user_id = $this->getAuthor($data->_embedded->author);
        $post->title = $data->title->rendered;
        $post->url_key = $data->url_key;
        $post->featured_image = $this->featuredImage($data->_embedded);
        $post->featured = ($data->sticky) ? 1 : null;
        $post->excerpt = $data->excerpt->rendered;
        $post->content = $data->content->rendered;
        $post->format = $data->format;
        $post->publishes_at = $this->carbonDate($data->date);
        $post->created_at = $this->carbonDate($data->date);
        $post->updated_at = $this->carbonDate($data->modified);
        $post->save();
        $this->syncTags($post, $data->_embedded->{"wp:term"});
        return $post;
    }

    public function featuredImage($data)
    {
        if (property_exists($data, "wp:featuredmedia")) {
            $data = head($data->{"wp:featuredmedia"});
            if (isset($data->source_url)) {
                return $data->source_url;
            }
        }
        return null;
        }
    private function syncTags(Post $post, $tags)
    {
        $tags = collect($tags)->collapse()->where('taxonomy', 'post_tag')->pluck('name')->toArray();
        if (count($tags) > 0) {
            $post->setTags($tags);
        }
    }
}
