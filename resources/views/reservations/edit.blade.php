@extends('layouts.app')

@section('title', 'Edit Reservation | Restaurant Reservation CRM')
@section('page_title', 'Edit Reservation')

@section('content')
    <form method="POST" action="{{ route('reservations.update', $reservation) }}">
        @method('PUT')
        @include('reservations._form')
    </form>
@endsection