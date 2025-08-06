<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            // Check if user already exists
            $existingUser = Login::where('email', $user->getEmail())->first();

            if ($existingUser) {
                // User exists, log them in using Auth
                \Auth::login($existingUser);
                return redirect('/home')->with('success', 'Signed in successfully with Google!');
            }

            // Create new user
            $newUser = Login::create([
                'username' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt(\Str::random(16)),
                'google_id' => $user->getId(),
            ]);

            \Auth::login($newUser);
            return redirect('/home')->with('success', 'Account created successfully with Google!');

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong with Google login!');
        }
    }
}
