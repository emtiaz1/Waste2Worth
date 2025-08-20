<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->get();
        return view('admin.contactmsg', compact('messages'));
    }
}
