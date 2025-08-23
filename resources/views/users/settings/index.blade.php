@extends('layouts.user-layout')

@section('css')
<style>
.settings-container {
    max-width: 700px;
    margin: 0 auto;
    padding: 20px;
    color: var(--color-text);
}

.settings-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

.settings-section {
    background: var(--color-surface);
    box-shadow: 0 2px 6px var(--color-shadow);
    padding: 20px;
    margin-bottom: 20px;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--color-text);
}

.section-text {
    font-size: 14px;
    color: var(--color-text-muted);
    margin-bottom: 12px;
}

.toggle-switch {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

/* Hide the raw checkbox */
.toggle-switch input[type="checkbox"] {
    display: none;
}

/* Track */
.toggle-switch .switch {
    position: relative;
    width: 50px;
    height: 26px;
    background: var(--color-border);
    border-radius: 20px;
    cursor: pointer;
    transition: background 0.3s ease;
}

/* Knob */
.toggle-switch .switch::after {
    content: "";
    position: absolute;
    top: 3px;
    left: 3px;
    width: 20px;
    height: 20px;
    background: var(--color-surface);
    border-radius: 50%;
    box-shadow: 0 1px 3px var(--color-shadow);
    transition: transform 0.3s ease;
}

/* Checked state */
.toggle-switch input:checked + .switch {
    background: var(--color-primary);
}

.toggle-switch input:checked + .switch::after {
    transform: translateX(24px);
}

.toggle-label {
    font-size: 15px;
    color: var(--color-text);
}

/* Buttons */
.btn-primary,
.btn-secondary,
.btn-accent {
    display: inline-block;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: background 0.2s ease;
}

.btn-primary {
    background: var(--color-primary);
    color: var(--color-text-inverse);
}

.btn-primary:hover {
    opacity: 0.9;
}

.btn-secondary {
    background: var(--color-secondary);
    color: var(--color-text-inverse);
}

.btn-secondary:hover {
    opacity: 0.9;
}

.btn-accent {
    background: var(--color-accent);
    color: var(--color-text-inverse);
}

.btn-accent:hover {
    opacity: 0.9;
}

    </style>
@endsection 

@section('main')
<div class="settings-container">

    <!-- Page Header -->
    <h1 class="settings-title">Settings</h1>

    <!-- Theme Section -->
    <section class="settings-section">
        <h2 class="section-title">Theme</h2>
        <form action="{{ route('settings.theme.update') }}" method="POST">
            @csrf
            <div class="toggle-switch">
                <input type="checkbox" id="themeToggle" name="theme" value="dark" {{ $user->theme === 'dark' ? 'checked' : '' }}>
                <label for="themeToggle" class="switch"></label>
                <span class="toggle-label">
                    {{ $user->theme === 'dark' ? 'Dark' : 'Light' }}
                </span>
            </div>
            <button class="btn-primary">Save Theme</button>
        </form>
    </section>

    <!-- Account Section -->
    <section class="settings-section">
        <h2 class="section-title">Account</h2>
        <p class="section-text">Manage your account details</p>
        <a href="{{ route('profile.edit') }}" class="link">Edit Profile</a>
    </section>

    <!-- Notifications Section -->
    <section class="settings-section">
        <h2 class="section-title">Notifications</h2>
        <p class="section-text">Control how you receive notifications.</p>
        {{-- <a href="{{ route('settings.notifications') }}" class="btn-accent">Notification Settings</a> --}}
    </section>

</div>
@endsection
