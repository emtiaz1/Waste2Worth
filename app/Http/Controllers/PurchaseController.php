<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Coin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string',
            'address' => 'required|string',
            'mobile' => 'required|string'
        ]);

        $product = Product::findOrFail($request->product_id);
        $user = Auth::user();

        // Calculate total coins
        $totalCoins = Coin::where('user_email', $user->email)->sum('eco_coin_value');

        if ($totalCoins < $product->eco_coin_value) {
            return back()->with('error', 'Not enough EcoCoins!');
        }

        if ($product->stock <= 0) {
            return back()->with('error', 'Product out of stock!');
        }

        // Create purchase record
        Purchase::create([
            'email' => $user->email,
            'product_id' => $product->id,
            'name' => $request->name,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'eco_coins_spent' => $product->eco_coin_value
        ]);

        // Deduct coins
        Coin::create([
            'user_email' => $user->email,
            'reason' => 'Product purchase: ' . $product->name,
            'eco_coin_value' => -$product->eco_coin_value
        ]);

        // Update stock
        $product->decrement('stock');

        return back()->with('success', 'Order placed successfully!');
    }

    public function history()
    {
        $purchases = Purchase::where('email', Auth::user()->email)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reward', compact('purchases'));
    }
}
