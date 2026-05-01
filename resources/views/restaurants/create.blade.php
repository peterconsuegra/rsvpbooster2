@extends('layouts.app')

@section('title', 'New Restaurant | Restaurant Reservation CRM')
@section('page_title', 'New Restaurant')

@section('content')
    <form method="POST" action="{{ route('restaurants.store') }}">
        @include('restaurants._form')
    </form>
@endsection