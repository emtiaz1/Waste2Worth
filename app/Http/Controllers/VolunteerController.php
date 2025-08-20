<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Volunteer;

class VolunteerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'tools' => 'nullable|string',
        ]);
        Volunteer::create($request->all());
        return response()->json(['success' => true]);
    }

    public function index()
    {
        $volunteers = Volunteer::all();
        return view('admin.volunteers', compact('volunteers'));
    }
}
