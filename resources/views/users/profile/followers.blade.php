<!DOCTYPE html>
<html>
<head><title>{{ $user->name }} - Followers</title></head>
<body>
    <h1 align="center">{{ $user->name }}'s Followers</h1>

    <table border="1" cellpadding="10" cellspacing="0" align="center">
        <tr>
            <th>Profile Picture</th>
            <th>Name</th>
        </tr>
        @foreach($followers as $followerUser)
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
        @endforeach
    </table>

    <br>
    <div align="center">
        <a href="{{ route('profile.view', $user->id) }}">⬅ Back to Profile</a>
    </div>
</body>
</html>
