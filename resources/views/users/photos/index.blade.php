<!DOCTYPE html>
<html>
<head><title>Your Photos</title></head>
<body>
    <h1 align="center">📸 Your Photos</h1>

    <p align="center">
        <a href="{{ route('photos.create') }}">➕ Upload New Photo</a>
    </p>

    @if(session('success'))
        <p align="center" style="color: green; font-weight: bold;">
            {{ session('success') }}
        </p>
    @endif

    @if($photos->count())
        <table border="1" cellpadding="10" cellspacing="0" align="center">
            <tr>
            @foreach($photos as $index => $photo)
                <td align="center" style="padding:15px;">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" 
                         width="150" height="150" 
                         style="border:1px solid #ccc; border-radius:5px;"><br><br>
                    <small><b>{{ $photo->caption }}</b></small><br>
                    <small>❤️ Likes: {{ $photo->likes_count }}</small>
                </td>

                @if(($index + 1) % 8 == 0)
                    </tr><tr>
                @endif
            @endforeach
            </tr>
        </table>
    @else
        <p align="center"><i>No photos uploaded yet.</i></p>
    @endif

    <p align="center">
        <a href="{{ route('profile.view') }}">⬅ Back to Profile</a>
    </p>
</body>
</html>
