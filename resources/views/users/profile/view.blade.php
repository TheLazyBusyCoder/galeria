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
<div class="profile">
    <div class="profile-header">
        <div class="profile-picture">
            @if($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">
            @else
                <p>No profile picture uploaded</p>
            @endif
        </div>
        <div class="profile-info">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Account:</strong> {{ ucfirst($user->account_type) }}</p>

            <div class="profile-stats">
                <a href="{{ route('profile.followers', $user->id) }}">
                    <strong>{{ $user->followers()->count() }}</strong> Followers
                </a>
                <a href="{{ route('profile.following', $user->id) }}">
                    <strong>{{ $user->followings()->count() }}</strong> Following
                </a>
            </div>

            <a href="{{ route('profile.edit') }}" class="link">Edit Profile</a>
        </div>
    </div>
    <x-photos-grid-view :photos="$photos" />
</div>
@endsection
