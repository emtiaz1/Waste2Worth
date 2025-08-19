<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForumDiscussion;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $forumDiscussions = ForumDiscussion::orderBy('created_at', 'desc')->get();
        return view('forum', compact('forumDiscussions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user = Auth::user();
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('forum_images', $imageName, 'public');
        }
        ForumDiscussion::create([
            'username' => $user->username ?? $user->name ?? 'Anonymous',
            'message' => $request->message,
            'image' => $imagePath,
        ]);
        return redirect()->route('forum.index');
    }
}
