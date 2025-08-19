<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_email',
        'product_id',
        'quantity',
        'eco_coin_price',
        'total_cost',
        'delivery_address',
        'status'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'eco_coin_price' => 'integer',
        'total_cost' => 'integer',
        'delivery_address' => 'array'
    ];

    /**
     * Get product relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get purchase history for a user
     */
    public static function getPurchaseHistory($userEmail, $limit = 10)
    {
        return self::where('user_email', $userEmail)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
