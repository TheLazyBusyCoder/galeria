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
    .button {
        padding: 5px;
        max-width: 150px;
    }
</style>
@endsection

@section('main')
<main class="container">
    <div class="card">
        <h1 class="heading">Upload Photo</h1>
        <form method="POST" action="{{ route('photos.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="image" required style="margin-bottom: 10px;">

            <input type="text" name="caption" style="margin-bottom: 10px;" maxlength="255" placeholder="Write a caption (optional)">

            <button class="button" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-up-icon lucide-image-up"><path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/><path d="m14 19.5 3-3 3 3"/><path d="M17 22v-5.5"/><circle cx="9" cy="9" r="2"/></svg>
            </button>
        </form>
    </div>
</main>
@endsection
