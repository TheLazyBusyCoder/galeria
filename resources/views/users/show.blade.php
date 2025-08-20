
@extends('layouts.user-layout')

@section('main')
<main>
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

        <x-photos-grid-view :photos="$photos" />
    </center>
</main>
@endsection

