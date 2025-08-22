<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

// class JsonNotification extends Notification implements ShouldQueue
class JsonNotification extends Notification
{
    use Queueable;

    public string $type;
    public string $text;
    public ?int $fromUserId;
    public ?array $extra;

    /**
     * Create a new notification instance.
     *
     * @param string $type  (like, comment, follow, etc.)
     * @param string $text  (notification text)
     * @param int|null $fromUserId (who triggered this notification)
     * @param array|null $extra (any extra data like post_id, comment_id, etc.)
     */
    public function __construct(string $type, string $text, ?int $fromUserId = null, array $extra = [])
    {
        $this->type = $type;
        $this->text = $text;
        $this->fromUserId = $fromUserId;
        $this->extra = $extra;
    }

    /**
     * Notification delivery channels
     */
    public function via($notifiable): array
    {
        return ['database']; // You can add 'mail', 'broadcast' later if needed
    }

    /**
     * Store notification in database
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => $this->type,
            'text' => $this->text,
            'from_user_id' => $this->fromUserId,
            'extra' => $this->extra,
        ];
    }

}

/*
// like
$user->notify(new JsonNotification(
    type: 'like',
    text: $liker->name . ' liked your photo',
    fromUserId: $liker->id,
    extra: ['post_id' => $post->id]
));

// comment
$user->notify(new JsonNotification(
    type: 'comment',
    text: $commenter->name . ' commented on your photo',
    fromUserId: $commenter->id,
    extra: ['post_id' => $post->id, 'comment_id' => $comment->id]
));

// follows
$user->notify(new JsonNotification(
    type: 'follow',
    text: $follower->name . ' sent you a follow request',
    fromUserId: $follower->id,
));

*/