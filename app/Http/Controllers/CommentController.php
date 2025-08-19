<?php

namespace App\Http\Controllers;

use App\Models\PhotoModel;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Store a comment
    public function store(Request $request, PhotoModel $photo)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $photo->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comment added!');
    }
}
