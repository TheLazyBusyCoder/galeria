@extends('layouts.user-layout')

@section('css')
<style>
    .following-list {
        max-width: 500px;
        margin: 0 auto;
        padding: 1rem;
    }
    .following-list h1 {
        text-align: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }
    .following-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #ddd;
    }
    .following-item img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }
    .following-item a {
        text-decoration: none;
        color: #333;
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
<div class="following-list">
    <h1>Following</h1>

    @forelse ($followings as $followingUser)
        <div class="following-item">
            @if($followingUser->profile_picture)
                <a href="{{ route('users.show', $followingUser->username) }}">
                    <img src="{{ asset('storage/' . $followingUser->profile_picture) }}" alt="Profile Pic">
                </a>
            @else
                <div style="width:45px;height:45px;border-radius:50%;background:#ccc;display:flex;align-items:center;justify-content:center;font-size:0.75rem;">
                    No Pic
                </div>
            @endif

            <a href="{{ route('users.show', $followingUser->username) }}">
                {{ $followingUser->name }}
            </a>
        </div>
    @empty
        <p style="text-align:center; color:#777;">Not following anyone</p>
    @endforelse

    <a href="{{ route('profile.view', $user->id) }}" class="back-link">⬅ Back to Profile</a>
</div>
@endsection
