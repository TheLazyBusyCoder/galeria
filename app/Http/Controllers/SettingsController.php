<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        return view('users.settings.index', compact('user'));
    }

    public function updateTheme(Request $request)
    {
        $request->merge([
            'theme' => $request->has('theme') ? 'dark' : 'light'
        ]);

        // $request->validate([
        //     'theme' => 'required|in:light,dark,system',
        // ]);

        $user = Auth::user();
        $user->update(['theme' => $request->theme]);

        return back()->with('success', 'Theme updated successfully!');
    }
}
