@csrf

<div class="row g-4">
    <div class="col-md-6">
        <label for="customer_name" class="form-label">Customer name</label>
        <input id="customer_name" name="customer_name" value="{{ old('customer_name', $reservation->customer_name) }}" class="form-control form-control-lg @error('customer_name') is-invalid @enderror" required>
        @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="restaurant_name" class="form-label">Restaurant name</label>
        <input id="restaurant_name" name="restaurant_name" value="{{ old('restaurant_name', $reservation->restaurant_name) }}" class="form-control form-control-lg @error('restaurant_name') is-invalid @enderror" required>
        @error('restaurant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $reservation->email) }}" class="form-control form-control-lg @error('email') is-invalid @enderror" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="phone" class="form-label">Phone <span class="text-muted small">recommended</span></label>
        <input id="phone" name="phone" value="{{ old('phone', $reservation->phone) }}" class="form-control form-control-lg @error('phone') is-invalid @enderror">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="reservation_date" class="form-label">Reservation date</label>
        <input id="reservation_date" type="date" name="reservation_date" value="{{ old('reservation_date', optional($reservation->reservation_date)->format('Y-m-d')) }}" class="form-control form-control-lg @error('reservation_date') is-invalid @enderror" required>
        @error('reservation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="reservation_time" class="form-label">Reservation time</label>
        <input id="reservation_time" type="time" name="reservation_time" value="{{ old('reservation_time', $reservation->formattedReservationTime()) }}" class="form-control form-control-lg @error('reservation_time') is-invalid @enderror" required>
        @error('reservation_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="party_size" class="form-label">Party size</label>
        <input id="party_size" type="number" min="1" name="party_size" value="{{ old('party_size', $reservation->party_size ?: 2) }}" class="form-control form-control-lg @error('party_size') is-invalid @enderror" required>
        @error('party_size') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select form-select-lg @error('status') is-invalid @enderror" required>
            @foreach (\App\Models\Reservation::STATUSES as $status)
                <option value="{{ $status }}" @selected(old('status', $reservation->status ?: 'pending') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <div class="form-text">Changing status here does not send Meta CAPI. Use Confirm Reservation.</div>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="purchase_value" class="form-label">Purchase value</label>
        <input id="purchase_value" type="number" min="0" step="0.01" name="purchase_value" value="{{ old('purchase_value', $reservation->purchase_value ?? '0.00') }}" class="form-control form-control-lg @error('purchase_value') is-invalid @enderror" required>
        @error('purchase_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="currency" class="form-label">Currency</label>
        <input id="currency" maxlength="3" name="currency" value="{{ old('currency', $reservation->currency ?: 'USD') }}" class="form-control form-control-lg text-uppercase @error('currency') is-invalid @enderror" required>
        @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label for="notes" class="form-label">Notes</label>
        <textarea id="notes" name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $reservation->notes) }}</textarea>
        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button class="btn btn-accent btn-lg rounded-pill px-4" type="submit">{{ $buttonText }}</button>
    <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Cancel</a>
</div>
