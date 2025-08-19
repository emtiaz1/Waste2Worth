<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumDiscussion extends Model
{
    protected $fillable = [
        'username',
        'message',
        'image',
    ];
}
