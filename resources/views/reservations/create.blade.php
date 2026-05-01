@extends('layouts.app')

@section('title', 'Create Reservation | Restaurant Reservation CRM')
@section('page_title', 'Create reservation')

@section('content')
    <div class="card card-soft">
        <div class="card-body p-4 p-xl-5">
            <h4 class="mb-1">New reservation</h4>
            <p class="text-muted mb-4">This will save the reservation only. The Meta Purchase event is sent later when you confirm it.</p>

            <form method="POST" action="{{ route('reservations.store') }}">
                @include('reservations._form', ['buttonText' => 'Create reservation'])
            </form>
        </div>
    </div>
@endsection
