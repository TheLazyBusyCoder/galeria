<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Mark all unread notifications as read except type 'message'
        $user->notifications()
            ->whereNull('read_at')
            ->where('data->type', '!=', 'message')
            ->update(['read_at' => now()]);

        // Pass all notifications to the view
        $notifications = $user->notifications()->latest()->get();

        return view('users.notifications.index', compact('notifications'));
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
    public function count()
    {
        $user = Auth::user(); // get the currently logged-in user

        // count unread notifications excluding messages
        $unreadCount = $user->notifications()
            ->whereNull('read_at')
            ->where('data->type', '!=', 'message')
            ->count();

        // count unread message notifications
        $countInbox = $user->notifications()
            ->whereNull('read_at')
            ->where('data->type', 'message')
            ->count();

        // return JSON response
        return response()->json([
            'count' => $unreadCount,
            'inboxCount' => $countInbox,
        ]);
    }
}
