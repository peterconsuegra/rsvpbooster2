# Restaurant Reservation CRM

A complete, small Laravel 12 app for managing restaurant reservations and sending a Meta Conversions API `Purchase` event when a reservation is confirmed.

## Stack

- Laravel 12
- Blade
- Bootstrap 5 CDN
- MySQL
- Laravel HTTP client
- Vanilla JavaScript only where useful

## Install

```bash
cd /var/www/html
unzip rsvpboosterpetelocalnet-full-app.zip
cd rsvpboosterpetelocalnet-full-app
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your MySQL credentials and Meta values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rsvpboosterpetelocalnet
DB_USERNAME=root
DB_PASSWORD=

META_PIXEL_ID=
META_ACCESS_TOKEN=
META_GRAPH_API_VERSION=vXX.X
META_TEST_EVENT_CODE=
```

Then run:

```bash
php artisan migrate
php artisan serve --host=0.0.0.0 --port=8000
```

Open:

```text
http://localhost:8000/dashboard
```

## Behavior

- Creating a reservation does not send a Meta event.
- Clicking **Confirm Reservation** sets the status to confirmed and sends one Meta CAPI `Purchase` event.
- The app generates and stores a unique `meta_event_id` for event deduplication.
- The same Purchase event is not sent again after a successful send.
- The **Retry Meta Event** button appears only when the reservation is confirmed and the Meta event has not succeeded.
- Meta API responses are stored in `meta_response` for debugging.
- `meta_event_sent_at` is stored only after a successful HTTP response from Meta.

## Meta CAPI user data

Hashed before sending:

- email, trimmed, lowercased, SHA-256 hashed
- phone, digits only, SHA-256 hashed

Not hashed:

- client IP address
- client user agent
- `_fbp` cookie
- `_fbc` cookie

## Author

Pedro Consuegra
