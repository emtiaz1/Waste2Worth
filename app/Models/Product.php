<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'eco_coin_price',
        'stock',
        'is_active'
    ];

    protected $casts = [
        'eco_coin_price' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get active products
     */
    public static function getActiveProducts()
    {
        return self::where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();
    }
}
