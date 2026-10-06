<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>@yield('title', 'UMKM Panel - Pangan Cerdas (Panca) Maryadat Kota Tual')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
            --accent: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
            --dark: #0f172a;
            --dark-2: #1e293b;
            --dark-3: #334155;
            --gray: #64748b;
            --gray-light: #94a3b8;
            --bg: #f1f5f9;
            --bg-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --glass: rgba(255,255,255,0.7);
            --glass-border: rgba(255,255,255,0.2);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.04);
            --shadow: 0 4px 20px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.1);
            --shadow-glow: 0 0 30px rgba(99,102,241,0.15);
            --radius: 16px;
            --radius-sm: 10px;
            --radius-xs: 8px;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 72px;
        }

        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); overflow-x: hidden; color: var(--text-main); margin: 0; padding: 0; }

        /* ===== ANTI-MELEBAR (overflow guard mobile) ===== */
        .min-w-0 { min-width: 0 !important; }
        img, video, canvas, svg { max-width: 100%; }
        .main-content { min-width: 0; }
        .table-responsive { max-width: 100%; -webkit-overflow-scrolling: touch; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--gray-light); border-radius: 10px; }

        /* ===== DARK MODE ===== */
        html.dark-mode {
            --bg: #0f172a;
            --bg-card: #1e293b;
            --text-main: #e2e8f0;
            --text-muted: #94a3b8;
            --shadow: 0 4px 20px rgba(0,0,0,0.3);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.4);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.2);
        }
        html.dark-mode body { background: var(--bg); color: var(--text-main); }
        html.dark-mode .card,
        html.dark-mode .chart-container,
        html.dark-mode .stat-card { background: var(--bg-card); color: var(--text-main); }
        html.dark-mode .table-modern thead th { background: #1a2332; color: var(--text-muted); }
        html.dark-mode .table-modern tbody td { border-bottom-color: #2a3a4e; color: var(--text-main); }
        html.dark-mode .table-modern tbody tr:hover { background: #253344; }
        html.dark-mode .form-control,
        html.dark-mode .form-select { background: #1e293b; border-color: #334155; color: #e2e8f0; }
        html.dark-mode .form-control:focus,
        html.dark-mode .form-select:focus { border-color: var(--primary); background: #1e293b; color: #e2e8f0; }
        html.dark-mode .text-muted { color: var(--text-muted) !important; }
        html.dark-mode .text-dark { color: var(--text-main) !important; }
        html.dark-mode .badge-modern { background: rgba(99,102,241,0.15); }
        html.dark-mode .stat-card .stat-icon { opacity: 0.8; }
        html.dark-mode .stat-card small { color: var(--text-muted) !important; }
        html.dark-mode .sidebar-admin { background: linear-gradient(180deg, #000000 0%, #0a0a0a 50%, #111111 100%) !important; }
        html.dark-mode .main-content { background: var(--bg); }
        html.dark-mode .alert-modern { background: var(--bg-card); color: var(--text-main); }
        html.dark-mode .section-title { color: var(--text-main); }
        html.dark-mode .small, html.dark-mode small { color: var(--text-muted); }
        html.dark-mode h4, html.dark-mode h5, html.dark-mode h6 { color: var(--text-main); }
        html.dark-mode .fw-semibold, html.dark-mode .fw-bold { color: var(--text-main); }
        html.dark-mode .card-product { background: var(--bg-card); }
        html.dark-mode .card-product .card-body { background: var(--bg-card); }
        html.dark-mode .page-link { background: var(--bg-card); color: var(--text-muted); }
        html.dark-mode .dropdown-menu { background: var(--bg-card); border-color: #334155; }
        html.dark-mode .dropdown-item { color: var(--text-main); }
        html.dark-mode .dropdown-item:hover { background: rgba(99,102,241,0.1); }
        html.dark-mode .modal-content { background: var(--bg-card); color: var(--text-main); }
        html.dark-mode .input-icon-wrapper .icon-right:hover { background: #253344; }
        html.dark-mode .skeleton { background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%); }
        html.dark-mode .progress-modern { background: #334155; }
        html.dark-mode .nav-link.text-dark { color: var(--text-main) !important; }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeInDown { from { opacity:0; transform:translateY(-20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeInLeft { from { opacity:0; transform:translateX(-30px); } to { opacity:1; transform:translateX(0); } }
        @keyframes fadeInRight { from { opacity:0; transform:translateX(30px); } to { opacity:1; transform:translateX(0); } }
        @keyframes scaleIn { from { opacity:0; transform:scale(0.9); } to { opacity:1; transform:scale(1); } }
        @keyframes float { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-10px); } }
        @keyframes pulse-glow { 0%,100% { box-shadow: 0 0 5px rgba(99,102,241,0.2); } 50% { box-shadow: 0 0 25px rgba(99,102,241,0.4); } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        @keyframes gradient-shift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes ripple { 0% { transform: scale(0); opacity: 0.5; } 100% { transform: scale(4); opacity: 0; } }

        .animate-fade-up { animation: fadeInUp 0.6s ease-out both; }
        .animate-fade-down { animation: fadeInDown 0.5s ease-out both; }
        .animate-fade-left { animation: fadeInLeft 0.6s ease-out both; }
        .animate-fade-right { animation: fadeInRight 0.6s ease-out both; }
        .animate-scale { animation: scaleIn 0.5s ease-out both; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }

        /* ===== SIDEBAR ===== */
        .sidebar-admin {
            min-height: 100vh;
            background: linear-gradient(180deg, #000000 0%, #0a0a0a 50%, #111111 100%);
            border-right: 1px solid rgba(255,255,255,0.08);
            position: fixed;
            width: var(--sidebar-width);
            left: 0; top: 0;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            height: 100vh;
            display: flex; flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-admin::-webkit-scrollbar { width: 4px; }
        .sidebar-admin::-webkit-scrollbar-track { background: #000; }
        .sidebar-admin::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.3); border-radius: 10px; }
        .sidebar-admin.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        .sidebar-admin.collapsed .sidebar-text { display: none; }
        .sidebar-admin.collapsed .sidebar-header-text { display: none; }
        .sidebar-admin.collapsed .sidebar-section-label { display: none; }
        .sidebar-admin.collapsed .sidebar-brand { justify-content: center; }
        .sidebar-admin.collapsed .nav-link { justify-content: center; padding: 11px 0; margin: 2px 8px; }
        .sidebar-admin.collapsed .nav-link i { margin: 0; font-size: 1.2rem; }
        .sidebar-admin.collapsed .sidebar-section-label { display: none; }

        .sidebar-admin .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 11px 20px;
            border-radius: 12px;
            margin: 2px 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
        }
        .sidebar-admin .nav-link:hover {
            color: #fff;
            background: rgba(99,102,241,0.15);
            border: 1px solid rgba(99,102,241,0.2);
        }
        .sidebar-admin .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 4px 15px rgba(99,102,241,0.4);
            border: 1px solid rgba(99,102,241,0.3);
        }
        .sidebar-admin .nav-link i { font-size: 1.1rem; width: 22px; text-align: center; flex-shrink: 0; }
        .sidebar-admin .sidebar-section-label {
            padding: 0.5rem 1.25rem;
            margin-top: 0.5rem;
        }
        .sidebar-admin .sidebar-section-label small {
            color: rgba(255,255,255,0.3) !important;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }
        .sidebar-admin .sidebar-brand {
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-admin .sidebar-brand h6 {
            color: #fff !important;
        }
        .sidebar-admin .sidebar-brand small {
            color: rgba(255,255,255,0.5) !important;
        }

        /* Sidebar toggle button */
        .sidebar-toggle-btn {
            position: absolute;
            top: 20px;
            right: -14px;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #6366f1, #818cf8);
            border: 3px solid #fff;
            border-radius: 50%;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1001;
            font-size: 0.7rem;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(99,102,241,0.4);
        }
        .sidebar-toggle-btn:hover { transform: scale(1.1); box-shadow: 0 4px 15px rgba(99,102,241,0.6); }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border-radius: var(--radius);
            border: none;
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
            background: var(--bg-card);
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 3px 3px 0 0;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        .stat-card .stat-icon {
            width: 56px; height: 56px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
        }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.5px; line-height: 1; }

        /* ===== BUTTONS ===== */
        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none; border-radius: 12px; font-weight: 600;
            padding: 0.65rem 1.5rem; transition: all 0.3s ease;
            position: relative; overflow: hidden;
        }
        .btn-primary-modern:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,0.35); color: #fff; }
        .btn-primary-modern:active { transform: translateY(0); }
        .btn-primary-modern .ripple-effect {
            position: absolute; border-radius: 50%; background: rgba(255,255,255,0.3); animation: ripple 0.6s ease-out;
        }

        /* ===== FORMS ===== */
        .form-control, .form-select {
            border-radius: var(--radius-xs); border: 1.5px solid #e2e8f0;
            padding: 0.65rem 1rem; transition: all 0.3s ease; background: #fff;
        }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .form-floating > .form-control { border-radius: var(--radius-xs); }

        .input-icon-wrapper { position: relative; }
        .input-icon-wrapper .form-control { padding-left: 2.8rem; padding-right: 2.8rem; }
        .input-icon-wrapper .icon-left { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-light); font-size: 1.1rem; z-index: 5; }
        .input-icon-wrapper .icon-right { position: absolute; right: 0.6rem; top: 50%; transform: translateY(-50%); color: var(--gray-light); cursor: pointer; z-index: 5; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: all 0.2s; }
        .input-icon-wrapper .icon-right:hover { background: #f1f5f9; color: var(--gray); }

        /* ===== TOAST ===== */
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .toast-modern { border: none; border-radius: 14px; box-shadow: 0 10px 40px rgba(0,0,0,0.12); animation: fadeInRight 0.4s ease-out; overflow: hidden; }
        .toast-modern.success { border-left: 4px solid var(--success); }
        .toast-modern.error { border-left: 4px solid var(--danger); }

        /* ===== ALERT ===== */
        .alert-modern {
            border: none; border-radius: 14px; box-shadow: var(--shadow);
            animation: fadeInDown 0.4s ease-out; margin: 1rem 1.5rem;
            display: flex; align-items: center; gap: 12px; padding: 1rem 1.25rem;
        }
        .alert-modern.success { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #065f46; }
        .alert-modern.error { background: linear-gradient(135deg, #fef2f2, #fecaca); color: #991b1b; }
        .alert-modern .alert-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
        .alert-modern.success .alert-icon { background: rgba(16,185,129,0.15); color: var(--success); }
        .alert-modern.error .alert-icon { background: rgba(239,68,68,0.15); color: var(--danger); }

        /* ===== SKELETON LOADING ===== */
        .skeleton { background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%); background-size: 200% 100%; animation: shimmer 1.5s ease-in-out infinite; border-radius: 8px; }

        /* ===== CHART CONTAINER ===== */
        .chart-container { background: var(--bg-card); border-radius: var(--radius); box-shadow: var(--shadow); padding: 1.5rem; transition: all 0.3s; }
        .chart-container:hover { box-shadow: var(--shadow-lg); }

        /* ===== TABLE MODERN ===== */
        .table-modern { border-collapse: separate; border-spacing: 0; }
        .table-modern thead th { background: #f8fafc; border: none; padding: 0.85rem 1rem; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--gray); }
        .table-modern tbody td { padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .table-modern tbody tr { transition: all 0.2s; }
        .table-modern tbody tr:hover { background: #f8fafc; }

        /* ===== BADGE MODERN ===== */
        .badge-modern { padding: 0.35rem 0.8rem; border-radius: 20px; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.3px; }

        /* ===== SECTION ===== */
        .section-title { font-weight: 800; font-size: 1.3rem; letter-spacing: -0.3px; position: relative; display: inline-flex; align-items: center; gap: 10px; }
        .section-title .icon-circle { width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 1rem; }

        /* ===== MAIN CONTENT OFFSET ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem 1.5rem 3rem 1.5rem;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-collapsed .main-content,
        .main-content.sidebar-collapsed-state {
            margin-left: var(--sidebar-collapsed-width);
        }

        .sidebar-backdrop {
            position: fixed; inset: 0; background: rgba(15,23,42,0.55); backdrop-filter: blur(3px);
            z-index: 1035; opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
        }
        .sidebar-backdrop.show { opacity: 1; pointer-events: auto; }

        @media (max-width: 767.98px) {
            .sidebar-admin { transform: translateX(-100%); width: var(--sidebar-width) !important; transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1); }
            .sidebar-admin.show { transform: translateX(0); box-shadow: 0 0 60px rgba(0,0,0,0.5); }
            .main-content { margin-left: 0 !important; padding: 0.85rem 0.85rem 96px; min-width: 0; max-width: 100%; }
            .sidebar-toggle-btn { display: none; }
            body.sidebar-open { overflow: hidden; }

            /* Semua anak konten wajib muat dalam layar */
            .main-content > *,
            .main-content .card,
            .main-content .row,
            .main-content .row > *,
            .main-content .chart-container,
            .main-content .chart-box { min-width: 0; max-width: 100%; }
            .main-content h1, .main-content h2, .main-content h3,
            .main-content h4, .main-content h5, .main-content h6,
            .main-content p, .main-content small, .main-content div,
            .main-content span, .main-content a, .main-content button { overflow-wrap: break-word; }
            .d-flex > * { min-width: 0; }
        }

        /* ===== COUNTER ANIMATION ===== */
        .counter-value { display: inline-block; }

        /* ===== PROGRESS BAR ===== */
        .progress-modern { height: 6px; border-radius: 10px; background: #f1f5f9; overflow: hidden; }
        .progress-modern .bar { height: 100%; border-radius: 10px; background: linear-gradient(90deg, var(--primary), var(--secondary)); transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); }

        /* ===== AVATAR ===== */
        .avatar { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; color: #fff; }

        /* ===== PAGINATION ===== */
        .page-link { border-radius: 10px !important; border: none !important; margin: 0 3px; padding: 0.5rem 0.85rem; font-weight: 600; color: var(--gray); transition: all 0.2s; }
        .page-link:hover, .page-item.active .page-link { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; box-shadow: 0 4px 12px rgba(99,102,241,0.3); }

        /* ===== DARK MODE TOGGLE ===== */
        .dark-mode-toggle {
            cursor: pointer;
            width: 48px; height: 26px;
            background: #64748b;
            border-radius: 13px;
            position: relative;
            transition: all 0.3s;
            border: none;
            padding: 0;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }
        .dark-mode-toggle.active { background: linear-gradient(135deg, #6366f1, #818cf8); }
        .dark-mode-toggle:hover:not(.active) { background: #94a3b8; }
        .dark-mode-toggle::after {
            content: '';
            position: absolute;
            top: 3px; left: 3px;
            width: 20px; height: 20px;
            background: #fff;
            border-radius: 50%;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .dark-mode-toggle.active::after { left: 25px; }

        /* Sidebar admin dark mode toggle special */
        .sidebar-admin .dark-mode-toggle {
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.15);
        }
        .sidebar-admin .dark-mode-toggle.active {
            background: linear-gradient(135deg, #6366f1, #818cf8);
            border-color: rgba(99,102,241,0.5);
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Backdrop sidebar mobile -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
        <!-- Sidebar -->
        <div class="sidebar-admin p-0" id="umkmSidebar">
            <button class="sidebar-toggle-btn" id="sidebarCollapseBtn" title="Toggle sidebar">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="p-4 text-center border-bottom sidebar-brand" style="border-color:rgba(255,255,255,0.06)!important">
                <span class="d-inline-flex align-items-center gap-2">
                    <img src="{{ asset('logo.png') }}" alt="Pangan Cerdas (Panca) Maryadat Kota Tual" style="width:36px;height:36px;border-radius:8px">
                    <span class="sidebar-header-text">
                        <h6 class="fw-bold text-white mb-0" style="font-size:0.95rem">Pangan Cerdas (Panca) Maryadat <span style="background:linear-gradient(135deg,#f59e0b,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Kota Tual</span></h6>
                        <small style="color:rgba(255,255,255,0.5);font-size:0.7rem">UMKM Panel</small>
                    </span>
                </span>
            </div>
            <nav class="nav flex-column py-3 flex-grow-1 d-flex flex-column">
                <div>
                <a class="nav-link {{ request()->routeIs('umkm.dashboard')?'active':'' }}" href="{{ route('umkm.dashboard') }}">
                    <i class="bi bi-grid-1x2"></i><span class="sidebar-text">Dashboard</span>
                </a>
                <a class="nav-link {{ request()->routeIs('umkm.products.*')?'active':'' }}" href="{{ route('umkm.products.index') }}">
                    <i class="bi bi-box-seam"></i><span class="sidebar-text">Produk Saya</span>
                </a>
                <a class="nav-link {{ request()->routeIs('umkm.orders.*')?'active':'' }}" href="{{ route('umkm.orders.index') }}">
                    <i class="bi bi-bag"></i><span class="sidebar-text">Pesanan</span>
                </a>

                <div style="padding:0.5rem 1.25rem;margin-top:0.5rem" class="sidebar-section-label">
                    <small style="color:rgba(255,255,255,0.2);font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;font-weight:700">Toko</small>
                </div>
                <a class="nav-link {{ request()->routeIs('profile.edit')?'active':'' }}" href="{{ route('profile.edit') }}">
                    <i class="bi bi-shop"></i><span class="sidebar-text">Profil Toko</span>
                </a>
                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-globe2"></i><span class="sidebar-text">Lihat Marketplace</span>
                </a>

                {{-- Spacer --}}
                <div class="flex-grow-1"></div>

                {{-- Dark Mode Toggle at bottom --}}
                <div style="padding:0.75rem 20px;margin-top:0.5rem" class="sidebar-section-label">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="sidebar-text" style="color:rgba(255,255,255,0.7);font-size:0.8rem;font-weight:500">
                            <i class="bi bi-moon-stars me-2"></i>Dark Mode
                        </span>
                        <button class="dark-mode-toggle" onclick="toggleDarkMode(this)" title="Toggle Dark/Light Mode" style="background:#1a1a1a;border:1px solid rgba(255,255,255,0.1)"></button>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mx-2 mb-3">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start" style="color:#ef4444;border:1px solid rgba(239,68,68,0.2);background:rgba(239,68,68,0.05)">
                        <i class="bi bi-box-arrow-right"></i><span class="sidebar-text">Keluar</span>
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Mobile header -->
            <div class="d-flex justify-content-between align-items-center mb-4 d-md-none animate-fade-down">
                <div class="d-flex align-items-center gap-2 min-w-0">
                    <button class="btn btn-sm flex-shrink-0" id="sidebarToggle" aria-label="Buka/tutup menu" style="background:#000;color:#fff;border-radius:10px;border:1px solid rgba(255,255,255,0.1);width:38px;height:38px;display:flex;align-items:center;justify-content:center"><i class="bi bi-list fs-5" id="sidebarToggleIcon"></i></button>
                    <span class="fw-bold small text-truncate" style="color:#1e293b"><img src="{{ asset('logo.png') }}" style="width:22px;height:22px;border-radius:6px" class="me-1"><span class="d-none d-sm-inline">Pangan Cerdas (Panca)</span><span class="d-sm-none">PANCA</span></span>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <button class="dark-mode-toggle" onclick="toggleDarkMode(this)" style="width:40px;height:22px" title="Toggle Dark/Light Mode"></button>
                    <a href="{{ route('home') }}" class="btn btn-sm flex-shrink-0" style="background:linear-gradient(135deg,#6366f1,#0ea5e9);color:#fff;border-radius:8px;border:none;width:38px;height:38px;display:flex;align-items:center;justify-content:center"><i class="bi bi-house"></i></a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert-modern success alert-dismissible fade show">
                    <div class="alert-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-modern error alert-dismissible fade show">
                    <div class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div class="flex-grow-1">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Chart.js: lokal dulu (anti-gagal CDN), fallback ke CDN --}}
    <script src="{{ asset('assets/chart.umd.min.js') }}"></script>
    <script>window.Chart || document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js@4"><\/script>');</script>
    <script>
        // ===== DARK MODE =====
        function initDarkMode() {
            const saved = localStorage.getItem('tualmart_dark_mode');
            if (saved === 'true') {
                document.documentElement.classList.add('dark-mode');
                document.querySelectorAll('.dark-mode-toggle').forEach(t => t.classList.add('active'));
            } else {
                document.querySelectorAll('.dark-mode-toggle').forEach(t => t.classList.remove('active'));
            }
        }
        initDarkMode();

        function toggleDarkMode(el) {
            const isDark = document.documentElement.classList.toggle('dark-mode');
            localStorage.setItem('tualmart_dark_mode', isDark);
            document.querySelectorAll('.dark-mode-toggle').forEach(t => t.classList.toggle('active', isDark));
        }

        // Intersection Observer for scroll animations
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    entry.target.classList.add('animate-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        document.querySelectorAll('[data-animate]').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            observer.observe(el);
        });
        const style = document.createElement('style');
        style.textContent = `.animate-visible { opacity:1!important; transform:translateY(0)!important; transition: all 0.6s cubic-bezier(0.4,0,0.2,1); }`;
        document.head.appendChild(style);

        // Ripple effect
        document.querySelectorAll('.btn-primary-modern').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');
                const rect = this.getBoundingClientRect();
                ripple.style.left = (e.clientX - rect.left) + 'px';
                ripple.style.top = (e.clientY - rect.top) + 'px';
                ripple.style.width = ripple.style.height = '10px';
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Counter animation
        document.querySelectorAll('[data-counter]').forEach(el => {
            const target = parseInt(el.getAttribute('data-counter'));
            const prefix = el.getAttribute('data-prefix') || '';
            const suffix = el.getAttribute('data-suffix') || '';
            let current = 0;
            const increment = target / 40;
            const timer = setInterval(() => {
                current += increment;
                if(current >= target) { current = target; clearInterval(timer); }
                el.textContent = prefix + (Number.isInteger(target) ? Math.floor(current).toLocaleString('id-ID') : current.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')) + suffix;
            }, 30);
        });

        // Auto-hide alerts
        document.querySelectorAll('.alert-modern').forEach(a => {
            setTimeout(() => { if(a) a.style.display = 'none'; }, 5000);
        });

        // Sidebar mobile toggle (hamburger + backdrop)
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');
        const sidebar = document.querySelector('.sidebar-admin');
        function closeMobileSidebar() {
            if (!sidebar) return;
            sidebar.classList.remove('show');
            sidebarBackdrop && sidebarBackdrop.classList.remove('show');
            sidebarToggleIcon && sidebarToggleIcon.classList.replace('bi-x-lg', 'bi-list');
            document.body.classList.remove('sidebar-open');
        }
        if(sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = sidebar.classList.toggle('show');
                sidebarBackdrop && sidebarBackdrop.classList.toggle('show', isOpen);
                sidebarToggleIcon && sidebarToggleIcon.classList.toggle('bi-x-lg', isOpen);
                sidebarToggleIcon && sidebarToggleIcon.classList.toggle('bi-list', !isOpen);
                document.body.classList.toggle('sidebar-open', isOpen);
            });
            sidebarBackdrop && sidebarBackdrop.addEventListener('click', closeMobileSidebar);
            document.addEventListener('click', (e) => {
                if (window.innerWidth < 768 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) closeMobileSidebar();
            });
            sidebar.querySelectorAll('a.nav-link').forEach(l => l.addEventListener('click', () => { if (window.innerWidth < 768) closeMobileSidebar(); }));
            window.addEventListener('resize', () => { if (window.innerWidth >= 768) closeMobileSidebar(); });
        }

        // Sidebar collapse toggle (desktop)
        const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
        if(sidebarCollapseBtn && sidebar) {
            // Load saved state
            if (localStorage.getItem('tualmart_sidebar_collapsed') === 'true') {
                sidebar.classList.add('collapsed');
            }
            sidebarCollapseBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('tualmart_sidebar_collapsed', sidebar.classList.contains('collapsed'));
            });
        }

        // ===== INPUT HARGA (format ribuan id-ID — bebas ketik 60.983 / 60983) =====
        document.addEventListener('input', function (e) {
            const el = e.target;
            if (!el.classList || !el.classList.contains('price-input')) return;
            const digitsBefore = el.value.slice(0, el.selectionStart).replace(/\D/g, '').length;
            let digits = el.value.replace(/\D/g, '').replace(/^0+(?=\d)/, '').slice(0, 15);
            el.value = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            let pos = 0, seen = 0;
            for (let i = 0; i < el.value.length && seen < digitsBefore; i++) {
                if (/\d/.test(el.value[i])) seen++;
                pos = i + 1;
            }
            try { el.setSelectionRange(pos, pos); } catch (_) {}
        });
        // Sebelum submit: kirim angka polos tanpa titik (server juga menormalisasi)
        document.addEventListener('submit', function (e) {
            (e.target.querySelectorAll ? e.target.querySelectorAll('input.price-input') : []).forEach(function (el) {
                el.value = el.value.replace(/\D/g, '');
            });
        }, true);
        // Prefill: nilai angka polos → format titik ribuan
        document.querySelectorAll('input.price-input').forEach(function (el) {
            const d = el.value.replace(/\D/g, '');
            if (d && el.value.indexOf('.') === -1) el.value = Number(d).toLocaleString('de-DE');
        });

        // ===== PWA SERVICE WORKER =====
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(() => {});
            });
        }
    </script>

    {{-- 5 icon bottom nav khusus mobile (umkm — link frontend publik) --}}
    @include('frontend.partials.mobile-nav')
    @yield('scripts')
</body>
</html>