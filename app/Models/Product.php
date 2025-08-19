<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'image',
        'stock',
        'eco_coin_value',
        'description'
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get all active products (products with stock > 0)
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveProducts()
    {
        return self::where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
