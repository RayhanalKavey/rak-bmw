<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'read_at',
    ];

    // Relationship: Notification belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
