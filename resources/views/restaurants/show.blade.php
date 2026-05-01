@extends('layouts.app')

@section('title', $restaurant->name . ' | Restaurant Reservation CRM')
@section('page_title', $restaurant->name)

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-soft">
                <div class="card-header bg-white border-0 rounded-top-4 p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $restaurant->name }}</h5>
                        <div class="small text-muted">Restaurant tracking profile.</div>
                    </div>
                    <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                </div>

                <div class="card-body p-4">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Meta Pixel ID</dt>
                        <dd class="col-sm-8">{{ $restaurant->meta_pixel_id ?: 'Not set' }}</dd>

                        <dt class="col-sm-4">Meta Access Token</dt>
                        <dd class="col-sm-8 font-monospace small">{{ $restaurant->maskedMetaAccessToken() }}</dd>

                        <dt class="col-sm-4">TikTok Pixel ID</dt>
                        <dd class="col-sm-8">{{ $restaurant->tiktok_pixel_id ?: 'Not set' }}</dd>

                        <dt class="col-sm-4">TikTok Access Token</dt>
                        <dd class="col-sm-8 font-monospace small">{{ $restaurant->maskedTiktokAccessToken() }}</dd>

                        <dt class="col-sm-4">Reservations</dt>
                        <dd class="col-sm-8">{{ number_format($restaurant->reservations_count) }}</dd>

                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8">{{ $restaurant->created_at?->format('M j, Y g:i A') }}</dd>

                        <dt class="col-sm-4">Updated</dt>
                        <dd class="col-sm-8">{{ $restaurant->updated_at?->format('M j, Y g:i A') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-soft">
                <div class="card-body p-4">
                    <h6 class="fw-bold">Actions</h6>

                    <div class="d-grid gap-2">
                        <a href="{{ route('reservations.create') }}" class="btn btn-accent rounded-pill">
                            <i class="bi bi-plus-lg me-1"></i> New reservation
                        </a>

                        <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary rounded-pill">
                            Back to restaurants
                        </a>

                        <form method="POST" action="{{ route('restaurants.destroy', $restaurant) }}">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="btn btn-outline-danger rounded-pill w-100"
                                data-confirm="Delete this restaurant? Existing reservations will keep their saved restaurant name."
                            >
                                Delete restaurant
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection