<!DOCTYPE html>
<html>
<head><title>{{ $user->name }} - Following</title></head>
<body>
    <h1 align="center">{{ $user->name }} is Following</h1>

    <table border="1" cellpadding="10" cellspacing="0" align="center">
        <tr>
            <th>Profile Picture</th>
            <th>Name</th>
        </tr>
        @foreach($followings as $followingUser)
        <tr>
            <td align="center">
                @if($followingUser->profile_picture)
                    <a href="{{ route('users.show', $followingUser->username) }}">
                        <img src="{{ asset('storage/' . $followingUser->profile_picture) }}" 
                             alt="Profile Pic" width="50" height="50">
                    </a>
                @else
                    <p>No Pic</p>
                @endif
            </td>
            <td align="center">
                <a href="{{ route('users.show', $followingUser->username) }}">
                    {{ $followingUser->name }}
                </a>
            </td>
        </tr>
        @endforeach
    </table>

    <br>
    <div align="center">
        <a href="{{ route('profile.view', $user->id) }}">⬅ Back to Profile</a>
    </div>
</body>
</html>
