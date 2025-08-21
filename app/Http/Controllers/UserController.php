<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        // check if viewer is authenticated and following
        $isFollower = false;
        if (auth()->check()) {
            $isFollower = $user->followers()
                            ->where('follower_id', auth()->id())
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