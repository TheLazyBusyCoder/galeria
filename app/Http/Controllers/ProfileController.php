<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $photos = $user->photos()->withCount('likes')->latest()->get(); // fetch user's photos
        return view('users.profile.view', compact('user' , 'photos'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('users.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->route('profile.view')->with('success', 'Profile updated!');
    }

    public function following($id)
    {
        $user = User::findOrFail($id);

        // Directly get the users this user follows (they are already User models)
        $followings = $user->followings()->get();

        return view('users.profile.following', compact('user', 'followings'));
    }

    public function followers($id)
    {
        $user = User::findOrFail($id);

        // Directly get the users who follow this user
        $followers = $user->followers()->get();

        return view('users.profile.followers', compact('user', 'followers'));
    }
}