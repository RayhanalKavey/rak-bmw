<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    // In Tag model
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }
}
