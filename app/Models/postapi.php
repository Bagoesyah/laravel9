<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class postapi extends Model
{
    use HasFactory;
    use softDeletes;

    protected $table = 'postapi';
    protected $fillable = [
        'title',
        'content',
        'author'
    ];

    protected $hidden = [];
}
