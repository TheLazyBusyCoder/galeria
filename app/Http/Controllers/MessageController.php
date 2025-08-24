<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\MessageRecipient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function fetch(User $user)
    {
        $authId = auth()->id();

        // Check permission: only fetch if allowed
        if ($user->account_type === 'private' && !$user->followers()->where('follower_id', $authId)->exists()) {
            abort(403, 'You cannot view this chat.');
        }

        // Fetch messages between logged-in user and the other user
        $messages = Message::where(function ($q) use ($authId, $user) {
                $q->where('sender_id', $authId)
                ->whereHas('recipients', fn($r) => $r->where('recipient_id', $user->id));
            })
            ->orWhere(function ($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)
                ->whereHas('recipients', fn($r) => $r->where('recipient_id', $authId));
            })
            ->with('attachments', 'sender') // include attachments and sender info
            ->orderBy('created_at', 'asc') // ascending order like conversation()
            ->get();

        return response()->json([
            'messages' => $messages
        ]);
    }

    /**
     * Show all 1:1 conversations (inbox)
     */
    public function index()
    {
        $userId = Auth::id();

        $conversations = User::whereHas('receivedMessages', function ($q) use ($userId) {
                // I sent them a message
                $q->where('sender_id', $userId);
            })
            ->orWhereHas('sentMessages', function ($q) use ($userId) {
                // They sent me a message -> check via pivot table
                $q->whereHas('recipients', function ($r) use ($userId) {
                    $r->where('recipient_id', $userId);
                });
            })
            ->get()
            ->map(function ($convUser) use ($userId) {
                // Add unread count dynamically
                $convUser->unread_count = Message::where('sender_id', $convUser->id)
                    ->whereHas('recipients', function ($q) use ($userId) {
                        $q->where('recipient_id', $userId)
                        ->whereNull('read_at');
                    })
                    ->count();

                return $convUser;
            });

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
        })->with('attachments', 'sender')->orderBy('created_at', 'desc')->get();

        // Update the inbox notfication
        auth()->user()->notifications()
            ->whereNull('read_at')
            ->where('data->type', 'message')
            ->where('data->from_user_id', $user->id)
            ->update(['read_at' => now()]);

        // ✅ Mark as read all messages received from this user
        DB::table('message_recipients')
            ->join('messages', 'message_recipients.message_id', '=', 'messages.id')
            ->where('messages.sender_id', $user->id)     // messages from that user
            ->where('message_recipients.recipient_id', $authId) // to me
            ->whereNull('message_recipients.read_at')
            ->update(['message_recipients.read_at' => now()]);

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

    // NEW: voice message
    public function sendVoice(Request $request, $userId)
    {
        $request->validate([
            'voice' => 'required|file|mimes:mp3,wav,ogg,webm|max:20480', // max 20MB
            'duration' => 'nullable|integer',
        ]);

        $path = $request->file('voice')->store('voice', 'public');

        // create message
        $message = Message::create([
            'sender_id' => auth()->id(),
            'type' => 'voice',
            'content' => null,
        ]);

        // create attachment
        MessageAttachment::create([
            'message_id' => $message->id,
            'type' => 'audio',
            'url' => $path,
            'size' => $request->file('voice')->getSize(),
            'metadata' => json_encode([
                'duration' => $request->duration,
                'format' => $request->file('voice')->getClientOriginalExtension(),
                'mime' => $request->file('voice')->getMimeType(),
            ]),
        ]);

        // link to recipient
        MessageRecipient::create([
            'message_id' => $message->id,
            'recipient_id' => $userId,
        ]);

        return response()->json(['success' => true, 'message_id' => $message->id]);
    }
}
