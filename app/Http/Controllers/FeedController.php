<?php

namespace App\Http\Controllers;

use App\Models\PhotoModel;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    // Show feed (all users' photos ordered by latest)
    public function index()
    {
        $photos = PhotoModel::with('user') // eager load user for name/profile pic
                        ->withCount('likes') // count likes
                        ->latest()
                        ->paginate(20); // paginate

        return view('users.feed.index', compact('photos'));
    }
}