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
        <h1 class="heading">Signup</h1>

        {{-- Show errors if validation fails --}}
        @if ($errors->any())
            <div class="error">
                <ul style="list-style-type: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="signupForm" method="POST" action="{{ route('signup') }}">
            @csrf
            <input style="margin-bottom: 10px;" type="text" id="name" name="name"
                placeholder="Name" required autocomplete="off" maxlength="100"
                pattern="^[a-zA-Z\s]+$" title="Only letters and spaces allowed">

            <input style="margin-bottom: 10px;" type="text" id="username" name="username"
                placeholder="Username" required autocomplete="off" minlength="3" maxlength="20"
                pattern="^[a-zA-Z0-9_]+$" title="Only letters, numbers, and underscores allowed">

            <input style="margin-bottom: 10px;" type="password" id="password" name="password"
                placeholder="Password" required autocomplete="off" minlength="6">

            <input style="margin-bottom: 10px;" type="password" id="password_confirmation"
                name="password_confirmation" placeholder="Confirm Password" required autocomplete="off">

            <button type="submit">Signup</button>
        </form>

        <p class="text">Already have an account? <a href="/login" class="link">Login here</a></p>
    </div>
</main>

<script>
document.getElementById("signupForm").addEventListener("submit", function(event) {
    const name = document.getElementById("name").value.trim();
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirm = document.getElementById("password_confirmation").value.trim();

    if (!/^[a-zA-Z\s]+$/.test(name)) {
        alert("Name can only contain letters and spaces.");
        event.preventDefault();
    }

    if (!/^[a-zA-Z0-9_]+$/.test(username) || username.length < 3) {
        alert("Username must be at least 3 characters and can only contain letters, numbers, and underscores.");
        event.preventDefault();
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters.");
        event.preventDefault();
    }

    if (password !== confirm) {
        alert("Passwords do not match.");
        event.preventDefault();
    }
});
</script>
@endsection
