@extends('layouts.app')

@section('title', 'Edit Reservation | Restaurant Reservation CRM')
@section('page_title', 'Edit reservation')

@section('content')
    <div class="card card-soft">
        <div class="card-body p-4 p-xl-5">
            <h4 class="mb-1">Edit reservation</h4>
            <p class="text-muted mb-4">Editing does not send or retry a Meta event.</p>

            <form method="POST" action="{{ route('reservations.update', $reservation) }}">
                @method('PUT')
                @include('reservations._form', ['buttonText' => 'Save changes'])
            </form>
        </div>
    </div>
@endsection
