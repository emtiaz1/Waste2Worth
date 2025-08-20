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
        
        // Create profile if it doesn't exist
        if (!$profile) {
            $profile = new Profile();
            $profile->email = $user->email;
            $profile->username = $user->name ?? 'User';
            $profile->save();
        }
        
        return view('profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'username' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'organization' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'social_links' => 'nullable|array',
            'interests' => 'nullable|array',
            'skills' => 'nullable|array',
            'achievements' => 'nullable|string|max:1000',
            'contribution' => 'nullable|string|max:1000',
            'total_token' => 'nullable|integer|min:0',
            'points_earned' => 'nullable|integer|min:0',
            'waste_reports_count' => 'nullable|integer|min:0',
            'community_events_attended' => 'nullable|integer|min:0',
            'volunteer_hours' => 'nullable|integer|min:0',
            'carbon_footprint_saved' => 'nullable|numeric|min:0',
            'preferred_causes' => 'nullable|array',
            'notification_preferences' => 'nullable|string|max:100',
            'email_notifications' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
            'profile_public' => 'nullable|boolean',
        ]);

        // Convert arrays to JSON for storage
        if (isset($validatedData['social_links'])) {
            $validatedData['social_links'] = json_encode($validatedData['social_links']);
        }
        if (isset($validatedData['interests'])) {
            $validatedData['interests'] = json_encode($validatedData['interests']);
        }
        if (isset($validatedData['skills'])) {
            $validatedData['skills'] = json_encode($validatedData['skills']);
        }
        if (isset($validatedData['preferred_causes'])) {
            $validatedData['preferred_causes'] = json_encode($validatedData['preferred_causes']);}
        // Update user's basic info if first_name and last_name are provided
        if (!empty($validatedData['first_name']) || !empty($validatedData['last_name'])) {
            $user->update([
                'name' => trim(($validatedData['first_name'] ?? '') . ' ' . ($validatedData['last_name'] ?? '')),
                'email' => $validatedData['email'],
            ]);
        }

        // Update or create profile
        $profile = Profile::where('email', $user->email)->first();
        
        if (!$profile) {
            $profile = new Profile();
            $profile->email = $user->email;
        }
        
        $profile->fill($validatedData);
        $profile->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function updateProfilePicture(Request $request)
    {
        try {
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user = Auth::user();
            \Log::info('User authenticated: ' . $user->email);
            
            $profile = Profile::where('email', $user->email)->first();
            \Log::info('Profile found: ' . ($profile ? 'Yes' : 'No'));
            
            // Create profile if it doesn't exist
            if (!$profile) {
                $profile = new Profile();
                $profile->email = $user->email;
                $profile->username = $user->name ?? 'User';
                $saved = $profile->save();
                \Log::info('Profile created: ' . ($saved ? 'Success' : 'Failed'));
            }

            if ($request->hasFile('profile_picture')) {
                \Log::info('File uploaded: ' . $request->file('profile_picture')->getClientOriginalName());
                
                // Delete old profile picture if exists
                if ($profile->profile_picture) {
                    Storage::disk('public')->delete($profile->profile_picture);
                    \Log::info('Old profile picture deleted: ' . $profile->profile_picture);
                }

                // Store new profile picture
                $fileName = time() . '_' . $request->file('profile_picture')->getClientOriginalName();
                $filePath = $request->file('profile_picture')->storeAs('profile_pictures', $fileName, 'public');
                \Log::info('New file stored at: ' . $filePath);
                
                $profile->profile_picture = $filePath;
                $saveResult = $profile->save();
                \Log::info('Profile picture field saved to database: ' . ($saveResult ? 'Success' : 'Failed'));
                \Log::info('Profile ID: ' . $profile->id);
                \Log::info('Profile picture in DB: ' . $profile->profile_picture);

                return response()->json([
                    'success' => true,
                    'message' => 'Profile picture updated successfully!',
                    'image_url' => Storage::url($filePath) . '?t=' . time()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No file uploaded.'
            ], 400);
            
        } catch (\Exception $e) {
            \Log::error('Profile picture upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading file: ' . $e->getMessage()
            ], 500);
        }
    }
}
