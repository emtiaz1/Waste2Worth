<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'email',
        'username',
        'profile_picture',
        'location',
        'status',
        'achievements',
        'contribution',
        'total_token',
        'token_usages'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'token_usages' => 'array',
    ];
}
