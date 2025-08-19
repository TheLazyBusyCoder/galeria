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
