<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // Follow a user
    public function store($id)
    {
        $user = Auth::user();

        if ($user->id == $id) {
            return back()->with('error', 'You cannot follow yourself!');
        }

        $alreadyFollowing = Follow::where('follower_id', $user->id)
                                  ->where('followed_id', $id)
                                  ->exists();

        if (!$alreadyFollowing) {
            Follow::create([
                'follower_id' => $user->id,
                'followed_id' => $id,
            ]);
        }

        return back()->with('success', 'You are now following this user.');
    }

    // Unfollow a user
    public function destroy($id)
    {
        $user = Auth::user();

        Follow::where('follower_id', $user->id)
              ->where('followed_id', $id)
              ->delete();

        return back()->with('success', 'You unfollowed this user.');
    }
}