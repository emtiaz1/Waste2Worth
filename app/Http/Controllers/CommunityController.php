<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForumDiscussion;

class CommunityController extends Controller
{
    public function index()
    {
        $forumDiscussions = ForumDiscussion::orderBy('created_at', 'desc')->get();
        return view('community', compact('forumDiscussions'));
    }
}
