@extends('layouts.app')

@section('title', 'New Reservation | Restaurant Reservation CRM')
@section('page_title', 'New Reservation')

@section('content')
    <form method="POST" action="{{ route('reservations.store') }}">
        @include('reservations._form')
    </form>
@endsection