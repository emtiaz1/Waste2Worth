<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = null;
        
        if ($user) {
            $profile = Profile::where('email', $user->email)->first();
            
            // If no profile exists, create a default one
            if (!$profile) {
                $profile = Profile::create([
                    'email' => $user->email,
                    'username' => $user->name ?? 'User',
                ]);
            }
        }
        
        return view('home', compact('profile'));
    }
}
