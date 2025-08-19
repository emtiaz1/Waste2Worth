<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'email',
        'product_id',
        'name',
        'address',
        'mobile',
        'eco_coins_spent'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get purchase history for a specific user
     *
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPurchaseHistory($email)
    {
        return self::where('email', $email)
            ->with('product')  // Eager load product relationship
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
