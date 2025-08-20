<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Coin;
use App\Models\WasteReport;
use App\Models\WasteCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function showRegisterForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:admins',
            'password' => 'required|confirmed',
        ]);

        Admin::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.login')->with('success', 'Registration successful! Please login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function home()
    {
        return view('admin.home');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    private function saveProductImage($image)
    {
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('frontend/productimage'), $filename);
        return 'frontend/productimage/' . $filename;
    }

    private function deleteProductImage($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
    }

    public function productStore(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->has('action')) {
                switch ($request->action) {
                    case 'add':
                        $request->validate([
                            'name' => 'required|string|max:255',
                            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                            'stock' => 'required|integer|min:0',
                            'eco_coin_value' => 'required|integer|min:0',
                            'description' => 'required|string'
                        ]);

                        $imagePath = $this->saveProductImage($request->file('image'));

                        Product::create([
                            'name' => $request->name,
                            'image' => $imagePath,
                            'stock' => $request->stock,
                            'eco_coin_value' => $request->eco_coin_value,
                            'description' => $request->description
                        ]);

                        return redirect()->route('admin.products')->with('success', 'Product added successfully!');

                    case 'edit':
                        $request->validate([
                            'name' => 'required|string|max:255',
                            'stock' => 'required|integer|min:0',
                            'eco_coin_value' => 'required|integer|min:0',
                            'description' => 'required|string'
                        ]);

                        $product = Product::findOrFail($request->id);

                        $data = [
                            'name' => $request->name,
                            'stock' => $request->stock,
                            'eco_coin_value' => $request->eco_coin_value,
                            'description' => $request->description
                        ];

                        if ($request->hasFile('image')) {
                            $request->validate([
                                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                            ]);

                            // Delete old image
                            $this->deleteProductImage($product->image);

                            // Save new image
                            $data['image'] = $this->saveProductImage($request->file('image'));
                        }

                        $product->update($data);
                        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');

                    case 'delete':
                        try {
                            $product = Product::findOrFail($request->id);

                            // Check if product has any related purchases
                            if ($product->purchases()->exists()) {
                                return redirect()->route('admin.products')
                                    ->with('error', 'Cannot delete product as it has related purchases. Consider updating the stock to 0 instead.');
                            }

                            // Delete the product image
                            $this->deleteProductImage($product->image);

                            $product->delete();
                            return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
                        } catch (\Exception $e) {
                            return redirect()->route('admin.products')
                                ->with('error', 'Unable to delete product. It may have related records.');
                        }
                }
            }
        }

        $products = Product::all();
        return view('admin.products', compact('products'));
    }

    public function showPurchases()
    {
        $purchases = Purchase::with(['user', 'product'])->orderBy('created_at', 'desc')->get();
        return view('admin.purchase', compact('purchases'));
    }

    public function confirmPurchase($id)
    {
        $purchase = \App\Models\Purchase::findOrFail($id);
        $purchase->status = 'confirmed';
        $purchase->save();

        return redirect()->route('admin.purchases')->with('success', 'Purchase confirmed!');
    }

    public function showUserDetails()
    {
        // Get all profiles with their aggregated data (no private profiles)
        $users = DB::table('profiles')
            ->leftJoin(DB::raw('(SELECT user_email, SUM(eco_coin_value) as total_coins FROM coins GROUP BY user_email) as c'), 'profiles.email', '=', 'c.user_email')
            ->leftJoin(DB::raw('(SELECT email, COUNT(*) as purchases_count, SUM(eco_coins_spent) as total_spent FROM purchases GROUP BY email) as p'), 'profiles.email', '=', 'p.email')
            ->select([
                'profiles.id',
                'profiles.email',
                'profiles.username',
                'profiles.first_name',
                'profiles.last_name',
                'profiles.phone',
                'profiles.date_of_birth',
                'profiles.gender',
                'profiles.profile_picture',
                'profiles.location',
                'profiles.status',
                'profiles.bio',
                'profiles.organization',
                'profiles.website',
                'profiles.achievements',
                'profiles.contribution',
                'profiles.total_token',
                'profiles.points_earned',
                'profiles.waste_reports_count',
                'profiles.community_events_attended',
                'profiles.volunteer_hours',
                'profiles.carbon_footprint_saved',
                'profiles.created_at',
                // Calculated fields
                DB::raw('COALESCE(c.total_coins, 0) as eco_coins'),
                DB::raw('COALESCE(p.purchases_count, 0) as purchases_count'),
                DB::raw('COALESCE(p.total_spent, 0) as coins_spent')
            ])
            ->orderBy('profiles.created_at', 'desc')
            ->get();

        return view('admin.user-details', compact('users'));
    }

    public function showUserDetail($id)
    {
        // Get specific profile with all related data
        $user = DB::table('profiles')
            ->leftJoin(DB::raw('(SELECT user_email, SUM(eco_coin_value) as total_coins FROM coins GROUP BY user_email) as c'), 'profiles.email', '=', 'c.user_email')
            ->leftJoin(DB::raw('(SELECT email, COUNT(*) as purchases_count, SUM(eco_coins_spent) as total_spent FROM purchases GROUP BY email) as p'), 'profiles.email', '=', 'p.email')
            ->where('profiles.id', $id)
            ->select([
                'profiles.*',
                DB::raw('COALESCE(c.total_coins, 0) as eco_coins'),
                DB::raw('COALESCE(p.purchases_count, 0) as purchases_count'),
                DB::raw('COALESCE(p.total_spent, 0) as coins_spent')
            ])
            ->first();

        if (!$user) {
            abort(404, 'Profile not found');
        }

        // Get user's coins history
        $coinTransactions = DB::table('coins')
            ->where('user_email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get user's purchases with product details
        $purchases = DB::table('purchases')
            ->leftJoin('products', 'purchases.product_id', '=', 'products.id')
            ->where('purchases.email', $user->email)
            ->select([
                'purchases.*',
                'products.name as product_name',
                'products.image as product_image',
                'products.description as product_description'
            ])
            ->orderBy('purchases.created_at', 'desc')
            ->get();

        // Since wastereport table doesn't have user_email field, provide empty collections
        $wasteReports = collect([]);
        $wasteByType = collect([]);

        return view('admin.user-detail', compact('user', 'coinTransactions', 'purchases', 'wasteReports', 'wasteByType'));
    }
}
