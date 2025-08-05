<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
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
            $existingUser = Login::where('email', $user->email)->first();

            if ($existingUser) {
                // User exists, log them in
                session(['user_id' => $existingUser->id]);
                session(['username' => $existingUser->username]);
                return redirect('/home')->with('success', 'Signed in successfully with Google!');
            }

            // Create new user
            $newUser = Login::create([
                'username' => $user->name,
                'email' => $user->email,
                'password' => bcrypt(\Str::random(16)),
                'present_address' => '', // Default empty address
                'google_id' => $user->id,
            ]);

            session(['user_id' => $newUser->id]);
            session(['username' => $newUser->username]);

            return redirect('/home')->with('success', 'Account created successfully with Google!');

        } catch (Exception $e) {
            return redirect('/signup')->with('error', 'Something went wrong with Google login!');
        }
    }
}
