<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'restaurant_id',
        'customer_name',
        'email',
        'phone',
        'reservation_date',
        'reservation_time',
        'party_size',
        'restaurant_name',
        'status',
        'purchase_value',
        'currency',
        'client_ip_address',
        'client_user_agent',
        'fbp',
        'fbc',
        'meta_event_id',
        'meta_event_sent_at',
        'meta_response',
        'notes',
    ];

    protected $casts = [
        'restaurant_id' => 'integer',
        'reservation_date' => 'date',
        'meta_response' => 'array',
        'meta_event_sent_at' => 'datetime',
        'purchase_value' => 'decimal:2',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function setCurrencyAttribute(?string $value): void
    {
        $this->attributes['currency'] = strtoupper(trim($value ?: 'USD'));
    }

    public function restaurantLabel(): string
    {
        return $this->restaurant?->name
            ?: $this->restaurant_name
            ?: 'Restaurant deleted';
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isMetaEventSent(): bool
    {
        return $this->meta_event_sent_at !== null;
    }

    public function hasMetaEventFailed(): bool
    {
        return $this->meta_response !== null
            && data_get($this->meta_response, 'ok') === false
            && $this->meta_event_sent_at === null;
    }

    public function canRetryMetaEvent(): bool
    {
        return $this->isConfirmed()
            && ($this->meta_event_sent_at === null || $this->hasMetaEventFailed());
    }

    public function metaStatusLabel(): string
    {
        if ($this->isMetaEventSent()) {
            return 'Sent';
        }

        if ($this->hasMetaEventFailed()) {
            return 'Failed';
        }

        return 'Not Sent';
    }

    public function metaStatusBadgeClass(): string
    {
        return match ($this->metaStatusLabel()) {
            'Sent' => 'text-bg-success',
            'Failed' => 'text-bg-danger',
            default => 'text-bg-secondary',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => 'text-bg-success',
            self::STATUS_CANCELLED => 'text-bg-danger',
            default => 'text-bg-warning',
        };
    }

    public function formattedReservationTime(): string
    {
        return substr((string) $this->reservation_time, 0, 5);
    }
}