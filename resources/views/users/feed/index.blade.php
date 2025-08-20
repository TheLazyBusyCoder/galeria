@extends('layouts.user-layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/photos-grid.css') }}">
@endsection

@section('main')
    <x-photos-grid-view :photos="$photos" />
@endsection

@section('js')

@endsection
