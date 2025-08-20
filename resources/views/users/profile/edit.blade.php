@extends('layouts.user-layout')

@section('css')
<style>
    .heading {
        margin-bottom: 1.5rem;
        color: var(--color-primary);
    }

    .text {
        margin-top: 1rem;
        color: var(--color-text-muted);
        font-size: 0.9rem;
    }
</style>
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h1 class="heading">Edit Profile</h1>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf

                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                        alt="Profile Picture" width="100" height="100">
                    <small style="display:block;text-align:center;">Current Picture</small>
                @else
                    <p style="text-align:center;">No profile picture uploaded</p>
                @endif

                <input type="file" name="profile_picture" style="margin-top: 10px;">

                <input placeholder="Name" type="text" name="name"  style="margin-top: 10px;" value="{{ old('name', $user->name) }}" required>

                <input placeholder="Email" type="email" name="email" style="margin-top: 10px;"  value="{{ old('email', $user->email) }}">

                <div>
                    <button type="submit">Update Profile</button>
                </div>

            </form>
        </div>
    </div>
@endsection
