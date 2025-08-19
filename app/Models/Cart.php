<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_email',
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    /**
     * Get product relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get cart items for a user
     */
    public static function getCartItems($userEmail)
    {
        return self::where('user_email', $userEmail)
            ->with('product')
            ->get();
    }

    /**
     * Get cart total for a user
     */
    public static function getCartTotal($userEmail)
    {
        return self::where('user_email', $userEmail)
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->quantity * $item->product->eco_coin_price;
            });
    }

    /**
     * Clear cart for a user
     */
    public static function clearCart($userEmail)
    {
        return self::where('user_email', $userEmail)->delete();
    }
}
