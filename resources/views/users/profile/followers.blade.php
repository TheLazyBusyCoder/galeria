@extends('layouts.user-layout')

@section('css')
<style>
    .followers-list {
        max-width: 500px;
        margin: 0 auto;
        padding: 1rem;
    }
    .followers-list h1 {
        text-align: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }
    .follower-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #ddd;
    }
    .follower-item img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }
    .follower-item a {
        text-decoration: none;
        color: var(--color-text);
        font-weight: 500;
    }
    .back-link {
        display: block;
        text-align: center;
        margin-top: 1rem;
        color: #555;
    }
</style>
@endsection

@section('main')
<div class="followers-list">
    <h1>Followers</h1>

    @forelse ($followers as $followerUser)
        <div class="follower-item">
            @if($followerUser->profile_picture)
                <a href="{{ route('users.show', $followerUser->username) }}">
                    <img src="{{ asset('storage/' . $followerUser->profile_picture) }}" alt="Profile Pic">
                </a>
            @else
                <div style="width:45px;height:45px;border-radius:50%;background:#ccc;display:flex;align-items:center;justify-content:center;font-size:0.75rem;">
                    No Pic
                </div>
            @endif

            <a href="{{ route('users.show', $followerUser->username) }}">
                {{ $followerUser->name }}
            </a>
        </div>
    @empty
        <p style="text-align:center; color:#777;">There are no followers</p>
    @endforelse

    <a href="{{ route('profile.view') }}" class="link">⬅ Back to Profile</a>
</div>
@endsection
