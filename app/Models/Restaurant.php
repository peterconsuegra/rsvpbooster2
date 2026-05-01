<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'meta_pixel_id',
        'meta_access_token',
        'tiktok_pixel_id',
        'tiktok_access_token',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function maskedMetaAccessToken(): string
    {
        return $this->maskedToken($this->meta_access_token);
    }

    public function maskedTiktokAccessToken(): string
    {
        return $this->maskedToken($this->tiktok_access_token);
    }

    private function maskedToken(?string $token): string
    {
        $token = trim((string) $token);

        if ($token === '') {
            return 'Not set';
        }

        if (strlen($token) <= 8) {
            return str_repeat('*', strlen($token));
        }

        return str_repeat('*', 8) . substr($token, -4);
    }
}