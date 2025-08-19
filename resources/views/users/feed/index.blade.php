<!DOCTYPE html>
<html>
<head>
    <title>Feed</title>
</head>
<body>
    <h1 align="center">📸 Feed</h1>

    <div align="center">
        <a href="{{ route('profile.view') }}">Profile</a>
    </div>

    @if($photos->count())
        <table border="0" cellpadding="10" cellspacing="0" align="center">
            <tr>
            @foreach($photos as $index => $photo)
                <td align="center" valign="top">
                    <p>
                        @if($photo->user->profile_picture)
                            <img src="{{ asset('storage/' . $photo->user->profile_picture) }}" alt="User Pic" width="30" height="30">
                        @else
                            <img src="https://via.placeholder.com/30" alt="User Pic">
                        @endif
                        <br>
                        <small><strong>{{ $photo->user->name }}</strong></small>
                    </p>

                    <p>
                        <img src="{{ asset('storage/' . $photo->image_path) }}" alt="Photo" width="120" height="120">
                    </p>

                    @if($photo->caption)
                        <p><small>{{ $photo->caption }}</small></p>
                    @endif

                    <form action="{{ route('photos.like', $photo->id) }}" method="POST">
                        @csrf
                        <button type="submit">Like</button>
                    </form>

                    <p><small>{{ $photo->likes_count }} likes</small></p>
                </td>

                @if(($index + 1) % 8 == 0)
                    </tr><tr>
                @endif
            @endforeach
            </tr>
        </table>
    @else
        <p align="center">No photos yet.</p>
    @endif

    <p align="center">
        {{ $photos->links() }}
    </p>
</body>
</html>
