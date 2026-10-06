<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pangan Cerdas (Panca) Maryadat Kota Tual') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --tual-blue: #1a5276; }
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #1a5276 0%, #2980b9 50%, #3498db 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,.2); overflow: hidden; max-width: 450px; width: 100%; }
        .auth-header { background: linear-gradient(135deg, #1a5276, #2980b9); padding: 30px; text-align: center; color: white; }
        .auth-body { padding: 30px; }
        .form-control:focus { border-color: #2980b9; box-shadow: 0 0 0 .2rem rgba(41,128,185,.25); }
        .btn-primary { background: #1a5276; border-color: #1a5276; }
        .btn-primary:hover { background: #2980b9; border-color: #2980b9; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <h4 class="fw-bold mb-1"><i class="bi bi-shop"></i> Pangan Cerdas (Panca) Maryadat <span class="text-warning">Kota Tual</span></h4>
            <small class="opacity-75">Marketplace Digital UMKM Kota Tual</small>
        </div>
        <div class="auth-body">
            {{ $slot }}
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
