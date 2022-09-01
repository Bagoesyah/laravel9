<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = "post";
    protected $fillable = [
        'title',
        'url_key',
        'content',
        'author',
        'wp_post_id'
    ];
}
