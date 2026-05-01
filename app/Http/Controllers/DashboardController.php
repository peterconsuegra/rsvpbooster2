<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'totalReservations' => Reservation::count(),
            'confirmedReservations' => Reservation::where('status', Reservation::STATUS_CONFIRMED)->count(),
            'pendingReservations' => Reservation::where('status', Reservation::STATUS_PENDING)->count(),
            'trackedPurchaseValue' => Reservation::whereNotNull('meta_event_sent_at')->sum('purchase_value'),
            'recentReservations' => Reservation::latest()->take(8)->get(),
        ]);
    }
}
