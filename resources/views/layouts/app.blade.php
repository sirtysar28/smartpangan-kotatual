<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'Pangan Cerdas (Panca) Maryadat Kota Tual - Monitoring Harga Pangan & Marketplace Digital')</title>
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
        /* Sidebar admin - always dark theme */
        .sidebar-admin { background: linear-gradient(180deg, #000000 0%, #0a0a0a 50%, #111111 100%) !important; }
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

        /* Frontend dark mode */
        html.dark-mode section[style*="background:#fff"],
        html.dark-mode section[style*="background: #fff"] { background: var(--bg-card) !important; }
        html.dark-mode .card[style*="background:#fff"],
        html.dark-mode .card[style*="background: #fff"] { background: var(--bg-card) !important; }
        html.dark-mode .card.border-0 { background: var(--bg-card); }
        html.dark-mode footer { background: #050a12 !important; }

        /* Commodity cards dark mode */
        html.dark-mode .commodity-card { background: var(--bg-card); border-color: #334155 !important; }
        html.dark-mode .commodity-card small { color: var(--text-muted) !important; }
        html.dark-mode .commodity-card h6,
        html.dark-mode .commodity-card .fw-bold { color: var(--text-main) !important; }

        /* Chat dark mode */
        html.dark-mode .chat-widget { background: var(--bg-card); }
        html.dark-mode .chat-messages { background: #0a0f1a; }
        html.dark-mode .chat-messages .msg-bot { background: #1e293b; color: var(--text-main); }
        html.dark-mode .chat-input-area { background: var(--bg-card); border-top-color: #334155; }

        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); overflow-x: hidden; color: var(--text-main); }
        /* FIX GLOBAL: Tailwind .collapse{visibility:collapse} konflik dgn Bootstrap accordion.
           Tanpa ini, teks di dalam akordion yang terbuka jadi invisible. */
        .accordion-collapse { visibility: visible !important; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--gray-light); border-radius: 10px; }

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

        /* ===== NAVBAR ===== */
        /* ===== NAVBAR (managed in navbar partial) ===== */

        /* ===== HERO ===== */
        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 30%, #312e81 60%, #4338ca 100%);
            background-size: 200% 200%;
            animation: gradient-shift 8s ease infinite;
            position: relative;
            overflow: hidden;
        }
        .hero-gradient::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(99,102,241,0.15) 0%, transparent 50%),
                        radial-gradient(circle at 70% 80%, rgba(14,165,233,0.1) 0%, transparent 40%);
            animation: float 8s ease-in-out infinite;
        }

        /* ===== GLASSMORPHISM ===== */
        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: rgba(255,255,255,0.3); }

        /* ===== CARDS ===== */
        .card-product {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            border-radius: var(--radius);
            overflow: hidden;
            background: var(--bg-card);
        }
        .card-product:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .card-product .product-img { height: 220px; object-fit: cover; transition: transform 0.6s ease; }
        .card-product:hover .product-img { transform: scale(1.05); }
        .card-product .img-wrapper { overflow: hidden; border-radius: var(--radius) var(--radius) 0 0; }
        .badge-discount { position: absolute; top: 12px; right: 12px; z-index: 2; border-radius: 20px; font-weight: 600; }
        .price-original { text-decoration: line-through; color: var(--gray-light); font-size: .85rem; }

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

        .btn-glass {
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px); color: #fff; border-radius: 12px;
            font-weight: 600; transition: all 0.3s;
        }
        .btn-glass:hover { background: rgba(255,255,255,0.2); color: #fff; }

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

        @media (max-width: 767.98px) {
            .sidebar-admin { transform: translateX(-100%); width: var(--sidebar-width) !important; }
            .sidebar-admin.show { transform: translateX(0); box-shadow: 0 0 60px rgba(0,0,0,0.5); }
            .main-content { margin-left: 0 !important; }
            .sidebar-toggle-btn { display: none; }
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

        /* ===== CHAT WIDGET ===== */
        .chat-widget {
            position: fixed;
            bottom: 160px;
            right: 24px;
            width: 380px;
            max-height: 500px;
            background: #fff;
            border-radius: var(--radius);
            box-shadow: 0 10px 50px rgba(0,0,0,0.15);
            z-index: 9998;
            display: none;
            flex-direction: column;
            overflow: hidden;
        }
        .chat-widget.show { display: flex; }
        .chat-widget .chat-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chat-messages {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
            max-height: 340px;
            background: #f8fafc;
        }
        .chat-messages .msg {
            margin-bottom: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.875rem;
            line-height: 1.5;
            max-width: 85%;
            word-wrap: break-word;
        }
        .chat-messages .msg-bot {
            background: #fff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-radius: 12px 12px 12px 4px;
        }
        .chat-messages .msg-user {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            margin-left: auto;
            border-radius: 12px 12px 4px 12px;
        }
        .chat-input-area {
            padding: 0.75rem;
            display: flex;
            gap: 0.5rem;
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }
        .chat-input-area input {
            flex: 1;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            outline: none;
        }
        .chat-input-area input:focus { border-color: var(--primary); }
        .chat-input-area button {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none; color: #fff; border-radius: 10px;
            width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.3s;
        }
        .chat-input-area button:hover { transform: scale(1.05); }
        .chat-btn {
            position: fixed;
            bottom: 24px; right: 24px;
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none; color: #fff; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; cursor: pointer; z-index: 9997;
            box-shadow: 0 4px 20px rgba(99,102,241,0.4);
            transition: all 0.3s;
        }
        .chat-btn:hover { transform: scale(1.1); box-shadow: 0 6px 30px rgba(99,102,241,0.5); }

        /* ===== COMMODITY CARDS ===== */
        .commodity-card {
            background: var(--bg-card);
            border: 1px solid #e2e8f0;
            border-radius: var(--radius);
            padding: 1rem;
            transition: all 0.3s;
        }
        .commodity-card:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 15px rgba(99,102,241,0.1);
            transform: translateY(-2px);
        }
        .commodity-card .price-up { color: #ef4444; }
        .commodity-card .price-down { color: #10b981; }
        .commodity-card .price-stable { color: #64748b; }
    </style>
    @yield('styles')
</head>
<body>
    @include('frontend.partials.navbar')

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

    @if(!auth()->check() || (auth()->check() && auth()->user()->isBuyer()))
        @include('frontend.partials.footer')
    @endif

    {{-- Chat Widget (only on frontpage for guest/buyer) --}}
    @if(!auth()->check() || (auth()->check() && auth()->user()->isBuyer()))
    @php
        $waNumber = \App\Models\Setting::get('whatsapp_number', '');
        $waClean = preg_replace('/[^0-9]/', '', $waNumber);
        if (substr($waClean, 0, 1) === '0') $waClean = '62' . substr($waClean, 1);
    @endphp
    <button class="chat-btn" id="chatToggleBtn" title="Chat dengan AI" style="bottom:90px">
        <i class="bi bi-robot"></i>
    </button>
    @if($waClean)
    <a href="https://wa.me/{{$waClean}}?text={{ urlencode('Halo Admin Pangan Cerdas (Panca) Maryadat Kota Tual, saya ingin bertanya tentang ') }}" target="_blank" class="chat-btn" id="waFloatBtn" title="Chat WhatsApp" style="background:linear-gradient(135deg,#25d366,#128c7e);box-shadow:0 4px 20px rgba(37,211,102,0.4)">
        <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>
    @endif
    <div class="chat-widget" id="chatWidget">
        <div class="chat-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-robot fs-5"></i>
                <div>
                    <div class="fw-bold" style="font-size:0.9rem">Pangan Cerdas (Panca) Maryadat Assistant</div>
                    <small style="opacity:0.7;font-size:0.7rem">Powered by Groq AI</small>
                </div>
            </div>
            <button class="btn btn-sm" style="color:#fff;background:rgba(255,255,255,0.15);border-radius:8px" id="chatCloseBtn"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="msg msg-bot">
                👋 Halo! Saya asisten AI Pangan Cerdas (Panca) Maryadat Kota Tual. Saya bisa membantu Anda dengan informasi harga pangan, ketersediaan stok, kerawanan pangan, keamanan pangan, data UMKM, dan perizinan di Kota Tual. Ada yang bisa saya bantu?
            </div>
        </div>
        <div class="chat-input-area">
            <input type="text" id="chatInput" placeholder="Ketik pesan..." autocomplete="off">
            <button id="chatSendBtn"><i class="bi bi-send"></i></button>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
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

        // Navbar scroll effect
        const navbar = document.querySelector('.navbar-tual');
        if(navbar) {
            window.addEventListener('scroll', () => {
                navbar.classList.toggle('scrolled', window.scrollY > 50);
            });
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

        // Sidebar mobile toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar-admin');
        if(sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('show'));
            document.addEventListener('click', (e) => {
                if(!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) sidebar.classList.remove('show');
            });
        }

        // Sidebar collapse toggle (desktop)
        const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
        if(sidebarCollapseBtn && sidebar) {
            const mainContent = document.querySelector('.main-content');
            // Load saved state
            if (localStorage.getItem('tualmart_sidebar_collapsed') === 'true') {
                sidebar.classList.add('collapsed');
                if(mainContent) mainContent.classList.add('sidebar-collapsed-state');
            }
            sidebarCollapseBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                if(mainContent) mainContent.classList.toggle('sidebar-collapsed-state');
                localStorage.setItem('tualmart_sidebar_collapsed', sidebar.classList.contains('collapsed'));
            });
        }

        // ===== CHAT WIDGET =====
        const chatToggleBtn = document.getElementById('chatToggleBtn');
        const chatWidget = document.getElementById('chatWidget');
        const chatCloseBtn = document.getElementById('chatCloseBtn');
        const chatInput = document.getElementById('chatInput');
        const chatSendBtn = document.getElementById('chatSendBtn');
        const chatMessages = document.getElementById('chatMessages');

        if(chatToggleBtn && chatWidget) {
            chatToggleBtn.addEventListener('click', () => {
                chatWidget.classList.toggle('show');
                chatToggleBtn.style.display = chatWidget.classList.contains('show') ? 'none' : 'flex';
            });
            chatCloseBtn.addEventListener('click', () => {
                chatWidget.classList.remove('show');
                chatToggleBtn.style.display = 'flex';
            });
        }

        function addChatMessage(text, isUser) {
            const div = document.createElement('div');
            div.className = 'msg ' + (isUser ? 'msg-user' : 'msg-bot');
            div.textContent = text;
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        async function sendChat() {
            const msg = chatInput.value.trim();
            if(!msg) return;
            addChatMessage(msg, true);
            chatInput.value = '';

            // Typing indicator
            const typing = document.createElement('div');
            typing.className = 'msg msg-bot';
            typing.innerHTML = '<i class="bi bi-three-dots"></i> Mengetik...';
            chatMessages.appendChild(typing);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            try {
                const res = await fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: msg })
                });
                const data = await res.json();
                typing.remove();
                addChatMessage(data.response || data.error || 'Maaf, terjadi kesalahan.', false);
            } catch(e) {
                typing.remove();
                addChatMessage('Gagal menghubungi AI. Silakan coba lagi.', false);
            }
        }

        if(chatSendBtn) {
            chatSendBtn.addEventListener('click', sendChat);
            chatInput.addEventListener('keypress', (e) => { if(e.key === 'Enter') sendChat(); });
        }
    </script>
    @yield('scripts')
</body>
</html>
