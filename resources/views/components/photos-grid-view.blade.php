@props(['photos' ])
@if($photos->count())
    <table border="1" cellspacing="0" align="center" cellpadding="10">
        <tr>
        @foreach($photos as $index => $photo)

            <td align="center" valign="top" >
                <!-- Photo -->
                <p>
                    <a href="{{route('photos.view' , ['photo_id' => $photo->id])}}">
                        <img src="{{ asset('storage/' . $photo->image_path) }}" alt="Photo" width="120" height="120">
                    </a>
                </p>

                <!-- Caption -->
                @if($photo->caption)
                    <p><small>{{ $photo->caption }} - {{ $photo->created_at->setTimezone('Asia/Kolkata')->diffForHumans() }}</small></p>
                @endif

                <!-- Posted by + Timestamp -->
                <p>
                    @if($photo->user->profile_picture)
                        <img src="{{ asset('storage/' . $photo->user->profile_picture) }}" alt="User Pic" width="30" height="30">
                    @else
                        <img src="https://via.placeholder.com/30" alt="User Pic">
                    @endif

                    <small>
                        <strong>
                            <a href="{{ route('users.show', $photo->user->username) }}">{{ $photo->user->name }}</a>
                        </strong>
                    </small>
                    <br>
                </p>

                <!-- Likes -->
                <form action="{{ route('photos.like', $photo->id) }}" method="POST">
                    @csrf
                    <div>
                        <small>{{ $photo->likes_count }} likes</small>
                        <button type="submit">Like</button>
                    </div>
                </form>

                <!-- Comment Form -->
                <form action="{{ route('photos.comment', $photo->id) }}" method="POST">
                    @csrf
                    <div>
                        <textarea name="content" required placeholder="Type something"></textarea>
                        <button type="submit">Post</button>
                    </div>
                </form>

                <!-- Comments -->
                @if($photo->comments->count())
                    <table border="0" width="100%">
                        @foreach($photo->comments->reverse() as $comment)
                            <tr valign="top">
                                <td width="25">
                                    @if($comment->user->profile_picture)
                                        <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" 
                                                alt="User Pic" width="20" height="20">
                                    @else
                                        <img src="https://via.placeholder.com/20" alt="User Pic">
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $comment->user->name }}</strong> 
                                    {{ $comment->content }} 
                                    - <small>{{ $comment->created_at->setTimezone('Asia/Kolkata')->diffForHumans() }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif

            </td>

            @if(($index + 1) % 4 == 0)
                </tr><tr>
            @endif
        @endforeach
        </tr>
    </table>
@else
    <p align="center">No photos yet.</p>
@endif