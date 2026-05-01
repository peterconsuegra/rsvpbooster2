<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class MetaConversionsApiService
{
    /**
     * Send a Meta Conversions API Purchase event for a confirmed reservation.
     *
     * Customer fields hashed before sending:
     * - email is trimmed, lowercased, and SHA-256 hashed
     * - phone is reduced to digits and SHA-256 hashed
     *
     * Browser and request fields are not hashed:
     * - client_ip_address
     * - client_user_agent
     * - fbp
     * - fbc
     *
     * The event is POSTed to:
     * https://graph.facebook.com/{META_GRAPH_API_VERSION}/{META_PIXEL_ID}/events
     *
     * To test in Meta Events Manager, set META_TEST_EVENT_CODE in .env.
     * This service includes test_event_code only when that value exists.
     */
    public function sendPurchase(Reservation $reservation, Request $request): bool
    {
        if ($reservation->isMetaEventSent()) {
            Log::info('Meta CAPI Purchase event was not sent because it already succeeded.', [
                'reservation_id' => $reservation->id,
                'event_id' => $reservation->meta_event_id,
            ]);

            return true;
        }

        $eventId = $reservation->meta_event_id ?: $this->generateEventId($reservation);

        if (! $reservation->meta_event_id) {
            $reservation->forceFill(['meta_event_id' => $eventId])->save();
        }

        $pixelId = config('services.meta.pixel_id');
        $accessToken = config('services.meta.access_token');
        $graphApiVersion = config('services.meta.graph_api_version');
        $testEventCode = config('services.meta.test_event_code');

        if (! $pixelId || ! $accessToken || ! $graphApiVersion || $graphApiVersion === 'vXX.X') {
            return $this->storeFailure($reservation, 'Meta CAPI is missing a valid META_PIXEL_ID, META_ACCESS_TOKEN, or META_GRAPH_API_VERSION.');
        }

        $payload = $this->buildPayload($reservation, $request, $eventId);

        if ($testEventCode) {
            $payload['test_event_code'] = $testEventCode;
        }

        $endpoint = sprintf(
            'https://graph.facebook.com/%s/%s/events?%s',
            trim($graphApiVersion, '/'),
            urlencode((string) $pixelId),
            http_build_query(['access_token' => $accessToken])
        );

        try {
            $response = Http::timeout(12)
                ->acceptJson()
                ->asJson()
                ->post($endpoint, $payload);

            $storedResponse = [
                'ok' => $response->successful(),
                'status' => $response->status(),
                'body' => $response->json() ?: $response->body(),
            ];

            if ($response->successful()) {
                $reservation->forceFill([
                    'meta_event_sent_at' => now(),
                    'meta_response' => $storedResponse,
                ])->save();

                Log::info('Meta CAPI Purchase event sent.', [
                    'reservation_id' => $reservation->id,
                    'event_id' => $eventId,
                    'status' => $response->status(),
                ]);

                return true;
            }

            $reservation->forceFill([
                'meta_response' => $storedResponse,
            ])->save();

            Log::warning('Meta CAPI Purchase event failed.', [
                'reservation_id' => $reservation->id,
                'event_id' => $eventId,
                'status' => $response->status(),
            ]);

            return false;
        } catch (Throwable $exception) {
            return $this->storeFailure($reservation, $exception->getMessage());
        }
    }

    private function buildPayload(Reservation $reservation, Request $request, string $eventId): array
    {
        $hashedEmail = $this->hashEmail($reservation->email);
        $hashedPhone = $this->hashPhone($reservation->phone);

        $userData = array_filter([
            'em' => $hashedEmail ? [$hashedEmail] : null,
            'ph' => $hashedPhone ? [$hashedPhone] : null,
            'client_ip_address' => $reservation->client_ip_address ?: $request->ip(),
            'client_user_agent' => $reservation->client_user_agent ?: $request->userAgent(),
            'fbp' => $reservation->fbp ?: $request->cookie('_fbp'),
            'fbc' => $reservation->fbc ?: $request->cookie('_fbc'),
        ], fn ($value) => filled($value));

        return [
            'data' => [
                [
                    'event_name' => 'Purchase',
                    'event_time' => now()->timestamp,
                    'event_id' => $eventId,
                    'action_source' => 'website',
                    'event_source_url' => route('reservations.show', $reservation),
                    'user_data' => $userData,
                    'custom_data' => [
                        'currency' => strtoupper($reservation->currency ?: 'USD'),
                        'value' => (float) $reservation->purchase_value,
                        'content_name' => 'Restaurant Reservation',
                        'content_type' => 'reservation',
                    ],
                ],
            ],
        ];
    }

    private function generateEventId(Reservation $reservation): string
    {
        return sprintf('reservation_purchase_%s_%s', $reservation->id, (string) Str::uuid());
    }

    private function hashEmail(?string $email): ?string
    {
        $normalized = strtolower(trim((string) $email));

        return $normalized === '' ? null : hash('sha256', $normalized);
    }

    private function hashPhone(?string $phone): ?string
    {
        $normalized = preg_replace('/\D+/', '', (string) $phone);

        return $normalized === '' ? null : hash('sha256', $normalized);
    }

    private function storeFailure(Reservation $reservation, string $message): bool
    {
        $reservation->forceFill([
            'meta_response' => [
                'ok' => false,
                'status' => null,
                'body' => null,
                'error' => $message,
            ],
        ])->save();

        Log::warning('Meta CAPI Purchase event failed before receiving a response.', [
            'reservation_id' => $reservation->id,
            'event_id' => $reservation->meta_event_id,
            'error' => $message,
        ]);

        return false;
    }
}
