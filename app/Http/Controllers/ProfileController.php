<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $profile = Profile::firstOrCreate(
            ['email' => $user->email],
            [
                'username' => $user->name,
                'email' => $user->email
            ]
        );
        return view('profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = Profile::where('email', $user->email)->first();
        $data = $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $ext = $image->getClientOriginalExtension();
            $name = time() . '_' . uniqid() . '.' . $ext;
            $image->move(public_path('frontend/profileimages'), $name);
            $data['profile_picture'] = 'frontend/profileimages/' . $name;
        }

        $profile->update($data);
        return back()->with('success', 'Profile updated successfully!');
    }
}
