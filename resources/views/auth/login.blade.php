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

    .error {
        color: red;
        font-size: 0.85rem;
        margin-bottom: 8px;
    }

    
</style>
@endsection

@section('main')
<main class="container">
    <div class="card">
        <h1 class="heading">Login</h1>
        
        {{-- Show validation errors --}}
        @if ($errors->any())
            <div class="error">
                <ul style="list-style-type: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <input style="margin-bottom: 10px;" type="text" id="username" name="username" 
                placeholder="Username" required autocomplete="off" minlength="3" maxlength="20"
                pattern="^[a-zA-Z0-9_]+$" title="Only letters, numbers and underscores allowed">

            <input style="margin-bottom: 10px;" type="password" id="password" name="password" 
                placeholder="Password" required autocomplete="off" minlength="6">

            <button type="submit">Login</button>
        </form>

        <p class="text">Don’t have an account? <a href="/signup" class="link">Signup here</a></p>
    </div>
</main>

<script>
document.getElementById("loginForm").addEventListener("submit", function(event) {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    if (username.length < 3 || !/^[a-zA-Z0-9_]+$/.test(username)) {
        alert("Username must be at least 3 characters and contain only letters, numbers, or underscores.");
        event.preventDefault();
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        event.preventDefault();
    }
});
</script>
@endsection
