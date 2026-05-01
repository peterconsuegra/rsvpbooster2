@extends('layouts.app')

@section('title', 'Dashboard | Restaurant Reservation CRM')
@section('page_title', 'Dashboard')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card card-soft h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Total reservations</div>
                        <div class="display-6 fw-bold">{{ number_format($totalReservations) }}</div>
                    </div>
                    <span class="stat-icon"><i class="bi bi-calendar-check"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card card-soft h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Confirmed</div>
                        <div class="display-6 fw-bold">{{ number_format($confirmedReservations) }}</div>
                    </div>
                    <span class="stat-icon"><i class="bi bi-check2-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card card-soft h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Pending</div>
                        <div class="display-6 fw-bold">{{ number_format($pendingReservations) }}</div>
                    </div>
                    <span class="stat-icon"><i class="bi bi-hourglass-split"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card card-soft h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Tracked purchase value</div>
                        <div class="display-6 fw-bold">${{ number_format((float) $trackedPurchaseValue, 2) }}</div>
                    </div>
                    <span class="stat-icon"><i class="bi bi-cash-coin"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-soft">
        <div class="card-header bg-white border-0 rounded-top-4 p-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Recent reservations</h5>
                <div class="small text-muted">Latest reservation and Meta event activity.</div>
            </div>
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary rounded-pill">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Customer</th>
                    <th>Restaurant</th>
                    <th>Date / Time</th>
                    <th>Status</th>
                    <th>Purchase</th>
                    <th>Meta</th>
                    <th class="text-end">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($recentReservations as $reservation)
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
                        <td><span class="badge {{ $reservation->statusBadgeClass() }}">{{ ucfirst($reservation->status) }}</span></td>
                        <td>{{ $reservation->currency }} {{ number_format((float) $reservation->purchase_value, 2) }}</td>
                        <td><span class="badge {{ $reservation->metaStatusBadgeClass() }}">{{ $reservation->metaStatusLabel() }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-outline-primary rounded-pill">Open</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">No reservations yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
