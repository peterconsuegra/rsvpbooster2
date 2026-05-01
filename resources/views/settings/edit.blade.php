@extends('layouts.app')

@section('title', 'Edit Reservation | Restaurant Reservation CRM')
@section('page_title', 'Edit reservation')

@section('content')
    <div class="card card-soft">
        <div class="card-header bg-white border-0 rounded-top-4 p-4">
            <h5 class="mb-0">Edit reservation</h5>
            <div class="small text-muted">Update reservation details.</div>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('reservations.update', $reservation) }}">
                @method('PUT')
                @include('reservations._form')
            </form>
        </div>
    </div>
@endsection