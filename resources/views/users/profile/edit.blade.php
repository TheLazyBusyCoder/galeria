<!DOCTYPE html>
<html>
<head><title>Edit Profile</title></head>
<body>
    <h1 align="center">Edit Profile</h1>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        <table border="1" cellpadding="10" cellspacing="0" align="center">
            <tr>
                <td align="center" valign="top" width="200">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                             alt="Profile Picture" width="120" height="120"><br>
                        <small>Current Picture</small>
                    @else
                        <p>No profile picture uploaded</p>
                    @endif

                    <p>
                        <label><b>Change Picture:</b></label><br>
                        <input type="file" name="profile_picture">
                    </p>
                </td>

                <td valign="top">
                    <p>
                        <label><b>Name:</b></label><br>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </p>
                    <p>
                        <label><b>Email:</b></label><br>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </p>

                    <p>
                        <button type="submit">Update Profile</button>
                    </p>
                    <p>
                        <a href="{{ route('profile.view') }}">⬅ Back to Profile</a>
                    </p>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
