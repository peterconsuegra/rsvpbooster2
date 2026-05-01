<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', DashboardController::class)->name('dashboard');

Route::resource('reservations', ReservationController::class);
Route::post('/reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])
    ->name('reservations.confirm');
Route::post('/reservations/{reservation}/retry-meta-event', [ReservationController::class, 'retryMetaEvent'])
    ->name('reservations.retry-meta-event');
