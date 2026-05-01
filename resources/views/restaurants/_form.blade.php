@csrf

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-soft">
            <div class="card-header bg-white border-0 rounded-top-4 p-4">
                <h5 class="mb-0">Restaurant details</h5>
                <div class="small text-muted">Store restaurant-level tracking credentials.</div>
            </div>

            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="name" class="form-label">Restaurant name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                        value="{{ old('name', $restaurant->name) }}"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="meta_pixel_id" class="form-label">Meta Pixel ID</label>
                        <input
                            type="text"
                            id="meta_pixel_id"
                            name="meta_pixel_id"
                            class="form-control @error('meta_pixel_id') is-invalid @enderror"
                            value="{{ old('meta_pixel_id', $restaurant->meta_pixel_id) }}"
                        >
                        @error('meta_pixel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tiktok_pixel_id" class="form-label">TikTok Pixel ID</label>
                        <input
                            type="text"
                            id="tiktok_pixel_id"
                            name="tiktok_pixel_id"
                            class="form-control @error('tiktok_pixel_id') is-invalid @enderror"
                            value="{{ old('tiktok_pixel_id', $restaurant->tiktok_pixel_id) }}"
                        >
                        @error('tiktok_pixel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="meta_access_token" class="form-label">Meta Access Token</label>
                        <textarea
                            id="meta_access_token"
                            name="meta_access_token"
                            rows="5"
                            class="form-control font-monospace small @error('meta_access_token') is-invalid @enderror"
                        >{{ old('meta_access_token', $restaurant->meta_access_token) }}</textarea>
                        @error('meta_access_token')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tiktok_access_token" class="form-label">TikTok Access Token</label>
                        <textarea
                            id="tiktok_access_token"
                            name="tiktok_access_token"
                            rows="5"
                            class="form-control font-monospace small @error('tiktok_access_token') is-invalid @enderror"
                        >{{ old('tiktok_access_token', $restaurant->tiktok_access_token) }}</textarea>
                        @error('tiktok_access_token')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-soft">
            <div class="card-body p-4">
                <h6 class="fw-bold">Tracking notes</h6>
                <p class="text-muted small mb-0">
                    Meta credentials here are used when sending Purchase events for reservations attached to this restaurant.
                    TikTok credentials are stored for future event integrations.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
    <button type="submit" class="btn btn-accent rounded-pill px-4">
        <i class="bi bi-check2 me-1"></i> Save restaurant
    </button>
</div>