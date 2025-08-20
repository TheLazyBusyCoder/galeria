@extends('layouts.user-layout')
@section('main')
<main>
    <x-photos-grid-view :photos="$photos" />
</main>
@endsection
