@csrf

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-soft">
            <div class="card-header bg-white border-0 rounded-top-4 p-4">
                <h5 class="mb-0">Reservation details</h5>
                <div class="small text-muted">Capture the customer, booking, and tracking value.</div>
            </div>

            <div class="card-body p-4">
                @if ($restaurants->isEmpty())
                    <div class="alert alert-warning rounded-4 border-0">
                        You need to create at least one restaurant before creating reservations.
                        <a href="{{ route('restaurants.create') }}" class="alert-link">Create a restaurant</a>.
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Customer name</label>
                        <input
                            type="text"
                            id="customer_name"
                            name="customer_name"
                            class="form-control @error('customer_name') is-invalid @enderror"
                            value="{{ old('customer_name', $reservation->customer_name) }}"
                            required
                        >
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="restaurant_id" class="form-label">Restaurant</label>
                        <select
                            id="restaurant_id"
                            name="restaurant_id"
                            class="form-select @error('restaurant_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select restaurant</option>
                            @foreach ($restaurants as $restaurant)
                                <option
                                    value="{{ $restaurant->id }}"
                                    @selected((string) old('restaurant_id', $reservation->restaurant_id) === (string) $restaurant->id)
                                >
                                    {{ $restaurant->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('restaurant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $reservation->email) }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $reservation->phone) }}"
                        >
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="reservation_date" class="form-label">Reservation date</label>
                        <input
                            type="date"
                            id="reservation_date"
                            name="reservation_date"
                            class="form-control @error('reservation_date') is-invalid @enderror"
                            value="{{ old('reservation_date', optional($reservation->reservation_date)->format('Y-m-d')) }}"
                            required
                        >
                        @error('reservation_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="reservation_time" class="form-label">Reservation time</label>
                        <input
                            type="time"
                            id="reservation_time"
                            name="reservation_time"
                            class="form-control @error('reservation_time') is-invalid @enderror"
                            value="{{ old('reservation_time', $reservation->formattedReservationTime()) }}"
                            required
                        >
                        @error('reservation_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="party_size" class="form-label">Party size</label>
                        <input
                            type="number"
                            id="party_size"
                            name="party_size"
                            min="1"
                            class="form-control @error('party_size') is-invalid @enderror"
                            value="{{ old('party_size', $reservation->party_size) }}"
                            required
                        >
                        @error('party_size')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="purchase_value" class="form-label">Purchase value</label>
                        <input
                            type="number"
                            id="purchase_value"
                            name="purchase_value"
                            min="0"
                            step="0.01"
                            class="form-control @error('purchase_value') is-invalid @enderror"
                            value="{{ old('purchase_value', $reservation->purchase_value) }}"
                            required
                        >
                        @error('purchase_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="currency" class="form-label">Currency</label>
                        <select
                            id="currency"
                            name="currency"
                            class="form-select @error('currency') is-invalid @enderror"
                            required
                        >
                            @foreach ($currencyOptions as $currency)
                                <option
                                    value="{{ $currency }}"
                                    @selected(old('currency', $reservation->currency) === $currency)
                                >
                                    {{ $currency }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select
                            id="status"
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >
                            @foreach (\App\Models\Reservation::STATUSES as $status)
                                <option
                                    value="{{ $status }}"
                                    @selected(old('status', $reservation->status) === $status)
                                >
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            class="form-control @error('notes') is-invalid @enderror"
                        >{{ old('notes', $reservation->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
    <button type="submit" class="btn btn-accent rounded-pill px-4" @disabled($restaurants->isEmpty())>
        <i class="bi bi-check2 me-1"></i> Save reservation
    </button>
</div>