<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $fillable = [
        'user_email',
        'reason',
        'eco_coin_value',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'eco_coin_value' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get total coins for a user
     */
    public static function getTotalCoinsForUser($email)
    {
        return self::where('user_email', $email)->sum('eco_coin_value');
    }

    /**
     * Get recent earnings for a user
     */
    public static function getRecentEarnings($email, $limit = 3)
    {
        return self::where('user_email', $email)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
