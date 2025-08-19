<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coin;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Purchase;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $userEmail = $user->email;

        // Get total eco coins for the user
        $totalEcoCoins = Coin::getTotalCoinsForUser($userEmail);

        // Get recent earnings (last 3)
        $recentEarnings = Coin::getRecentEarnings($userEmail, 3);

        // Get products for marketplace
        $products = Product::getActiveProducts();

        // Get cart items
        $cartItems = Cart::getCartItems($userEmail);
        $cartTotal = Cart::getCartTotal($userEmail);

        // Get purchase history
        $purchaseHistory = Purchase::getPurchaseHistory($userEmail, 5);

        // Update the profile total_token column
        $this->updateProfileTokens($userEmail, $totalEcoCoins);

        return view('reward', compact(
            'totalEcoCoins',
            'recentEarnings',
            'products',
            'cartItems',
            'cartTotal',
            'purchaseHistory'
        ));
    }

    /**
     * Add test data for a user
     */
    private function addTestData($userEmail)
    {
        $testData = [
            [
                'user_email' => $userEmail,
                'reason' => 'Beach Cleanup Event',
                'eco_coin_value' => 150,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_email' => $userEmail,
                'reason' => 'Waste Report Verified',
                'eco_coin_value' => 75,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_email' => $userEmail,
                'reason' => 'Monthly Challenge',
                'eco_coin_value' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($testData as $data) {
            Coin::create($data);
        }
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:10'
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        // Check if product is available
        if ($product->stock < $quantity) {
            return response()->json(['success' => false, 'message' => 'Insufficient stock']);
        }

        // Add or update cart item
        $cartItem = Cart::updateOrCreate(
            [
                'user_email' => $user->email,
                'product_id' => $request->product_id
            ],
            [
                'quantity' => DB::raw("quantity + $quantity")
            ]
        );

        $cartTotal = Cart::getCartTotal($user->email);
        $cartCount = Cart::where('user_email', $user->email)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        Cart::where('user_email', $user->email)
            ->where('product_id', $request->product_id)
            ->delete();

        $cartTotal = Cart::getCartTotal($user->email);
        $cartCount = Cart::where('user_email', $user->email)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount
        ]);
    }

    /**
     * Complete purchase
     */
    public function completePurchase(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|array',
            'delivery_address.fullName' => 'required|string',
            'delivery_address.phone' => 'required|string',
            'delivery_address.address' => 'required|string',
            'delivery_address.city' => 'required|string',
            'delivery_address.zipCode' => 'required|string'
        ]);

        $user = Auth::user();
        $userEmail = $user->email;

        // Get cart items
        $cartItems = Cart::getCartItems($userEmail);
        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Cart is empty']);
        }

        // Calculate total cost
        $totalCost = Cart::getCartTotal($userEmail);
        $userCoins = Coin::getTotalCoinsForUser($userEmail);

        // Check if user has enough coins
        if ($userCoins < $totalCost) {
            return response()->json(['success' => false, 'message' => 'Insufficient EcoCoins']);
        }

        DB::transaction(function () use ($cartItems, $userEmail, $request, $totalCost) {
            // Create purchase records
            foreach ($cartItems as $cartItem) {
                Purchase::create([
                    'user_email' => $userEmail,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'eco_coin_price' => $cartItem->product->eco_coin_price,
                    'total_cost' => $cartItem->quantity * $cartItem->product->eco_coin_price,
                    'delivery_address' => $request->delivery_address,
                    'status' => 'confirmed'
                ]);

                // Update product stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Deduct coins from user
            Coin::create([
                'user_email' => $userEmail,
                'reason' => 'Purchase - Order #' . time(),
                'eco_coin_value' => -$totalCost
            ]);

            // Clear cart
            Cart::clearCart($userEmail);

            // Update profile total tokens
            $newTotal = Coin::getTotalCoinsForUser($userEmail);
            $this->updateProfileTokens($userEmail, $newTotal);
        });

        return response()->json(['success' => true, 'message' => 'Purchase completed successfully']);
    }
    public function addCoins(Request $request)
    {
        $request->validate([
            'user_email' => 'required|email',
            'reason' => 'required|string|max:255',
            'eco_coin_value' => 'required|integer|min:1'
        ]);

        Coin::create([
            'user_email' => $request->user_email,
            'reason' => $request->reason,
            'eco_coin_value' => $request->eco_coin_value
        ]);

        // Update profile total tokens
        $totalCoins = Coin::getTotalCoinsForUser($request->user_email);
        $this->updateProfileTokens($request->user_email, $totalCoins);

        return response()->json(['success' => true, 'message' => 'Eco coins added successfully']);
    }

    /**
     * Update profile total tokens
     */
    private function updateProfileTokens($email, $totalCoins)
    {
        $profile = Profile::where('email', $email)->first();
        if ($profile) {
            $profile->update(['total_token' => $totalCoins]);
        }
    }
}
