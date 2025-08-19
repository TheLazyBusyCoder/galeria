<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($username)
    {
        // Find user by username
        $user = User::where('username', $username)->firstOrFail();

        // Load their photos with likes
        $photos = $user->photos()->withCount('likes')->latest()->get();

        return view('users.show', compact('user', 'photos'));
    }
}