
@extends('layouts.user-layout')

@section('main')
<main>

    <table border="1" cellpadding="10" cellspacing="0" align="center">
        <tr>
            <td align="center" valign="middle">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                         width="120" height="120" alt="Profile Picture">
                @else
                    <p>No profile picture uploaded</p>
                @endif
            </td>
            <td valign="top">
                <b>Name:</b> {{ $user->name }} <br>
                <b>Email:</b> {{ $user->email }} <br><br>

                {{-- Followers / Following counts --}}
                <a href="{{ route('profile.followers', $user->id) }}">
                    <b>{{ $user->followers()->count() }}</b> Followers
                </a> | 
                <a href="{{ route('profile.following', $user->id) }}">
                    <b>{{ $user->followings()->count() }}</b> Following
                </a>
                <br><br>

                <a href="{{ route('profile.edit') }}">Edit Profile</a> <br><br>

                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </td>
        </tr>
    </table>

    <br>

    <x-photos-grid-view :photos="$photos" />

</main>
@endsection

