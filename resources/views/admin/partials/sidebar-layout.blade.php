<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="sidebar-admin p-0 d-none d-md-block" id="adminSidebar">
            <button class="sidebar-toggle-btn" id="sidebarCollapseBtn" title="Toggle sidebar">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="p-4 text-center border-bottom sidebar-brand" style="border-color:rgba(255,255,255,0.06)!important">
                <span class="d-inline-flex align-items-center gap-2">
                    <img src="{{ asset('logo.png') }}" alt="Pangan Cerdas (Panca) Maryadat Kota Tual" style="width:36px;height:36px;border-radius:8px">
                    <img src="{{ asset('Lambang_Kota_Tual.png') }}" alt="Lambang Kota Tual" style="width:28px;height:28px;border-radius:8px;opacity:0.9">
                    <span class="sidebar-header-text">
                        <h6 class="fw-bold text-white mb-0" style="font-size:0.95rem">Pangan Cerdas (Panca) Maryadat <span style="background:linear-gradient(135deg,#f59e0b,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Kota Tual</span></h6>
                        <small style="color:rgba(255,255,255,0.5);font-size:0.7rem">Admin Panel</small>
                    </span>
                </span>
            </div>
            <nav class="nav flex-column py-3 d-flex" style="min-height:100vh">
                <div>
                <a class="nav-link {{ request()->routeIs('admin.dashboard')?'active':'' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2"></i><span class="sidebar-text">Dashboard</span>
                </a>
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
                {{-- Marketplace UMKM dinonaktifkan (revisi 7 Juli 2026)
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

                <div style="padding:0.5rem 1.25rem;margin-top:0.5rem" class="sidebar-section-label">
                    <small style="color:rgba(255,255,255,0.2);font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;font-weight:700">AI & Analytics</small>
                </div>
                <a class="nav-link {{ request()->routeIs('admin.ai-dashboard.index')?'active':'' }}" href="{{ route('admin.ai-dashboard.index') }}">
                    <i class="bi bi-robot"></i><span class="sidebar-text">AI Dashboard</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.ai-dashboard.inflation')?'active':'' }}" href="{{ route('admin.ai-dashboard.inflation') }}">
                    <i class="bi bi-bar-chart-line"></i><span class="sidebar-text">Inflasi</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.ai-dashboard.economy')?'active':'' }}" href="{{ route('admin.ai-dashboard.economy') }}">
                    <i class="bi bi-cash-coin"></i><span class="sidebar-text">Ekonomi</span>
                </a>

                <div style="padding:0.5rem 1.25rem;margin-top:0.5rem" class="sidebar-section-label">
                    <small style="color:rgba(255,255,255,0.2);font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;font-weight:700">Lainnya</small>
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

                {{-- Super Admin Only Menus --}}
                @if(auth()->check() && auth()->user()->isSuperAdmin())
                <div style="padding:0.5rem 1.25rem;margin-top:0.5rem" class="sidebar-section-label">
                    <small style="color:rgba(255,255,255,0.2);font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;font-weight:700">Super Admin</small>
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

                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit()" style="color:#ef4444;border:1px solid rgba(239,68,68,0.2);background:rgba(239,68,68,0.05)">
                    <i class="bi bi-box-arrow-right"></i><span class="sidebar-text">Keluar</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-none">@csrf</form>
                <form method="POST" action="{{ route('logout') }}" class="d-none">@csrf</form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Mobile header -->
            <div class="d-flex justify-content-between align-items-center mb-4 d-md-none animate-fade-down">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm" id="sidebarToggle" style="background:#000;color:#fff;border-radius:10px;border:1px solid rgba(255,255,255,0.1)"><i class="bi bi-list"></i></button>
                    <span class="fw-bold" style="color:#1e293b"><img src="{{ asset('logo.png') }}" style="width:24px;height:24px;border-radius:6px"> Pangan Cerdas (Panca) Maryadat Kota Tual</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="dark-mode-toggle" onclick="toggleDarkMode(this)" style="width:44px;height:24px" title="Toggle Dark/Light Mode"></button>
                    <a href="{{ route('home') }}" class="btn btn-sm" style="background:linear-gradient(135deg,#6366f1,#0ea5e9);color:#fff;border-radius:10px;border:none"><i class="bi bi-house"></i></a>
                </div>
            </div>

            @yield('content')
        </div>
    </div>
</div>
