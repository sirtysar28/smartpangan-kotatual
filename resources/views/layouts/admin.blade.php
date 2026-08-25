<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'Admin Panel - Pangan Cerdas (Panca) Maryadat Kota Tual') — Layanan Pangan Cerdas</title>
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
            --radius: 16px;
            --radius-sm: 10px;
            --radius-xs: 8px;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 72px;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--text-main); margin: 0; padding: 0; overflow-x: hidden; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--gray-light); border-radius: 10px; }

        /* ===== DARK MODE ===== */
        html.dark-mode { --bg: #0f172a; --bg-card: #1e293b; --text-main: #e2e8f0; --text-muted: #94a3b8; --shadow: 0 4px 20px rgba(0,0,0,0.3); --shadow-lg: 0 10px 40px rgba(0,0,0,0.4); --shadow-sm: 0 1px 3px rgba(0,0,0,0.2); }
        html.dark-mode body { background: var(--bg); color: var(--text-main); }
        html.dark-mode .card, html.dark-mode .chart-container, html.dark-mode .stat-card { background: var(--bg-card); color: var(--text-main); }
        html.dark-mode .table-modern thead th { background: #1a2332; color: var(--text-muted); }
        html.dark-mode .table-modern tbody td { border-bottom-color: #2a3a4e; color: var(--text-main); }
        html.dark-mode .table-modern tbody tr:hover { background: #253344; }
        html.dark-mode .form-control, html.dark-mode .form-select { background: #1e293b; border-color: #334155; color: #e2e8f0; }
        html.dark-mode .form-control:focus, html.dark-mode .form-select:focus { border-color: var(--primary); background: #1e293b; color: #e2e8f0; }
        html.dark-mode .text-muted { color: var(--text-muted) !important; }
        html.dark-mode .text-dark { color: var(--text-main) !important; }
        html.dark-mode .badge-modern { background: rgba(99,102,241,0.15); }
        html.dark-mode .main-content { background: var(--bg); }
        html.dark-mode .alert-modern { background: var(--bg-card); color: var(--text-main); }
        html.dark-mode .section-title { color: var(--text-main); }
        html.dark-mode .small, html.dark-mode small { color: var(--text-muted); }
        html.dark-mode h4, html.dark-mode h5, html.dark-mode h6 { color: var(--text-main); }
        html.dark-mode .fw-semibold, html.dark-mode .fw-bold { color: var(--text-main); }
        html.dark-mode .page-link { background: var(--bg-card); color: var(--text-muted); }
        html.dark-mode .dropdown-menu { background: var(--bg-card); border-color: #334155; }
        html.dark-mode .dropdown-item { color: var(--text-main); }
        html.dark-mode .dropdown-item:hover { background: rgba(99,102,241,0.1); }
        html.dark-mode .skeleton { background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%); }
        html.dark-mode .commodity-card { background: var(--bg-card); border-color: #334155 !important; }
        html.dark-mode .commodity-card h6, html.dark-mode .commodity-card .fw-bold { color: var(--text-main) !important; }
        html.dark-mode .commodity-card small { color: var(--text-muted) !important; }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeInDown { from { opacity:0; transform:translateY(-20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes float { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-10px); } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        @keyframes ripple { 0% { transform: scale(0); opacity: 0.5; } 100% { transform: scale(4); opacity: 0; } }
        @keyframes pulse-glow { 0%,100% { box-shadow: 0 0 5px rgba(99,102,241,0.2); } 50% { box-shadow: 0 0 25px rgba(99,102,241,0.4); } }
        .animate-fade-up { animation: fadeInUp 0.6s ease-out both; }
        .animate-fade-down { animation: fadeInDown 0.5s ease-out both; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; } .delay-4 { animation-delay: 0.4s; } .delay-5 { animation-delay: 0.5s; } .delay-6 { animation-delay: 0.6s; }

        /* ===== SIDEBAR ===== */
        .sidebar-admin {
            min-height: 100vh; background: linear-gradient(180deg, #0a0a0a 0%, #111 50%, #0a0a0a 100%);
            border-right: 1px solid rgba(255,255,255,0.06); position: fixed; width: var(--sidebar-width);
            left: 0; top: 0; z-index: 1040; overflow-y: auto; overflow-x: hidden; height: 100vh;
            display: flex; flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-admin::-webkit-scrollbar { width: 4px; }
        .sidebar-admin::-webkit-scrollbar-track { background: #000; }
        .sidebar-admin::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.3); border-radius: 10px; }
        .sidebar-admin.collapsed { width: var(--sidebar-collapsed-width); }
        .sidebar-admin.collapsed .sidebar-text { display: none; }
        .sidebar-admin.collapsed .sidebar-header-text { display: none; }
        .sidebar-admin.collapsed .sidebar-section-label { display: none; }
        .sidebar-admin.collapsed .sidebar-brand { justify-content: center; }
        .sidebar-admin.collapsed .nav-link { justify-content: center; padding: 11px 0; margin: 2px 8px; }
        .sidebar-admin.collapsed .nav-link i { margin: 0; font-size: 1.2rem; }
        .sidebar-admin .nav-link {
            color: rgba(255,255,255,0.6); padding: 10px 16px; border-radius: 10px; margin: 1px 10px;
            transition: all 0.25s ease; font-weight: 500; font-size: 0.85rem;
            display: flex; align-items: center; gap: 11px; white-space: nowrap; text-decoration: none;
            border: 1px solid transparent;
        }
        .sidebar-admin .nav-link:hover { color: #fff; background: rgba(99,102,241,0.12); border-color: rgba(99,102,241,0.15); }
        .sidebar-admin .nav-link.active {
            color: #fff; background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 4px 15px rgba(99,102,241,0.35); border-color: rgba(99,102,241,0.3);
        }
        .sidebar-admin .nav-link i { font-size: 1.05rem; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar-admin .sidebar-section-label { padding: 0.6rem 1.1rem; margin-top: 0.8rem; }
        .sidebar-admin .sidebar-section-label small { color: rgba(255,255,255,0.25); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1.2px; font-weight: 700; }
        .sidebar-admin .sidebar-brand { border-bottom: 1px solid rgba(255,255,255,0.06); }
        .sidebar-admin .sidebar-brand h6 { color: #fff !important; }
        .sidebar-admin .sidebar-brand small { color: rgba(255,255,255,0.4) !important; }
        .sidebar-toggle-btn {
            position: absolute; top: 22px; right: -14px; width: 28px; height: 28px;
            background: linear-gradient(135deg, #6366f1, #818cf8); border: 3px solid var(--bg-card);
            border-radius: 50%; color: #fff; display: flex; align-items: center; justify-content: center;
            cursor: pointer; z-index: 1041; font-size: 0.65rem; transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(99,102,241,0.4);
        }
        .sidebar-toggle-btn:hover { transform: scale(1.15); }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border-radius: var(--radius); border: none; box-shadow: var(--shadow);
            transition: all 0.3s ease; overflow: hidden; position: relative; background: var(--bg-card);
        }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:3px 3px 0 0; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
        .stat-card .stat-icon { width:50px; height:50px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; }
        .stat-card .stat-value { font-size:1.6rem; font-weight:800; letter-spacing:-0.5px; line-height:1; }

        /* ===== BUTTONS ===== */
        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none;
            border-radius: 10px; font-weight: 600; padding: 0.6rem 1.3rem; transition: all 0.3s;
        }
        .btn-primary-modern:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,0.35); color: #fff; }

        /* ===== FORMS ===== */
        .form-control, .form-select { border-radius: var(--radius-xs); border: 1.5px solid #e2e8f0; padding: 0.6rem 0.9rem; transition: all 0.3s; background: #fff; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .input-icon-wrapper { position: relative; }
        .input-icon-wrapper .form-control { padding-left: 2.6rem; }
        .input-icon-wrapper .icon-left { position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%); color: var(--gray-light); font-size: 1rem; z-index: 5; }
        .input-icon-wrapper .input-icon-left { position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%); color: var(--gray-light); font-size: 0.8rem; font-weight: 600; z-index: 5; pointer-events: none; }
        .input-icon-wrapper .icon-right { position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); color: var(--gray-light); cursor: pointer; z-index: 5; width:34px; height:34px; display:flex; align-items:center; justify-content:center; border-radius:8px; transition:all 0.2s; }
        .input-icon-wrapper .icon-right:hover { background: #f1f5f9; }

        /* ===== ALERT ===== */
        .alert-modern { border:none; border-radius:var(--radius); box-shadow:var(--shadow); animation:fadeInDown 0.4s ease-out; display:flex; align-items:center; gap:12px; padding:1rem 1.2rem; }
        .alert-modern.success { background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#065f46; }
        .alert-modern.error { background:linear-gradient(135deg,#fef2f2,#fecaca); color:#991b1b; }
        .alert-modern .alert-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
        .alert-modern.success .alert-icon { background:rgba(16,185,129,0.15); color:var(--success); }
        .alert-modern.error .alert-icon { background:rgba(239,68,68,0.15); color:var(--danger); }

        /* ===== TABLE ===== */
        .table-modern { border-collapse: separate; border-spacing: 0; }
        .table-modern thead th { background: #f8fafc; border:none; padding:0.8rem 0.9rem; font-weight:600; font-size:0.78rem; text-transform:uppercase; letter-spacing:0.5px; color:var(--gray); }
        .table-modern tbody td { padding:0.8rem 0.9rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
        .table-modern tbody tr { transition:all 0.2s; }
        .table-modern tbody tr:hover { background: #f8fafc; }

        /* ===== BADGE ===== */
        .badge-modern { padding:0.3rem 0.7rem; border-radius:20px; font-weight:600; font-size:0.72rem; letter-spacing:0.3px; }
        .section-title { font-weight:800; font-size:1.2rem; letter-spacing:-0.3px; display:inline-flex; align-items:center; gap:10px; }
        .section-title .icon-circle { width:36px; height:36px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; font-size:0.95rem; }

        /* ===== CHART ===== */
        .chart-container { background:var(--bg-card); border-radius:var(--radius); box-shadow:var(--shadow); padding:1.5rem; transition:all 0.3s; }
        .chart-container:hover { box-shadow:var(--shadow-lg); }

        /* ===== COMMODITY CARD ===== */
        .commodity-card { background:var(--bg-card); border:1px solid #e2e8f0; border-radius:var(--radius); padding:0.9rem; transition:all 0.3s; }
        .commodity-card:hover { border-color:var(--primary); box-shadow:0 4px 15px rgba(99,102,241,0.1); transform:translateY(-2px); }
        .commodity-card .price-up { color:#ef4444; }
        .commodity-card .price-down { color:#10b981; }
        .commodity-card .price-stable { color:#0ea5e9; }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width); padding: 1.5rem; min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .main-content.sidebar-collapsed-state { margin-left: var(--sidebar-collapsed-width); }
        @media (max-width: 767.98px) {
            .sidebar-admin { transform: translateX(-100%); width: var(--sidebar-width) !important; }
            .sidebar-admin.show { transform: translateX(0); box-shadow: 0 0 60px rgba(0,0,0,0.5); }
            .main-content { margin-left: 0 !important; }
            .sidebar-toggle-btn { display: none; }
        }

        /* ===== AVATAR ===== */
        .avatar { border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:700; color:#fff; }

        /* ===== PAGINATION ===== */
        .page-link { border-radius:8px !important; border:none !important; margin:0 2px; padding:0.4rem 0.75rem; font-weight:600; color:var(--gray); transition:all 0.2s; font-size:0.85rem; }
        .page-link:hover, .page-item.active .page-link { background:linear-gradient(135deg,var(--primary),var(--secondary)); color:#fff; box-shadow:0 4px 12px rgba(99,102,241,0.3); }

        /* ===== DARK MODE TOGGLE ===== */
        .dark-mode-toggle {
            cursor:pointer; width:44px; height:24px; background:#334155; border-radius:12px;
            position:relative; transition:all 0.3s; border:none; padding:0;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.3);
        }
        .dark-mode-toggle.active { background:linear-gradient(135deg,#6366f1,#818cf8); }
        .dark-mode-toggle::after {
            content:''; position:absolute; top:2px; left:2px; width:20px; height:20px;
            background:#fff; border-radius:50%; transition:all 0.3s; box-shadow:0 1px 3px rgba(0,0,0,0.2);
        }
        .dark-mode-toggle.active::after { left:22px; }

        /* ===== SKELETON ===== */
        .skeleton { background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%); background-size:200% 100%; animation:shimmer 1.5s ease-in-out infinite; border-radius:8px; }

        /* ===== PROGRESS ===== */
        .progress-modern { height:6px; border-radius:10px; background:#f1f5f9; overflow:hidden; }
        .progress-modern .bar { height:100%; border-radius:10px; background:linear-gradient(90deg,var(--primary),var(--secondary)); transition:width 1.5s cubic-bezier(0.4,0,0.2,1); }

        @yield('styles')
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- SIDEBAR -->
        <div class="sidebar-admin p-0 d-none d-md-block" id="adminSidebar">
            <button class="sidebar-toggle-btn" id="sidebarCollapseBtn" title="Toggle sidebar"><i class="bi bi-chevron-left"></i></button>
            <div class="p-3 text-center border-bottom sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                    <span class="d-inline-flex align-items-center gap-2">
                        <img src="{{ asset('logo.png') }}" alt="Pangan Cerdas (Panca) Maryadat Kota Tual" style="width:32px;height:32px;border-radius:8px">
                        <span class="sidebar-header-text">
                            <h6 class="fw-bold text-white mb-0" style="font-size:0.9rem">Pangan Cerdas (Panca) Maryadat <span style="background:linear-gradient(135deg,#f59e0b,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Kota Tual</span></h6>
                            <small style="color:rgba(255,255,255,0.4);font-size:0.65rem">Admin Panel</small>
                        </span>
                    </span>
                </a>
            </div>
            <nav class="nav flex-column py-2 flex-grow-1 d-flex flex-column">
                <div>
                <a class="nav-link {{ request()->routeIs('admin.dashboard')?'active':'' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2"></i><span class="sidebar-text">Dashboard</span>
                </a>

                <div class="sidebar-section-label">
                    <small>Pangan Cerdas</small>
                </div>
                <a class="nav-link {{ request()->routeIs('admin.commodities.*')?'active':'' }}" href="{{ route('admin.commodities.index') }}">
                    <i class="bi bi-tag"></i><span class="sidebar-text">Panel Harga</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.availability.*')?'active':'' }}" href="{{ route('admin.availability.index') }}">
                    <i class="bi bi-box-seam"></i><span class="sidebar-text">Ketersediaan</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.food-vulnerability.*')?'active':'' }}" href="{{ route('admin.food-vulnerability.index') }}">
                    <i class="bi bi-exclamation-triangle"></i><span class="sidebar-text">Kerawanan Pangan</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.food-safety.*')?'active':'' }}" href="{{ route('admin.food-safety.index') }}">
                    <i class="bi bi-shield-check"></i><span class="sidebar-text">Keamanan Pangan</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.permits.*') || request()->routeIs('frontend.permits') ?'active':'' }}" href="{{ route('admin.permits.index') }}">
                    <i class="bi bi-file-earmark-text"></i><span class="sidebar-text">Perizinan Pangan</span>
                </a>

                {{-- === MARKETPLACE DINONAKTIFKAN (revisi 7 Juli 2026) ===
                <div class="sidebar-section-label">
                    <small>Marketplace</small>
                </div>
                <a class="nav-link {{ request()->routeIs('admin.umkms.*')?'active':'' }}" href="{{ route('admin.umkms.index') }}">
                    <i class="bi bi-shop"></i><span class="sidebar-text">Kelola UMKM</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.categories.*')?'active':'' }}" href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-grid"></i><span class="sidebar-text">Kategori</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.orders.*')?'active':'' }}" href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-bag"></i><span class="sidebar-text">Pesanan</span>
                </a>
                --}}

                <div class="sidebar-section-label">
                    <small>AI & Analytics</small>
                </div>
                <a class="nav-link {{ request()->routeIs('admin.ai-dashboard.*')?'active':'' }}" href="{{ route('admin.ai-dashboard.index') }}">
                    <i class="bi bi-robot"></i><span class="sidebar-text">AI Dashboard</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.ai-dashboard.inflation')?'active':'' }}" href="{{ route('admin.ai-dashboard.inflation') }}">
                    <i class="bi bi-bar-chart-line"></i><span class="sidebar-text">Inflasi</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.ai-dashboard.economy')?'active':'' }}" href="{{ route('admin.ai-dashboard.economy') }}">
                    <i class="bi bi-cash-coin"></i><span class="sidebar-text">Ekonomi</span>
                </a>

                <div class="sidebar-section-label">
                    <small>Lainnya</small>
                </div>
                <a class="nav-link {{ request()->routeIs('admin.reports.*')?'active':'' }}" href="{{ route('admin.reports.index') }}">
                    <i class="bi bi-file-earmark-bar-graph"></i><span class="sidebar-text">Laporan</span>
                </a>
                {{-- Marketplace disembunyikan (revisi 7 Juli 2026)
                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-globe2"></i><span class="sidebar-text">Marketplace</span>
                </a>
                --}}
                <a class="nav-link {{ request()->routeIs('admin.settings.*')?'active':'' }}" href="{{ route('admin.settings.index') }}">
                    <i class="bi bi-gear"></i><span class="sidebar-text">Pengaturan</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.site-pages.*')?'active':'' }}" href="{{ route('admin.site-pages.index') }}">
                    <i class="bi bi-file-earmark-text"></i><span class="sidebar-text">Kelola Halaman</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.faqs.*') ?'active':'' }}" href="{{ route('admin.faqs.index') }}">
                    <i class="bi bi-patch-question"></i><span class="sidebar-text">FAQ Perizinan</span>
                </a>

                @if(auth()->check() && auth()->user()->isSuperAdmin())
                <div class="sidebar-section-label">
                    <small>Super Admin</small>
                </div>
                <a class="nav-link {{ request()->routeIs('admin.users.*')?'active':'' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i><span class="sidebar-text">Manage User</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.activity-logs.*')?'active':'' }}" href="{{ route('admin.activity-logs.index') }}">
                    <i class="bi bi-clock-history"></i><span class="sidebar-text">Aktivitas Log</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.backup.*')?'active':'' }}" href="{{ route('admin.backup.index') }}">
                    <i class="bi bi-database"></i><span class="sidebar-text">Backup & Restore</span>
                </a>
                @endif

                <div class="flex-grow-1"></div>

                <div style="padding:0.6rem 16px;margin-top:0.5rem">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="sidebar-text" style="color:rgba(255,255,255,0.6);font-size:0.78rem;font-weight:500">
                            <i class="bi bi-moon-stars me-1"></i>Dark Mode
                        </span>
                        <button class="dark-mode-toggle" onclick="toggleDarkMode(this)" title="Toggle Dark/Light Mode"></button>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" id="logoutForm" class="mx-2 mb-3">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start" style="color:#ef4444;border:1px solid rgba(239,68,68,0.2);background:rgba(239,68,68,0.05)">
                        <i class="bi bi-box-arrow-right"></i><span class="sidebar-text">Keluar</span>
                    </button>
                </form>
            </nav>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content flex-grow-1">
            <!-- Mobile header -->
            <div class="d-flex justify-content-between align-items-center mb-3 d-md-none">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm" id="sidebarToggle" style="background:#0f172a;color:#fff;border-radius:8px;border:1px solid rgba(255,255,255,0.1);width:36px;height:36px;display:flex;align-items:center;justify-content:center"><i class="bi bi-list"></i></button>
                    <span class="fw-bold small"><img src="{{ asset('logo.png') }}" style="width:22px;height:22px;border-radius:6px" class="me-1"> Pangan Cerdas (Panca) Maryadat Kota Tual</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="dark-mode-toggle" onclick="toggleDarkMode(this)" style="width:40px;height:22px" title="Toggle Dark/Light Mode"></button>
                    <a href="{{ route('home') }}" class="btn btn-sm" style="background:linear-gradient(135deg,#6366f1,#0ea5e9);color:#fff;border-radius:8px;border:none;width:36px;height:36px;display:flex;align-items:center;justify-content:center"><i class="bi bi-house"></i></a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm" style="background:#ef4444;color:#fff;border-radius:8px;border:none;width:36px;height:36px;display:flex;align-items:center;justify-content:center" title="Keluar"><i class="bi bi-box-arrow-right"></i></button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert-modern success alert-dismissible fade show mb-3">
                    <div class="alert-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-modern error alert-dismissible fade show mb-3">
                    <div class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div class="flex-grow-1">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
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

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if(entry.isIntersecting) { entry.target.classList.add('animate-visible'); observer.unobserve(entry.target); } });
        }, { threshold: 0.1 });
        document.querySelectorAll('[data-animate]').forEach(el => { el.style.opacity='0'; el.style.transform='translateY(20px)'; observer.observe(el); });
        const st = document.createElement('style');
        st.textContent = '.animate-visible{opacity:1!important;transform:translateY(0)!important;transition:all 0.5s cubic-bezier(0.4,0,0.2,1)}';
        document.head.appendChild(st);

        document.querySelectorAll('.btn-primary-modern').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const r = document.createElement('span'); r.classList.add('ripple-effect');
                const rect = this.getBoundingClientRect(); r.style.left=(e.clientX-rect.left)+'px'; r.style.top=(e.clientY-rect.top)+'px'; r.style.width=r.style.height='10px';
                this.appendChild(r); setTimeout(()=>r.remove(),600);
            });
        });

        document.querySelectorAll('[data-counter]').forEach(el => {
            const target=parseInt(el.getAttribute('data-counter')), prefix=el.getAttribute('data-prefix')||'', suffix=el.getAttribute('data-suffix')||'';
            let current=0; const increment=target/40;
            const timer=setInterval(()=>{ current+=increment; if(current>=target){current=target;clearInterval(timer);} el.textContent=prefix+Math.floor(current).toLocaleString('id-ID')+suffix; },30);
        });

        document.querySelectorAll('.alert-modern').forEach(a=>{ setTimeout(()=>{if(a)a.style.display='none';},5000); });

        const sidebarToggle=document.getElementById('sidebarToggle'), sidebar=document.querySelector('.sidebar-admin');
        if(sidebarToggle&&sidebar){
            sidebarToggle.addEventListener('click',()=>sidebar.classList.toggle('show'));
            document.addEventListener('click',(e)=>{ if(!sidebar.contains(e.target)&&!sidebarToggle.contains(e.target))sidebar.classList.remove('show'); });
        }
        const sidebarCollapseBtn=document.getElementById('sidebarCollapseBtn');
        if(sidebarCollapseBtn&&sidebar){
            if(localStorage.getItem('tualmart_sidebar_collapsed')==='true')sidebar.classList.add('collapsed');
            sidebarCollapseBtn.addEventListener('click',()=>{
                sidebar.classList.toggle('collapsed');
                document.querySelector('.main-content').classList.toggle('sidebar-collapsed-state');
                localStorage.setItem('tualmart_sidebar_collapsed',sidebar.classList.contains('collapsed'));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>