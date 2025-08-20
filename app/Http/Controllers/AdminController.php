<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Product;
use App\Models\Purchase;
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
}
