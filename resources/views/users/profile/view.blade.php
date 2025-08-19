<!DOCTYPE html>
<html>
<head><title>Profile</title></head>
<body>
    <h1 align="center">Welcome, {{ $user->name }}</h1>

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

                <a href="{{ route('profile.edit') }}">Edit Profile</a> <br><br>

                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </td>
        </tr>
    </table>

    <br>

    <h2 align="center">Your Pictures</h2>
    <div align="center">
        <a href="{{ route('photos.index') }}">📸 View Your Photos</a> | 
        <a href="{{ route('photos.create') }}">⬆️ Upload a New Photo</a>
    </div>

    <h2 align="center">Feed</h2>
    <div align="center">
        <a href="{{ route('feed.index') }}">Feed</a>
    </div>
</body>
</html>

{{-- <h3>Photo Grid</h3>
    <table>
        <tr>
        @foreach($photos as $index => $photo)
            <td>
                <img src="{{ asset('storage/' . $photo->image_path) }}" alt="Photo" width="100" height="100">
            </td>

            @if(($index + 1) % 3 == 0)
                </tr><tr>
            @endif
        @endforeach
        </tr>
    </table> --}}