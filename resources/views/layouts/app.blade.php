<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Restaurant Reservation CRM')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --crm-sidebar: #172033;
            --crm-accent: #e86f38;
            --crm-soft: #fff7f2;
        }
        body {
            background: #f6f7fb;
            color: #263238;
        }
        .app-shell {
            min-height: 100vh;
        }
        .sidebar {
            background: var(--crm-sidebar);
            color: #fff;
            min-height: 100vh;
            position: sticky;
            top: 0;
        }
        .sidebar .brand {
            letter-spacing: -.03em;
            font-weight: 800;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, .76);
            border-radius: 14px;
            padding: .7rem .85rem;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, .12);
        }
        .topbar {
            background: rgba(255, 255, 255, .82);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, .05);
        }
        .card-soft {
            border: 0;
            border-radius: 22px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, .06);
        }
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--crm-soft);
            color: var(--crm-accent);
        }
        .btn-accent {
            --bs-btn-bg: var(--crm-accent);
            --bs-btn-border-color: var(--crm-accent);
            --bs-btn-hover-bg: #d85e29;
            --bs-btn-hover-border-color: #d85e29;
            --bs-btn-color: #fff;
            --bs-btn-hover-color: #fff;
        }
        .table > :not(caption) > * > * {
            padding: 1rem .85rem;
        }
        .content-wrap {
            max-width: 1480px;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                min-height: auto;
                position: relative;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid app-shell">
    <div class="row">
        <aside class="col-lg-3 col-xl-2 sidebar p-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="stat-icon bg-white bg-opacity-10 text-white">
                    <i class="bi bi-calendar2-heart"></i>
                </div>
                <div>
                    <div class="brand fs-5">Reservation CRM</div>
                    <div class="small text-white-50">Restaurant growth desk</div>
                </div>
            </div>

            <nav class="nav flex-column gap-2">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}" href="{{ route('reservations.index') }}">
                    <i class="bi bi-journal-text me-2"></i> Reservations
                </a>
                <a class="nav-link" href="{{ route('reservations.create') }}">
                    <i class="bi bi-plus-circle me-2"></i> New reservation
                </a>
            </nav>

            <div class="mt-5 p-3 rounded-4 bg-white bg-opacity-10 small text-white-50">
                Purchase events are sent only when a reservation is confirmed or manually retried.
            </div>
        </aside>

        <main class="col-lg-9 col-xl-10 px-0">
            <nav class="topbar px-4 py-3 d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-semibold">@yield('page_title', 'Dashboard')</div>
                    <div class="small text-muted">Clean reservation tracking with Meta CAPI deduplication.</div>
                </div>
                <a href="{{ route('reservations.create') }}" class="btn btn-accent rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> Reservation
                </a>
            </nav>

            <div class="content-wrap p-4 p-xl-5">
                @foreach (['success' => 'success', 'warning' => 'warning', 'info' => 'info', 'error' => 'danger'] as $flashKey => $alertClass)
                    @if (session($flashKey))
                        <div class="alert alert-{{ $alertClass }} alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                            {{ session($flashKey) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                @endforeach

                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm">
                        <div class="fw-semibold mb-1">Please fix the following:</div>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('[data-confirm]').forEach((button) => {
        button.addEventListener('click', (event) => {
            if (!window.confirm(button.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
</script>
@stack('scripts')
</body>
</html>
