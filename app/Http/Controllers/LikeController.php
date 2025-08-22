<?php

namespace App\Http\Controllers;

use App\Models\PhotoModel;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // Like a photo
    public function store(PhotoModel $photo)
    {
        $user = auth()->user();

        // prevent duplicate likes
        if (!$photo->likes()->where('user_id', $user->id)->exists()) {
            $photo->likes()->create(['user_id' => $user->id]);

            // Send notification to the photo owner
            if ($photo->user_id !== $user->id) { // don't notify self
                $photo->user->notify(new \App\Notifications\JsonNotification(
                    type: 'like',
                    text: $user->name . ' liked your photo',
                    fromUserId: $user->id,
                    extra: ['post_id' => $photo->id]
                ));
            }
        }

        return back()->with('success', 'Photo liked!');
    }

    // Unlike a photo
    public function destroy(PhotoModel $photo)
    {
        $user = auth()->user();

        $photo->likes()->where('user_id', $user->id)->delete();

        return back()->with('success', 'Photo unliked!');
    }
}
