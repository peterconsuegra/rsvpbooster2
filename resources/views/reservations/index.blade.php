@extends('layouts.app')

@section('title', 'Reservations | Restaurant Reservation CRM')
@section('page_title', 'Reservations')

@section('content')
    <div class="card card-soft">
        <div class="card-header bg-white border-0 rounded-top-4 p-4 d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center">
            <div>
                <h5 class="mb-0">Reservations</h5>
                <div class="small text-muted">Create, edit, confirm, and track Meta CAPI Purchase events.</div>
            </div>
            <a href="{{ route('reservations.create') }}" class="btn btn-accent rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> New reservation
            </a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Customer</th>
                    <th>Restaurant</th>
                    <th>Date / Time</th>
                    <th>Party</th>
                    <th>Status</th>
                    <th>Purchase Value</th>
                    <th>Meta Event Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($reservations as $reservation)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $reservation->customer_name }}</div>
                            <div class="small text-muted">{{ $reservation->email }}</div>
                        </td>
                        <td>{{ $reservation->restaurant_name }}</td>
                        <td>
                            {{ $reservation->reservation_date?->format('M j, Y') }}
                            <div class="small text-muted">{{ $reservation->formattedReservationTime() }}</div>
                        </td>
                        <td>{{ $reservation->party_size }}</td>
                        <td><span class="badge {{ $reservation->statusBadgeClass() }}">{{ ucfirst($reservation->status) }}</span></td>
                        <td>{{ $reservation->currency }} {{ number_format((float) $reservation->purchase_value, 2) }}</td>
                        <td><span class="badge {{ $reservation->metaStatusBadgeClass() }}">{{ $reservation->metaStatusLabel() }}</span></td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">No reservations found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($reservations->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
@endsection
