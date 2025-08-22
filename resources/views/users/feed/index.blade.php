@extends('layouts.user-layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/photos-grid.css') }}">
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
        .pager{
            display:flex;justify-content:center;align-items:center;gap:6px;
            padding:10px 0;flex-wrap:wrap
        }
        .pager-btn{
            padding:6px 10px;border:1px solid #ddd;border-radius:6px;
            text-decoration:none;color:#333;font-size:14px;line-height:1
        }
        .pager-btn:hover{background:#f5f5f5}
        .pager-btn.disabled{opacity:.5;pointer-events:none}
        .pager-info{font-size:14px;color:#666;padding:0 6px}
    </style>
@endsection

@section('main')
    <form action="" method="GET">
        <div class="filter-bar">
            <input value="{{$search ?? ''}}" type="text" name="search" placeholder="Search....">
            <button class="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
            </button>
        </div>
    </form>
    {{-- Compact Pagination --}}
    @if ($photos->hasPages())
        <nav class="pager" role="navigation" aria-label="Pagination">
            <a href="{{ $photos->url(1) }}"
            class="pager-btn {{ $photos->onFirstPage() ? 'disabled' : '' }}"
            aria-disabled="{{ $photos->onFirstPage() ? 'true' : 'false' }}">« First</a>

            <a href="{{ $photos->previousPageUrl() }}"
            class="pager-btn {{ $photos->onFirstPage() ? 'disabled' : '' }}"
            rel="prev">‹ Prev</a>

            <span class="pager-info">
                Page {{ $photos->currentPage() }} of {{ $photos->lastPage() }}
            </span>

            <a href="{{ $photos->nextPageUrl() }}"
            class="pager-btn {{ $photos->currentPage() == $photos->lastPage() ? 'disabled' : '' }}"
            rel="next">Next ›</a>

            <a href="{{ $photos->url($photos->lastPage()) }}"
            class="pager-btn {{ $photos->currentPage() == $photos->lastPage() ? 'disabled' : '' }}">Last »</a>
        </nav>
    @endif

    <x-photos-grid-view :photos="$photos" />

    {{-- Compact Pagination --}}
    @if ($photos->hasPages())
        <nav class="pager" role="navigation" aria-label="Pagination">
            <a href="{{ $photos->url(1) }}"
            class="pager-btn {{ $photos->onFirstPage() ? 'disabled' : '' }}"
            aria-disabled="{{ $photos->onFirstPage() ? 'true' : 'false' }}">« First</a>

            <a href="{{ $photos->previousPageUrl() }}"
            class="pager-btn {{ $photos->onFirstPage() ? 'disabled' : '' }}"
            rel="prev">‹ Prev</a>

            <span class="pager-info">
                Page {{ $photos->currentPage() }} of {{ $photos->lastPage() }}
            </span>

            <a href="{{ $photos->nextPageUrl() }}"
            class="pager-btn {{ $photos->currentPage() == $photos->lastPage() ? 'disabled' : '' }}"
            rel="next">Next ›</a>

            <a href="{{ $photos->url($photos->lastPage()) }}"
            class="pager-btn {{ $photos->currentPage() == $photos->lastPage() ? 'disabled' : '' }}">Last »</a>
        </nav>
    @endif
@endsection

@section('js')

@endsection
