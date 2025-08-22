@extends('layouts.user-layout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/photos-grid.css') }}">
<style>
    .profile-header {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        gap: 2rem;
        padding: 1.5rem;
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: 8px;
    }

    .profile-picture img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--color-border);
    }

    .profile-info p {
        margin: 0.5rem 0;
    }

    .profile-stats {
        margin: 1rem 0;
        display: flex;
        gap: 1.5rem;
    }

    .profile-stats a {
        color: var(--color-text);
        text-decoration: none;
    }
</style>
@endsection

@section('main')
<div class="profile-container">
    <div class="profile-header">
        {{-- Profile Picture --}}
        <div class="profile-picture">
            @if($user->profile_picture)
                <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile Picture">
            @else
                <img src="https://via.placeholder.com/120" alt="Profile Picture">
            @endif
        </div>

        {{-- Profile Info --}}
        <div class="profile-info">
            <h1>{{ $user->name }}</h1>
            <p>{{ucfirst($user->account_type)}} Account</p>

            {{-- Stats --}}
            <div class="profile-stats">
                <a>
                    <strong>{{ $user->followers()->where('status', 'accepted')->count() }}</strong> Followers
                </a>
                <a>
                    <strong>{{ $user->followings()->where('status', 'accepted')->count() }}</strong> Following
                </a>
            </div>

            {{-- Actions --}}
            <div class="profile-actions">
                @php
                    $previous = url()->previous();
                    $current = url()->current();

                    $authUser = Auth::user();
                    $followRecord = null;

                    if($authUser && $authUser->id !== $user->id) {
                        $followRecord = \App\Models\Follow::where('follower_id', $authUser->id)
                                    ->where('followed_id', $user->id)
                                    ->first();
                    }
                @endphp

                @auth
                    @if(Auth::id() !== $user->id) {{-- don’t show button on own profile --}}
                        @if(!$followRecord)
                            {{-- Not following yet --}}
                            <form action="{{ route('follow.store', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-follow">Follow</button>
                            </form>
                        @elseif($followRecord->status === 'accepted')
                            {{-- Already following --}}
                            <form action="{{ route('follow.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-unfollow">Unfollow</button>
                            </form>
                            {{-- Redirect to conversation --}}
                            <a href="{{ route('messages.conversation', $user->id) }}" class="link">Message</a>
                        @elseif($followRecord->status === 'pending')
                            {{-- Request sent --}}
                            Request Sent, Please wait
                        @endif
                    @endif
                @endauth
            </div>
        </div>
    </div>

    {{-- Photos Grid --}}
    @if ($user->account_type === 'public' || $isFollower)
        <x-photos-grid-view :photos="$photos" />
    @else
        <p style="text-align:center; color:#777; margin:1rem 0;">
            This account is private. Follow {{ $user->name }} to see their photos.
        </p>
    @endif
</div>
@endsection
