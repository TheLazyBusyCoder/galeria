<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use App\Notifications\JsonNotification;
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

        $targetUser = User::findOrFail($id);

        $alreadyFollowing = Follow::where('follower_id', $user->id)
                                ->where('followed_id', $id)
                                ->exists();

        if (!$alreadyFollowing) {
            $status = $targetUser->account_type == 'private' ? 'pending' : 'accepted';

            $follow = Follow::create([
                'follower_id' => $user->id,
                'followed_id' => $id,
                'status' => $status,
            ]);

            // 🔔 Send follow notification
            $text = $status === 'pending'
                ? $user->name . ' sent you a follow request'
                : $user->name . ' started following you';

            $targetUser->notify(new JsonNotification(
                type: 'follow',
                text: $text,
                fromUserId: $user->id,
                extra: ['follow_id' => $follow->id] // 👈 store follow_id
            ));
        }

        return back()->with(
            'success', 
            $targetUser->account_type 
                ? 'Follow request sent.' 
                : 'You are now following this user.'
        );
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

    public function accept($id)
    {
        $user = Auth::user(); // the one being followed
        $follow = Follow::where('id', $id)
                        ->where('followed_id', $user->id) // ensure it's their request
                        ->where('status', 'pending')
                        ->firstOrFail();

        $follow->update(['status' => 'accepted']);

        // 🔔 Send notification back to follower
        $follower = $follow->follower;
        $follower->notify(new JsonNotification(
            type: 'follow_accept',
            text: $user->name . ' accepted your follow request',
            fromUserId: $user->id
        ));

        return back()->with('success', 'Follow request accepted.');
    }

    public function reject($id)
    {
        $user = Auth::user();
        $follow = Follow::where('id', $id)
                        ->where('followed_id', $user->id)
                        ->where('status', 'pending')
                        ->firstOrFail();

        $follower = $follow->follower;

        $follow->delete();

        // 🔔 Optional: send rejection notification
        $follower->notify(new JsonNotification(
            type: 'follow_reject',
            text: $user->name . ' rejected your follow request',
            fromUserId: $user->id
        ));

        return back()->with('success', 'Follow request rejected.');
    }
}