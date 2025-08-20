@extends('layouts.user-layout')

@section('main')
<main>

    @if(session('success'))
        <p align="center" style="color: green; font-weight: bold;">
            {{ session('success') }}
        </p>
    @endif

    @if($photos->count())
        <table border="1" cellpadding="10" cellspacing="0" align="center">
            <tr>
            @foreach($photos as $index => $photo)
                <td align="center" >
                    <img src="{{ asset('storage/' . $photo->image_path) }}" 
                        width="150" height="150" 
                        style="border:1px solid #ccc; border-radius:5px;"><br><br>
                    <small><b>{{ $photo->caption }}</b></small><br>
                    <small>❤️ Likes: {{ $photo->likes_count }}</small><br><br>

                    {{-- Delete Button --}}
                    <form action="{{ route('photos.destroy', $photo->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this photo?')">
                            🗑️ Delete
                        </button>
                    </form>

                    @if($photo->comments->count())
                        <table >
                            @foreach($photo->comments->reverse() as $comment)
                                <tr>
                                    <td>
                                        @if($comment->user->profile_picture)
                                            <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" 
                                                alt="User Pic" width="20" height="20">
                                        @else
                                            <img src="https://via.placeholder.com/20" alt="User Pic">
                                        @endif
                                    </td>
                                    <td><strong>{{ $comment->user->name }}:</strong></td>
                                    <td>{{ $comment->content }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
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
</main>
@endsection