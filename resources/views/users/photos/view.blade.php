<!DOCTYPE html>
<html>
<head>
    <title>View</title>
</head>
<body>
    <center>
        <h1 align="center">📸 View</h1>
        @php
            $previous = url()->previous();
            $current = url()->current();
        @endphp

        <a align="center" href="{{ $previous !== $current ? $previous : route('feed.index') }}">
            ⬅ Back
        </a>
    </center>
    <br>

    <table border="1" cellspacing="0" cellpadding="10" align="center" width="80%">
        <tr>
            <!-- LEFT SIDE: BIG PHOTO -->
            <td align="center" valign="top" width="60%">
                <a href="{{ route('photos.view' , ['photo_id' => $photo->id]) }}">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" alt="Photo" width="400" height="400">
                </a>

                @if($photo->caption)
                    <p><small><i>{{ $photo->caption }}</i></small></p>
                @endif
            </td>

            <!-- RIGHT SIDE: USER INFO + INTERACTIONS -->
            <td valign="top" width="40%">
                <!-- USER INFO -->
                <p>
                    @if($photo->user->profile_picture)
                        <img src="{{ asset('storage/' . $photo->user->profile_picture) }}" 
                             alt="User Pic" width="40" height="40">
                    @else
                        <img src="https://via.placeholder.com/40" alt="User Pic">
                    @endif
                    <strong>
                        <a href="{{ route('users.show', $photo->user->username) }}">
                            {{ $photo->user->name }}
                        </a>
                    </strong>
                </p>

                <!-- LIKES -->
                <form action="{{ route('photos.like', $photo->id) }}" method="POST">
                    @csrf
                    <p>
                        <small>{{ $photo->likes_count }} likes</small><br>
                        <button type="submit">❤️ Like</button>
                    </p>
                </form>

                <!-- COMMENT BOX -->
                <form action="{{ route('photos.comment', $photo->id) }}" method="POST">
                    @csrf
                    <p>
                        <textarea name="content" required placeholder="Type something" rows="2" cols="30"></textarea><br>
                        <button type="submit">💬 Post</button>
                    </p>
                </form>

                <!-- COMMENTS -->
                @if($photo->comments->count())
                    <table border="0" width="100%">
                        @foreach($photo->comments->reverse() as $comment)
                            <tr valign="top">
                                <td width="30">
                                    @if($comment->user->profile_picture)
                                        <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" 
                                            alt="User Pic" width="25" height="25">
                                    @else
                                        <img src="https://via.placeholder.com/25" alt="User Pic">
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $comment->user->name }}</strong> 
                                    <small>🕒 {{ $comment->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</small><br>
                                    {{ $comment->content }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </td>
        </tr>
    </table>

</body>
</html>
