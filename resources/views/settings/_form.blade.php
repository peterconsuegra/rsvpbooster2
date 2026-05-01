@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="customer_name" class="form-label">Customer name</label>
        <input
            type="text"
            name="customer_name"
            id="customer_name"
            class="form-control rounded-4"
            value="{{ old('customer_name', $reservation->customer_name) }}"
            required
        >
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input
            type="email"
            name="email"
            id="email"
            class="form-control rounded-4"
            value="{{ old('email', $reservation->email) }}"
            required
        >
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label">Phone</label>
        <input
            type="text"
            name="phone"
            id="phone"
            class="form-control rounded-4"
            value="{{ old('phone', $reservation->phone) }}"
        >
    </div>

    <div class="col-md-6">
        <label for="restaurant_name" class="form-label">Restaurant name</label>
        <select name="restaurant_name" id="restaurant_name" class="form-select rounded-4" required>
            <option value="">Select restaurant</option>

            @foreach ($restaurantNameOptions as $restaurantName)
                <option
                    value="{{ $restaurantName }}"
                    @selected(old('restaurant_name', $reservation->restaurant_name) === $restaurantName)
                >
                    {{ $restaurantName }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="reservation_date" class="form-label">Reservation date</label>
        <input
            type="date"
            name="reservation_date"
            id="reservation_date"
            class="form-control rounded-4"
            value="{{ old('reservation_date', optional($reservation->reservation_date)->format('Y-m-d')) }}"
            required
        >
    </div>

    <div class="col-md-6">
        <label for="reservation_time" class="form-label">Reservation time</label>
        <input
            type="time"
            name="reservation_time"
            id="reservation_time"
            class="form-control rounded-4"
            value="{{ old('reservation_time', $reservation->formattedReservationTime()) }}"
            required
        >
    </div>

    <div class="col-md-4">
        <label for="party_size" class="form-label">Party size</label>
        <input
            type="number"
            name="party_size"
            id="party_size"
            class="form-control rounded-4"
            value="{{ old('party_size', $reservation->party_size) }}"
            min="1"
            required
        >
    </div>

    <div class="col-md-4">
        <label for="purchase_value" class="form-label">Purchase value</label>
        <input
            type="number"
            name="purchase_value"
            id="purchase_value"
            class="form-control rounded-4"
            value="{{ old('purchase_value', $reservation->purchase_value ?? '0.00') }}"
            step="0.01"
            min="0"
            required
        >
    </div>

    <div class="col-md-4">
        <label for="currency" class="form-label">Currency</label>
        <select name="currency" id="currency" class="form-select rounded-4" required>
            <option value="">Select currency</option>

            @foreach ($currencyOptions as $currency)
                <option
                    value="{{ $currency }}"
                    @selected(old('currency', $reservation->currency) === $currency)
                >
                    {{ $currency }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select rounded-4" required>
            @foreach (\App\Models\Reservation::STATUSES as $status)
                <option
                    value="{{ $status }}"
                    @selected(old('status', $reservation->status) === $status)
                >
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label for="notes" class="form-label">Notes</label>
        <textarea
            name="notes"
            id="notes"
            class="form-control rounded-4"
            rows="4"
        >{{ old('notes', $reservation->notes) }}</textarea>
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-accent rounded-pill px-4">
        Save reservation
    </button>

    <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
        Cancel
    </a>
</div>