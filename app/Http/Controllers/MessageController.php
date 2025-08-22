<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Show all 1:1 conversations (inbox)
     */
    public function index()
    {
        $userId = Auth::id();

        // Get all users that current user has messages with
        $conversations = User::whereHas('receivedMessages', function ($q) use ($userId) {
            $q->where('sender_id', $userId);
        })->orWhereHas('sentMessages', function ($q) use ($userId) {
            $q->where('sender_id', '!=', $userId);
        })->get();

        return view('users.messages.index', compact('conversations'));
    }

    /**
     * Show messages between logged-in user and a specific user
     */
    public function conversation(User $user)
    {
        $authId = Auth::id();

        // Only allow if recipient is public OR sender is a follower
        if ($user->account_type === 'private' && !$user->followers()->where('follower_id', $authId)->exists()) {
            abort(403, 'You cannot message this user.');
        }

        // Fetch messages 1:1
        $messages = Message::where(function ($q) use ($authId, $user) {
            $q->where('sender_id', $authId)
              ->whereHas('recipients', fn($r) => $r->where('recipient_id', $user->id));
        })->orWhere(function ($q) use ($authId, $user) {
            $q->where('sender_id', $user->id)
              ->whereHas('recipients', fn($r) => $r->where('recipient_id', $authId));
        })->with('attachments', 'sender')->orderBy('created_at', 'asc')->get();

        return view('users.messages.conversation', compact('user', 'messages'));
    }

    /**
     * Send a new message
     */
    public function send(Request $request, User $user)
    {
        $authId = Auth::id();

        // Check permission
        if ($user->account_type === 'private' && !$user->followers()->where('follower_id', $authId)->exists()) {
            abort(403, 'You cannot message this user.');
        }

        $request->validate([
            'content' => 'required_without:attachment|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,png,gif,mp4,pdf|max:10240', // 10MB
        ]);

        // Create message
        $message = Message::create([
            'sender_id' => $authId,
            'type' => $request->hasFile('attachment') ? 'file' : 'text',
            'content' => $request->input('content'),
        ]);

        // Attach recipient
        $message->recipients()->attach($user->id);

        // Send notification to the photo owner
        $user->notify(new \App\Notifications\JsonNotification(
            type: 'message',
            text: Auth::user()->name . ' send you a message',
            fromUserId: $authId,
        ));

        // Optional: handle attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('messages', 'public');

            $message->attachments()->create([
                'type' => $file->getClientOriginalExtension() === 'mp4' ? 'video' : 'image',
                'url' => $path,
                'size' => $file->getSize(),
            ]);
        }

        return back()->with('success', 'Message sent!');
    }

    /**
     * Mark a message as read
     */
    public function markRead(Message $message)
    {
        $authId = Auth::id();

        $recipient = $message->recipients()->where('recipient_id', $authId)->first();

        if (!$recipient) {
            abort(403, 'Not authorized.');
        }

        $message->recipients()->updateExistingPivot($authId, ['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
