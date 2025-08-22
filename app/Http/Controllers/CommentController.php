<?php

namespace App\Http\Controllers;

use App\Models\PhotoModel;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, PhotoModel $photo)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = $photo->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        // Send notification to photo owner
        $user = auth()->user();
        if ($photo->user_id !== $user->id) { // don't notify self
            $photo->user->notify(new \App\Notifications\JsonNotification(
                type: 'comment',
                text: $user->name . ' commented on your photo',
                fromUserId: $user->id,
                extra: ['post_id' => $photo->id, 'comment_id' => $comment->id]
            ));
        }

        return back()->with('success', 'Comment added!');
    }
}
