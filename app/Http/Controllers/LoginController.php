<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function signup(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|min:4',
            'email' => 'required|email|unique:logins',
            'password' => 'required|min:4|confirmed',
        ]);

        $login = Login::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create a profile record for the new user
        \App\Models\Profile::firstOrCreate(
            ['email' => $request->email],
            [
                'username' => $request->username,
                'email' => $request->email
            ]
        );

        return redirect('/login?mode=signin#')->with('success', 'Account created successfully!');
    }

    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Login::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email is not registered.',
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password is incorrect.',
            ]);
        }

        // Use Laravel's Auth system to login the user
        Auth::login($user);

        return redirect()->intended('/home')->with('success', 'Signed in successfully!');
    }

}
