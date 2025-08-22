@extends('layouts.user-layout')

@section('css')
<style>
    .filter-bar {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 15px;
    }
    .button { 
        width: 50px;
        border-radius: 0;
        padding: 5px;
    }

    input {
        width: 300px;
        border-radius: 0;
    }
</style>
<style>
    .user-list {
        max-width: 600px;
        margin: 10px auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .user-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        border-bottom: 1px solid var(--color-border);
    }
    .user-item img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .user-item a {
        text-decoration: none;
        color: var(--color-text);
        font-weight: 500;
    }

    /* pagination */
    .pager {
        display:flex;justify-content:center;align-items:center;gap:6px;
        padding:10px 0;flex-wrap:wrap
    }
    .pager-btn {
        padding:6px 10px;border:1px solid #ddd;border-radius:6px;
        text-decoration:none;color:#333;font-size:14px;line-height:1
    }
    .pager-btn:hover { background:#f5f5f5 }
    .pager-btn.disabled { opacity:.5;pointer-events:none }
    .pager-info { font-size:14px;color:#666;padding:0 6px }
</style>
@endsection

@section('main')
<form action="" method="GET">
    <div class="filter-bar">
        <input value="{{ $search ?? '' }}" type="text" name="search" placeholder="Search users...">
        <button class="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
        </button>
    </div>
</form>

<div class="user-list">
    @forelse ($users as $user)
        <div class="user-item">
            @if($user->profile_picture)
                <a href="{{ route('users.show', $user->username) }}">
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Pic">
                </a>
            @else
                <div style="width:40px;height:40px;border-radius:50%;background:#ccc;display:flex;align-items:center;justify-content:center;font-size:12px;">
                    ?
                </div>
            @endif

            <a  href="{{ route('users.show', $user->username) }}">
                <strong>{{ $user->name }}</strong> <small>({{ '@'.$user->username }})</small> {{ ucfirst($user->account_type) }}
            </a>
        </div>
    @empty
        <p style="text-align:center;color:#777;">No users found</p>
    @endforelse
</div>

@if ($users->hasPages())
    <nav class="pager" role="navigation" aria-label="Pagination">
        <a href="{{ $users->url(1) }}"
        class="pager-btn {{ $users->onFirstPage() ? 'disabled' : '' }}">« First</a>

        <a href="{{ $users->previousPageUrl() }}"
        class="pager-btn {{ $users->onFirstPage() ? 'disabled' : '' }}">‹ Prev</a>

        <span class="pager-info">
            Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
        </span>

        <a href="{{ $users->nextPageUrl() }}"
        class="pager-btn {{ $users->currentPage() == $users->lastPage() ? 'disabled' : '' }}">Next ›</a>

        <a href="{{ $users->url($users->lastPage()) }}"
        class="pager-btn {{ $users->currentPage() == $users->lastPage() ? 'disabled' : '' }}">Last »</a>
    </nav>
@endif
@endsection
