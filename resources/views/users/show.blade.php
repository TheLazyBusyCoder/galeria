<!DOCTYPE html>
<html>
<head><title>{{ $user->name }}'s Profile</title></head>
<body>
    <center>
        <h1>{{ $user->name }}</h1>

        <p>
            @if($user->profile_picture)
                <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile Pic" width="100" height="100">
            @else
                <img src="https://via.placeholder.com/100" alt="Profile Pic">
            @endif
        </p>

        @php
            $previous = url()->previous();
            $current = url()->current();
        @endphp

        <a href="{{ $previous !== $current ? $previous : route('feed.index') }}">
            ⬅ Back
        </a>

        {{-- Follow / Unfollow button --}}
        @auth
            @if(Auth::id() !== $user->id) {{-- don’t show button on own profile --}}
                @if(Auth::user()->followings->contains($user->id))
                    <form action="{{ route('follow.destroy', $user->id) }}" method="POST" style="margin-top:10px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Unfollow</button>
                    </form>
                @else
                    <form action="{{ route('follow.store', $user->id) }}" method="POST" style="margin-top:10px;">
                        @csrf
                        <button type="submit">Follow</button>
                    </form>
                @endif
            @endif
        @endauth

        <p>
            <strong>{{ $user->followers()->count() }}</strong> Followers |
            <strong>{{ $user->followings()->count() }}</strong> Following
        </p>

        <h2>Photos</h2>
        <table border="0" cellpadding="10" cellspacing="0">
            <tr>
            @foreach($photos as $index => $photo)
                <td align="center" valign="top">
                    <img src="{{ Storage::url($photo->image_path) }}" alt="Photo" width="120" height="120"><br>
                    
                    @if($photo->caption)
                        <small>{{ $photo->caption }}</small><br>
                    @endif
                    
                    <form action="{{ route('photos.like', $photo->id) }}" method="POST">
                        @csrf
                        <div>
                            <small>{{ $photo->likes_count }} likes</small>
                            <button type="submit">Like</button>
                        </div>
                    </form>
                </td>

                @if(($index + 1) % 5 == 0)
                    </tr><tr>
                @endif
            @endforeach
            </tr>
        </table>
    </center>
</body>
</html>
