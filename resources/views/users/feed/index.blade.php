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
                <td align="center" valign="top" >
                    <p>
                        <a href="{{ asset('storage/' . $photo->image_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $photo->image_path) }}" alt="Photo" width="120" height="120">
                        </a>
                    </p>

                    @if($photo->caption)
                    
                        <p><small>{{ $photo->caption }}</small></p>
                    @endif

                    <p>
                        @if($photo->user->profile_picture)
                            <img src="{{ asset('storage/' . $photo->user->profile_picture) }}" alt="User Pic" width="30" height="30">
                        @else
                            <a href="https://via.placeholder.com/30" target="_blank">
                                <img src="https://via.placeholder.com/30" alt="User Pic">
                            </a>
                        @endif
                        <small>
                            <strong>
                                <a href="{{ route('users.show', $photo->user->username) }}">{{ $photo->user->name }}</a>
                            </strong>
                        </small>
                    </p>


                    <form action="{{ route('photos.like', $photo->id) }}" method="POST">
                        @csrf
                        <div>
                            <small>{{ $photo->likes_count }} likes</small>
                            <button type="submit">Like</button>
                        </div>
                    </form>

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
