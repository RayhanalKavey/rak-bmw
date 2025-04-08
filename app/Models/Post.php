<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image',
        'visibility',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🏷️ Many-to-Many: A post can have many tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
        // If you use PostTag model: ->using(PostTag::class)
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
