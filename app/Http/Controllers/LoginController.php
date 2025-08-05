<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'username' => 'required|min:4',
            'email' => 'required|email|unique:logins',
            'password' => 'required|min:4|confirmed',
            'present_address' => 'required',
        ]);

        $login = Login::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'present_address' => $request->present_address,
        ]);

        return redirect('/signup?mode=signin#')->with('success', 'Account created successfully!');
    }

    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Login::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        // Start a session for the user
        session(['user_id' => $user->id]);
        session(['username' => $user->username]);

        return redirect('/home')->with('success', 'Signed in successfully!');
    }
}
