<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        if($user->username == Auth::user()->username) {
            return redirect()->route('profile.view');
        }

        // check if viewer is authenticated and following
        $isFollower = false;
        if (auth()->check()) {
            $isFollower = $user->followers()
                ->where('follower_id', auth()->id())
                ->where('status', 'accepted')
                ->exists();
        }

        // Load photos only if public OR viewer is a follower
        $photos = collect(); // empty by default
        if ($user->account_type === 'public' || $isFollower) {
            $photos = $user->photos()->withCount('likes')->latest()->get();
        }

        return view('users.show', compact('user', 'photos', 'isFollower'));
    }
}