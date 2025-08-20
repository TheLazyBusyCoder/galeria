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
        <h1 class="heading">Signup</h1>
        <form method="POST" action="/signup">
            @csrf
            <input type="text" name="name" placeholder="Name" required autocomplete="off">
            <input type="text" name="username" placeholder="Username" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" required autocomplete="off">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="off">
            <button type="submit">Signup</button>
        </form>
        <p class="text">Already have an account? <a href="/login" class="link">Login here</a></p>
    </div>
</main>
@endsection