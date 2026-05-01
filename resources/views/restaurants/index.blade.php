@extends('layouts.app')

@section('title', 'Restaurants | Restaurant Reservation CRM')
@section('page_title', 'Restaurants')

@section('content')
    <div class="card card-soft">
        <div class="card-header bg-white border-0 rounded-top-4 p-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Restaurants</h5>
                <div class="small text-muted">Manage restaurants and their tracking credentials.</div>
            </div>
            <a href="{{ route('restaurants.create') }}" class="btn btn-accent rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> Restaurant
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Meta Pixel</th>
                    <th>TikTok Pixel</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($restaurants as $restaurant)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $restaurant->name }}</div>
                            <div class="small text-muted">{{ $restaurant->reservations_count ?? 0 }} reservations</div>
                        </td>
                        <td>{{ $restaurant->meta_pixel_id ?: 'Not set' }}</td>
                        <td>{{ $restaurant->tiktok_pixel_id ?: 'Not set' }}</td>
                        <td>{{ $restaurant->created_at?->format('M j, Y') }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-sm btn-outline-primary rounded-pill">Open</a>
                                <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-sm btn-outline-secondary rounded-pill">Edit</a>
                                <form method="POST" action="{{ route('restaurants.destroy', $restaurant) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger rounded-pill"
                                        data-confirm="Delete this restaurant? Existing reservations will keep their saved restaurant name."
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            No restaurants yet.
                            <a href="{{ route('restaurants.create') }}">Create your first restaurant</a>.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($restaurants->hasPages())
            <div class="card-footer bg-white border-0 rounded-bottom-4 p-4">
                {{ $restaurants->links() }}
            </div>
        @endif
    </div>
@endsection