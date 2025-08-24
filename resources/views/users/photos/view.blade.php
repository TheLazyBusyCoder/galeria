@extends('layouts.user-layout')

@section('css')
<style>
.photo-container {
    max-width: 1000px;
    margin: 20px auto;
    display: flex;
    gap: 20px;
    min-height: 500px;
    padding: 15px;
    box-shadow: 0 2px 6px var(--color-shadow);
}

.photo-side {
    flex: 1;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

/* Fixed container for main photo */
.main-photo-container {
    width: 250px;
    height: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

/* Image inside the fixed box */
.main-photo {
    width: 100%;
    height: 100%;
    object-fit: cover; /* change to cover if you want it cropped */
}

.caption {
    font-style: italic;
    color: var(--color-text-muted);
}

.user-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
}

.user-info img {
    border-radius: 50%;
}

.comments-side {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.comments {
    flex: 1;
    overflow-y: auto;
    max-height: 300px;
    width: 450px;
    border: 1px solid var(--color-border);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.comment {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--color-border);
}

.comment:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.comment img {
    border-radius: 50%;
    flex-shrink: 0;
}

.comment-content small {
    color: #888;
    font-size: 12px;
}

.comment-form {
    border: 1px solid var(--color-border);
    border-radius: 8px;
    padding: 15px;
}

.like-button {
    background: none;
    color: rgb(168, 1, 1);

}
.like-button:hover {
    background: none;
    color: red;

}

</style>
@endsection

@section('main')
    <div class="photo-container">
        {{-- Photo Side --}}
        <div class="photo-side">

            <div class="user-info">
                @if($photo->user->profile_picture)
                    <img src="{{ asset('storage/' . $photo->user->profile_picture) }}" alt="User Pic" width="40" height="40">
                @else
                    <img src="https://via.placeholder.com/40" alt="User Pic" width="40" height="40">
                @endif
                <strong>
                    <a href="{{ route('users.show', $photo->user->username) }}" class="link">
                        {{ $photo->user->name }}
                    </a>
                </strong>
            </div>

            <div class="main-photo-container">
                <a href="{{ route('photos.view', ['photo_id' => $photo->id]) }}" class="link">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" alt="Photo" class="main-photo">
                </a>
            </div>
            
            @if($photo->caption)
                <p class="caption">{{ $photo->caption }}</p>
            @endif
            @if(!$photo->likes->contains('user_id', auth()->id()))
                <form action="{{ route('photos.like', $photo->id) }}" method="POST">
                    @csrf
                    <p>
                        <small>{{ $photo->likes_count }} likes</small><br>
                        <button class="like-button" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/></svg>
                        </button>
                    </p>
                </form>
            @else
                {{ $photo->likes_count }} Likes
            @endif
        </div>

        {{-- Comments Side --}}
        <div class="comments-side">
            {{-- Comments Display --}}
            @if($photo->comments->count())
                <div class="comments">
                    @foreach($photo->comments->reverse() as $comment)
                        <div class="comment">
                            @if($comment->user->profile_picture)
                                <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" alt="User Pic" width="30" height="30">
                            @else
                                <img src="https://via.placeholder.com/30" alt="User Pic" width="30" height="30">
                            @endif
                            <div class="comment-content">
                                <a class="link" href="{{route('users.show' , $comment->user->username)}}"><strong>{{ $comment->user->name }}</strong></a>
                                <small>{{ $comment->created_at->setTimezone('Asia/Kolkata')->format('M j, g:i A') }}</small>
                                <p>{{ $comment->content }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="comments">
                    <p style="text-align: center; color: #888;">No comments yet. Be the first to comment!</p>
                </div>
            @endif

            {{-- Comment Form --}}
            <div class="comment-form">
                <form action="{{ route('photos.comment', $photo->id) }}" method="POST">
                    @csrf
                    <input name="content" style="margin-bottom: 10px;" required placeholder="Add a comment..."  />
                    <button type="submit">Post Comment</button>
                </form>
            </div>
        </div>
    </div>
@endsection