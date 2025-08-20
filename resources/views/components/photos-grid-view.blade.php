@props(['photos'])

@if($photos->count())
<div class="photo-grid">
    @foreach($photos as $photo)
        <div class="photo-card">

            <!-- Photo -->
            <a href="{{ route('photos.view', ['photo_id' => $photo->id]) }}">
                <img src="{{ asset('storage/' . $photo->image_path) }}" 
                     alt="Photo" class="photo-img">
            </a>

            <!-- Caption -->
            @if($photo->caption)
                <p class="caption">
                    {{ $photo->caption }} 
                    <span class="timestamp">
                        • {{ $photo->created_at->setTimezone('Asia/Kolkata')->diffForHumans() }}
                    </span>
                </p>
            @endif

            <!-- User -->
            <div class="photo-user">
                @if($photo->user->profile_picture)
                    <img src="{{ asset('storage/' . $photo->user->profile_picture) }}" 
                         alt="User Pic" class="user-pic">
                @else
                    <img src="https://via.placeholder.com/30" alt="User Pic" class="user-pic">
                @endif

                <a href="{{ route('users.show', $photo->user->username) }}" class="username">
                    {{ $photo->user->name }}
                </a>
            </div>

            <!-- Likes + Comments -->
            <div class="photo-meta">
                <span>{{ $photo->likes_count }} likes</span>
                <span>{{ $photo->comments->count() }} comments</span>
            </div>

            <!-- Like Form -->
            {{-- <form action="{{ route('photos.like', $photo->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn">View</button>
            </form>

            <a href="{{route('photos.view' , $photo->id)}}" class="link">View</a> --}}

        </div>
    @endforeach
</div>
@else
    <p class="no-photos">No photos yet.</p>
@endif
