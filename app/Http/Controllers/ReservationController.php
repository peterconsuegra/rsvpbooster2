<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Services\MetaConversionsApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(): View
    {
        return view('reservations.index', [
            'reservations' => Reservation::latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('reservations.create', [
            'reservation' => new Reservation([
                'status' => Reservation::STATUS_PENDING,
                'currency' => 'USD',
                'purchase_value' => '0.00',
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedReservationData($request);

        $data['client_ip_address'] = $request->ip();
        $data['client_user_agent'] = $request->userAgent();
        $data['fbp'] = $request->cookie('_fbp');
        $data['fbc'] = $request->cookie('_fbc');

        $reservation = Reservation::create($data);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservation created. Confirm it when you are ready to send the Meta Purchase event.');
    }

    public function show(Reservation $reservation): View
    {
        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation): View
    {
        return view('reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $reservation->update($this->validatedReservationData($request));

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservation updated.');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservation deleted.');
    }

    public function confirm(
        Request $request,
        Reservation $reservation,
        MetaConversionsApiService $metaConversionsApiService
    ): RedirectResponse {
        if ($reservation->status === Reservation::STATUS_CANCELLED) {
            return redirect()
                ->route('reservations.show', $reservation)
                ->with('warning', 'Cancelled reservations cannot be confirmed. Edit the reservation first if this was a mistake.');
        }

        if (! $reservation->isConfirmed()) {
            $reservation->update(['status' => Reservation::STATUS_CONFIRMED]);
            $reservation->refresh();
        }

        if ($reservation->isMetaEventSent()) {
            return redirect()
                ->route('reservations.show', $reservation)
                ->with('info', 'Reservation is confirmed and the Meta Purchase event was already sent.');
        }

        $sent = $metaConversionsApiService->sendPurchase($reservation, $request);

        return redirect()
            ->route('reservations.show', $reservation->fresh())
            ->with($sent ? 'success' : 'warning', $sent
                ? 'Reservation confirmed and Meta Purchase event sent.'
                : 'Reservation confirmed, but the Meta Purchase event was not sent. Check the stored response.');
    }

    public function retryMetaEvent(
        Request $request,
        Reservation $reservation,
        MetaConversionsApiService $metaConversionsApiService
    ): RedirectResponse {
        if (! $reservation->isConfirmed()) {
            return redirect()
                ->route('reservations.show', $reservation)
                ->with('warning', 'Only confirmed reservations can send a Meta Purchase event.');
        }

        if (! $reservation->canRetryMetaEvent()) {
            return redirect()
                ->route('reservations.show', $reservation)
                ->with('info', 'The Meta Purchase event was already sent. No duplicate event was created.');
        }

        $sent = $metaConversionsApiService->sendPurchase($reservation, $request);

        return redirect()
            ->route('reservations.show', $reservation->fresh())
            ->with($sent ? 'success' : 'warning', $sent
                ? 'Meta Purchase event sent.'
                : 'Meta Purchase event retry failed. Check the stored response.');
    }

    private function validatedReservationData(Request $request): array
    {
        return $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'reservation_date' => ['required', 'date'],
            'reservation_time' => ['required', 'date_format:H:i'],
            'party_size' => ['required', 'integer', 'min:1'],
            'restaurant_name' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(Reservation::STATUSES)],
            'purchase_value' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
