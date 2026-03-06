<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TRB HUB')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f5f7fb; }
        .app-shell { min-height: 100vh; display: flex; flex-direction: column; }
        .app-main { flex: 1; }
        .brand-badge { font-weight: 700; letter-spacing: .2px; }
        .card-elev { border: 0; box-shadow: 0 10px 30px rgba(16, 24, 40, .08); border-radius: 16px; }
        .section-title { font-weight: 700; }
        .muted { color: #667085; }
        .required::after { content: " *"; color: #dc3545; }
        .btn-pill { border-radius: 999px; }
        .input-h { height: 44px; }
    </style>

    @stack('styles')
</head>
<body>
<div class="app-shell">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand brand-badge" href="{{ route('taruna.register') }}">TRB HUB</a>

            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-outline-primary btn-sm btn-pill" href="{{ route('taruna.edit.access') }}">
                    Edit Identitas
                </a>
                <a class="btn btn-outline-danger btn-sm btn-pill" href="/admin">
                    Masuk
                </a>
            </div>
        </div>
    </nav>

    <main class="app-main py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="py-4 border-top bg-white">
        <div class="container text-center muted">
            TRB HUB • Pendaftaran Ujian TRB
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>