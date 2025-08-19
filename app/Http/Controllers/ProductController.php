<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::getActiveProducts();
        $recentEarnings = \App\Models\Coin::where('user_email', auth()->user()->email)
            ->where('eco_coin_value', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalEcoCoins = \App\Models\Coin::where('user_email', auth()->user()->email)
            ->sum('eco_coin_value');

        $purchaseHistory = \App\Models\Purchase::where('email', auth()->user()->email)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reward', compact('products', 'recentEarnings', 'totalEcoCoins', 'purchaseHistory'));
    }
}
