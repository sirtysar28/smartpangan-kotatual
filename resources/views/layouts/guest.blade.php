<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>{{ config('app.name', 'Pangan Cerdas (Panca) Maryadat Kota Tual') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #6366f1; --secondary: #0ea5e9; }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #4338ca 100%); background-size: 200% 200%; animation: gradient-shift 8s ease infinite; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin:0; }
        @keyframes gradient-shift { 0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%} }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)} }
        @keyframes float { 0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)} }
        .auth-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(30px); border-radius: 24px; box-shadow: 0 25px 80px rgba(0,0,0,0.3); max-width: 440px; width: 100%; animation: fadeInUp 0.6s ease-out; }
        .auth-body { padding: 2.5rem; }
        .form-control { border-radius: 10px; border: 1.5px solid #e2e8f0; padding: 0.65rem 1rem; transition: all 0.3s; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .btn-primary-modern { background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none; border-radius: 12px; font-weight: 600; transition: all 0.3s; }
        .btn-primary-modern:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,0.35); }
        .input-icon-wrapper { position: relative; }
        .input-icon-wrapper .form-control { padding-left: 2.8rem; padding-right: 2.8rem; }
        .input-icon-wrapper .icon-left { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .input-icon-wrapper .icon-right { position: absolute; right: 0.6rem; top: 50%; transform: translateY(-50%); color: #94a3b8; cursor: pointer; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 8px; }
        .bg-particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; overflow: hidden; z-index: 0; }
        .bg-particles span { position: absolute; width: 4px; height: 4px; background: rgba(255,255,255,0.15); border-radius: 50%; animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body>
    <div class="bg-particles">
        <span style="top:10%;left:20%;animation-delay:0s"></span>
        <span style="top:30%;left:80%;animation-delay:1s"></span>
        <span style="top:50%;left:10%;animation-delay:2s"></span>
        <span style="top:70%;left:60%;animation-delay:0.5s"></span>
        <span style="top:20%;left:50%;animation-delay:1.5s"></span>
        <span style="top:80%;left:30%;animation-delay:3s"></span>
    </div>
    <div class="auth-card" style="position:relative;z-index:1">
        <div class="auth-body">
            {{ $slot }}
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
