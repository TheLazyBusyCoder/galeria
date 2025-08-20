
@extends('layouts.user-layout')

@section('main')
<main>
    <h1 align="center">{{ $user->name }}'s Followers</h1>

    <table border="1" cellpadding="10" cellspacing="0" align="center">
        <tr>
            <th>Profile Picture</th>
            <th>Name</th>
        </tr>
        @forelse ($followers as $followerUser)
            <tr>
                <td align="center">
                    @if($followerUser->profile_picture)
                        <a href="{{ route('users.show', $followerUser->username) }}">
                            <img src="{{ asset('storage/' . $followerUser->profile_picture) }}" 
                                alt="Profile Pic" width="50" height="50">
                        </a>
                    @else
                        <p>No Pic</p>
                    @endif
                </td>
                <td align="center">
                    <a href="{{ route('users.show', $followerUser->username) }}">
                        {{ $followerUser->name }}
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2">There are no followers</td>
            </tr>
        @endforelse
    </table>

    <br>
    <div align="center">
        <a href="{{ route('profile.view') }}">⬅ Back to Profile</a>
    </div>
</main>
@endsection

