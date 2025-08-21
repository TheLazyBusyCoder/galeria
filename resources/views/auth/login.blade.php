@extends('layouts.auth-layout')

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
        <h1 class="heading">Login</h1>
        <form method="POST" action="/login">
            @csrf
            <input style="margin-bottom: 10px;" type="text" name="username" placeholder="Username" required autocomplete="off">
            <input style="margin-bottom: 10px;" type="password" name="password" placeholder="Password" required autocomplete="off">
            <button type="submit">Login</button>
        </form>
        <p class="text">Don’t have an account? <a href="/signup" class="link">Signup here</a></p>
    </div>
</main>
@endsection
