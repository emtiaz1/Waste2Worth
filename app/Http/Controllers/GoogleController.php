<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\Profile;
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

            \Log::info('Google user data:', [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'id' => $user->getId()
            ]);

            // Check if user already exists
            $existingUser = Login::where('email', $user->getEmail())->first();

            if ($existingUser) {
                // Always update username for Google users
                $username = $user->getName() ?? explode('@', $user->getEmail())[0];
                $existingUser->username = $username;
                $existingUser->save();

                // Update or create profile
                $profile = Profile::updateOrCreate(
                    ['email' => $existingUser->email],
                    ['username' => $username]
                );

                \Log::info('Existing user and profile updated:', [
                    'user_id' => $existingUser->id,
                    'username' => $existingUser->username,
                    'email' => $existingUser->email,
                    'profile_id' => $profile->id
                ]);

                // User exists, log them in using Auth
                \Auth::login($existingUser);
                return redirect('/home')->with('success', 'Signed in successfully with Google!');
            }

            // Create new user
            $username = $user->getName() ?? explode('@', $user->getEmail())[0];
            $newUser = Login::create([
                'username' => $username,
                'email' => $user->getEmail(),
                'password' => bcrypt(\Str::random(16)),
                'google_id' => $user->getId(),
            ]);

            // Create profile for new user
            $profile = Profile::create([
                'email' => $newUser->email,
                'username' => $username
            ]);

            \Log::info('New user and profile created:', [
                'user_id' => $newUser->id,
                'username' => $newUser->username,
                'email' => $newUser->email,
                'profile_id' => $profile->id
            ]);

            \Auth::login($newUser);
            return redirect('/home')->with('success', 'Account created successfully with Google!');

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong with Google login!');
        }
    }
}
