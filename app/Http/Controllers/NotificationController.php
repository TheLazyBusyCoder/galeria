<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index() {
        return view('users.notifications.index');
    }
    public function markRead(Request $req)
    {
        $user = Auth::user();

        // Ensure the notification belongs to this user
        $notification = $user->notifications()->where('id', $req->id)->firstOrFail();

        // Mark as read
        $notification->markAsRead();

        return back()->with('status', 'Notification marked as read.');
    }
}
