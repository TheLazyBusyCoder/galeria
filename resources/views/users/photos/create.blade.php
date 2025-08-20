@extends('layouts.user-layout')

@section('css')
<style>
    .heading {
        margin-bottom: 1.5rem;
        color: var(--color-primary);
    }

    .text {
        margin-top: 1rem;
        color: var(--color-text-muted);
        font-size: 0.9rem;
    }    
</style>
@endsection

@section('main')
<main class="container">
    <div class="card">
        <h1 class="heading">Upload Photo</h1>
        <form method="POST" action="{{ route('photos.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="image" required>

            <input type="text" name="caption" maxlength="255" placeholder="Write a caption (optional)">

            <button type="submit">Upload</button>
        </form>
    </div>
</main>
@endsection
