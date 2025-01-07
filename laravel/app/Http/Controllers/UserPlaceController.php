<?php

namespace App\Http\Controllers;

use App\Models\UserPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPlaceController extends Controller
{
    public function index()
    {
        $places = Auth::user()->places;
        return view('user_places.index', compact('places'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Auth::user()->places()->create(['name' => $request->name]);

        return redirect()->route('user_places.index')->with('success', 'City added successfully.');
    }

    public function destroy(UserPlace $place)
    {
        $this->authorize('delete', $place);

        $place->delete();

        return redirect()->route('user_places.index')->with('success', 'City removed successfully.');
    }
}