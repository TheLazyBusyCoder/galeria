@extends('layouts.auth-layout')

@section('css')

@endsection

@section('main')
<main class="container">
    <div class="card">
        <p class="text" style="margin-bottom: 10px;">You have tried logging in too many times. Please wait a few minutes before trying again.</p>
        <a href="{{ route('login') }}" class="link">Go back to login</a>
    </div>
</main>
@endsection
