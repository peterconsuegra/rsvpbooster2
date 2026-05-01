@extends('layouts.app')

@section('title', 'New Reservation | Restaurant Reservation CRM')
@section('page_title', 'New reservation')

@section('content')
    <div class="card card-soft">
        <div class="card-header bg-white border-0 rounded-top-4 p-4">
            <h5 class="mb-0">Create reservation</h5>
            <div class="small text-muted">Add a new restaurant reservation.</div>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('reservations.store') }}">
                @include('reservations._form')
            </form>
        </div>
    </div>
@endsection