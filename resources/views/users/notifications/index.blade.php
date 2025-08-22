@extends('layouts.user-layout')

@section('css')
<style>
    .notifications {
        max-width: 800px;
        margin: 1rem auto;
        background: var(--color-surface-alt);
        border: 1px solid var(--color-border);
        border-radius: 8px;
    }

    .notification {
        padding: 1rem;
        border-bottom: 1px solid var(--color-divider);
        color: var(--color-text);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification:last-child {
        border-bottom: none;
    }

    .notification.unread {
        background: var(--color-surface);
        font-weight: 500;
    }

    .notification small {
        display: block;
        color: var(--color-text-muted);
        margin-top: 0.25rem;
        font-size: 0.8rem;
    }
    .actions {
        display: flex;
        justify-content: end;
        align-items: center;
        gap: 15px;
    }

    .button {
        padding: 5px;
        border-radius: 0px;
    }

    .left-box {
        display: flex;
        gap: 10px;
        justify-content: start;
        align-items: center;
    }

    .left-box img {
        border-radius: 50%;
        flex-shrink: 0;
    }

    .user-box {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }
</style>
@endsection

@section('main')
<div class="notifications">
    @forelse(auth()->user()->notifications as $notification)
        @php
            $data = $notification->data;
            // dd($data);
            $type = $data['type'] ?? null;
            $fromUserId = $data['from_user_id'] ?? null;
            $photoId = $data['extra']['post_id'] ?? null;
            $commentId = $data['comment_id'] ?? null;
        @endphp

        <div class="notification {{ $notification->read_at ? '' : 'unread' }}">
            {{-- Show actor --}}
            <div class="">
                <div class="left-box">
                    @if($fromUserId)
                        <div class="user-box">
                            <img src="{{ asset('storage/' . \App\Models\User::find($fromUserId)->profile_picture) }}" alt="User Pic" width="30" height="30">
                            <a class="link" href="{{ route('users.show', \App\Models\User::find($fromUserId)->username) }}">
                                {{ \App\Models\User::find($fromUserId)->name }}
                            </a>
                        </div>
                    @endif
                </div>
                <small>
                   {{ $data['text'] ?? 'Notification' }} • {{ $notification->created_at->diffForHumans() }}
                </small>
            </div>

            {{-- Action buttons --}}
            <div class="actions">
                @if(in_array($type, ['like', 'comment']))
                    @if (!$notification->read_at)
                        <form action="{{ route('notifications.read') }}" method="POST" style="display:inline;">
                            @csrf
                            {{-- Mark as read --}}
                            <input type="hidden" name="id" value="{{$notification->id}}">
                            <button class="button" type="submit">Mark as Read</button>
                        </form>
                    @endif

                    {{-- Redirect to post / comment --}}
                    @if($photoId)
                        <a href="{{ route('photos.view', $photoId) }}" class="link">View Post</a>
                    @endif
                @elseif($type === 'message')
                    @if (!$notification->read_at)
                        <form action="{{ route('notifications.read') }}" method="POST" style="display:inline;">
                            @csrf
                            {{-- Mark as read --}}
                            <input type="hidden" name="id" value="{{$notification->id}}">
                            <button class="button" type="submit">Mark as Read</button>
                        </form>
                    @endif
                @elseif($type === 'follow')
                    @php
                        $followId = $notification->data['extra']['follow_id'] ?? null;
                        $follow = $followId ? \App\Models\Follow::find($followId) : null;
                    @endphp

                    @if($follow && $follow->status === 'pending')
                        <form action="{{ route('follow.accept', $follow->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="button" type="submit">Accept</button>
                        </form>

                        <form action="{{ route('follow.reject', $follow->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="button" type="submit">Reject</button>
                        </form>
                    @elseif($follow && $follow->status === 'accepted')
                       @if (!$notification->read_at)
                            <form action="{{ route('notifications.read') }}" method="POST" style="display:inline;">
                                @csrf
                                {{-- Mark as read --}}
                                <input type="hidden" name="id" value="{{$notification->id}}">
                                <button class="button" type="submit">Mark as Read</button>
                            </form>
                       @endif
                    @elseif($follow && $follow->status === 'rejected')
                        <form action="{{ route('notifications.read') }}" method="POST" style="display:inline;">
                            @csrf
                            {{-- Mark as read --}}
                            <input type="hidden" name="id" value="{{$notification->id}}">
                            <button class="button" type="submit">Mark as Read</button>
                        </form>
                    @else
                        <form action="{{ route('notifications.read') }}" method="POST" style="display:inline;">
                            @csrf
                            {{-- Mark as read --}}
                            <input type="hidden" name="id" value="{{$notification->id}}">
                            <button class="button" type="submit">Mark as Read</button>
                        </form>
                    @endif
                @elseif ('follow_accept')
                @if (!$notification->read_at)
                    <form action="{{ route('notifications.read') }}" method="POST" style="display:inline;">
                        @csrf
                        {{-- Mark as read --}}
                        <input type="hidden" name="id" value="{{$notification->id}}">
                        <button class="button" type="submit">Mark as Read</button>
                    </form>
                @endif
                @elseif ('follow_rejected')
                    @if (!$notification->read_at)
                        <form action="{{ route('notifications.read') }}" method="POST" style="display:inline;">
                            @csrf
                            {{-- Mark as read --}}
                            <input type="hidden" name="id" value="{{$notification->id}}">
                            <button class="button" type="submit">Mark as Read</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <div class="notification">
            No notifications yet.
        </div>
    @endforelse
</div>
@endsection

@section('js')
@endsection
