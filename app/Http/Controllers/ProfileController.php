<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = Profile::where('email', $user->email)->first();
        
        // If no profile exists, create a default one
        if (!$profile) {
            $profile = Profile::create([
                'email' => $user->email,
                'username' => $user->name ?? 'User',
            ]);
        }
        
        return view('profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = Profile::where('email', $user->email)->first();
        
        if (!$profile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        // Validation rules
        $request->validate([
            'username' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'organization' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'status' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notification_preferences' => 'nullable|in:all,important,none',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'profile_public' => 'boolean',
            'interests' => 'nullable|array',
            'skills' => 'nullable|array',
            'preferred_causes' => 'nullable|array',
            'social_links' => 'nullable|array',
            'social_links.facebook' => 'nullable|url',
            'social_links.twitter' => 'nullable|url',
            'social_links.linkedin' => 'nullable|url',
            'social_links.instagram' => 'nullable|url',
        ]);

        $data = $request->except(['profile_picture', '_token', '_method']);
        
        // Handle boolean fields
        $data['email_notifications'] = $request->has('email_notifications');
        $data['sms_notifications'] = $request->has('sms_notifications');
        $data['profile_public'] = $request->has('profile_public');

        // Handle array fields
        $data['interests'] = $request->input('interests', []);
        $data['skills'] = $request->input('skills', []);
        $data['preferred_causes'] = $request->input('preferred_causes', []);
        
        // Handle social links
        $socialLinks = [];
        if ($request->filled('social_links.facebook')) {
            $socialLinks['facebook'] = $request->input('social_links.facebook');
        }
        if ($request->filled('social_links.twitter')) {
            $socialLinks['twitter'] = $request->input('social_links.twitter');
        }
        if ($request->filled('social_links.linkedin')) {
            $socialLinks['linkedin'] = $request->input('social_links.linkedin');
        }
        if ($request->filled('social_links.instagram')) {
            $socialLinks['instagram'] = $request->input('social_links.instagram');
        }
        $data['social_links'] = $socialLinks;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($profile->profile_picture && Storage::disk('public')->exists($profile->profile_picture)) {
                Storage::disk('public')->delete($profile->profile_picture);
            }
            
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('profile_pictures', $filename, 'public');
            $data['profile_picture'] = 'storage/' . $path;
        }

        $profile->update($data);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function updateProfilePicture(Request $request)
    {
        $user = Auth::user();
        $profile = Profile::where('email', $user->email)->first();
        
        if (!$profile) {
            return response()->json(['error' => 'Profile not found.'], 404);
        }

        // Validation for profile picture only
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($profile->profile_picture && Storage::disk('public')->exists(str_replace('storage/', '', $profile->profile_picture))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $profile->profile_picture));
            }
            
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('profile_pictures', $filename, 'public');
            
            $profile->update(['profile_picture' => 'storage/' . $path]);

            return response()->json([
                'success' => true, 
                'message' => 'Profile picture updated successfully!',
                'image_url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}
