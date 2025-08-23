@extends('layouts.user-layout')

@section('css')
<style>

.convo-list {
    max-width: 500px;
    margin: 0 auto;
    padding: 1rem;
}

.convo-list h1 {
    text-align: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    color: var(--color-text);
}

.convo-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
    border-bottom: 1px solid var(--color-divider);
    background: var(--color-surface);
    transition: background 0.2s;
}

.convo-item img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid var(--color-border);
}

.convo-item a {
    text-decoration: none;
    color: var(--color-text);
    font-weight: 500;
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 1rem;
    color: var(--color-text-muted);
}

.notification-count {
    background: red;
    color: var(--color-text-inverse);
    font-size: 0.7rem;
    font-weight: bold;
    padding: 5px 8px;
    border-radius: 50%;
}
</style>

@endsection

@section('main')
        <div class="convo-list">
            <h1>Inbox</h1>
            @forelse($conversations as $convUser)
                <div class="convo-item">
                    @if($convUser->profile_picture)
                        <a href="{{ route('messages.conversation', $convUser->id) }}">
                            <img src="{{ asset('storage/' . $convUser->profile_picture) }}" alt="Profile Pic">
                        </a>
                    @else
                        <div class="no-avatar">No Pic</div>
                    @endif
                    <a href="{{ route('messages.conversation', $convUser->id) }}" style="position: relative;">
                        {{ $convUser->name }}
                        @if($convUser->unread_count > 0)
                            <span class="notification-count">{{ $convUser->unread_count ?? 0 }}</span>
                        @endif
                    </a>
                </div>
            @empty
                <p style="text-align:center; color:#777;">No conversations yet.</p>
            @endforelse
        </div>
@endsection

@section('js')
@endsection
