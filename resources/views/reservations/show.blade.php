@extends('layouts.app')

@section('title', 'Reservation Details | Restaurant Reservation CRM')
@section('page_title', 'Reservation details')

@section('content')
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card card-soft mb-4">
                <div class="card-body p-4 p-xl-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                        <div>
                            <h3 class="mb-1">{{ $reservation->customer_name }}</h3>
                            <div class="text-muted">{{ $reservation->restaurant_name }}</div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap align-items-start">
                            <span class="badge {{ $reservation->statusBadgeClass() }} fs-6">{{ ucfirst($reservation->status) }}</span>
                            <span class="badge {{ $reservation->metaStatusBadgeClass() }} fs-6">Meta: {{ $reservation->metaStatusLabel() }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="text-muted small">Email</div>
                            <div class="fw-semibold">{{ $reservation->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Phone</div>
                            <div class="fw-semibold">{{ $reservation->phone ?: 'Not provided' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Date / time</div>
                            <div class="fw-semibold">{{ $reservation->reservation_date?->format('M j, Y') }} at {{ $reservation->formattedReservationTime() }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Party size</div>
                            <div class="fw-semibold">{{ $reservation->party_size }} guests</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Purchase value</div>
                            <div class="fw-semibold">{{ $reservation->currency }} {{ number_format((float) $reservation->purchase_value, 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Meta event ID</div>
                            <div class="fw-semibold text-break">{{ $reservation->meta_event_id ?: 'Not generated yet' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Meta sent at</div>
                            <div class="fw-semibold">{{ $reservation->meta_event_sent_at?->format('M j, Y g:i A') ?: 'Not sent' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Created</div>
                            <div class="fw-semibold">{{ $reservation->created_at?->format('M j, Y g:i A') }}</div>
                        </div>
                        @if ($reservation->notes)
                            <div class="col-12">
                                <div class="text-muted small">Notes</div>
                                <div class="p-3 rounded-4 bg-light">{{ $reservation->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card card-soft">
                <div class="card-header bg-white border-0 rounded-top-4 p-4">
                    <h5 class="mb-0">Stored Meta response</h5>
                    <div class="small text-muted">Useful for debugging without exposing your access token.</div>
                </div>
                <div class="card-body p-4">
                    @if ($reservation->meta_response)
                        <pre class="bg-dark text-white rounded-4 p-4 mb-0 small overflow-auto">{{ json_encode($reservation->meta_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    @else
                        <div class="text-muted">No Meta response stored yet.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-soft position-sticky" style="top: 24px;">
                <div class="card-body p-4">
                    <h5>Actions</h5>
                    <p class="text-muted small">Confirm sends one Meta Purchase event. Retry only appears for confirmed reservations without a successful send.</p>

                    <div class="d-grid gap-2">
                        @if (! $reservation->isMetaEventSent() && $reservation->status !== \App\Models\Reservation::STATUS_CANCELLED)
                            <form method="POST" action="{{ route('reservations.confirm', $reservation) }}">
                                @csrf
                                <button class="btn btn-accent rounded-pill w-100" data-confirm="Confirm this reservation and send the Meta Purchase event?">
                                    <i class="bi bi-check2-circle me-1"></i> Confirm Reservation
                                </button>
                            </form>
                        @endif

                        @if ($reservation->canRetryMetaEvent())
                            <form method="POST" action="{{ route('reservations.retry-meta-event', $reservation) }}">
                                @csrf
                                <button class="btn btn-outline-danger rounded-pill w-100" data-confirm="Retry sending this Meta Purchase event? The same event ID will be reused.">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Retry Meta Event
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>

                        <form method="POST" action="{{ route('reservations.destroy', $reservation) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-secondary rounded-pill w-100" data-confirm="Delete this reservation?">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
