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

        // Get purchase history
        $purchaseHistory = Purchase::getPurchaseHistory($userEmail);

        // Update the profile total_token column
        $this->updateProfileTokens($userEmail, $totalEcoCoins);

        return view('reward', compact(
            'totalEcoCoins',
            'recentEarnings',
            'products',
            'purchaseHistory'
        ));
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
