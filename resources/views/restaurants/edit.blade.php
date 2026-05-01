@extends('layouts.app')

@section('title', 'Edit Restaurant | Restaurant Reservation CRM')
@section('page_title', 'Edit Restaurant')

@section('content')
    <form method="POST" action="{{ route('restaurants.update', $restaurant) }}">
        @method('PUT')
        @include('restaurants._form')
    </form>
@endsection