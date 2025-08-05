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
            'password' => 'required|min:4',
            'present_address' => 'required',
        ]);

        $login = Login::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'present_address' => $request->present_address,
        ]);

        return redirect('/home')->with('success', 'Account created successfully!');
    }
}
