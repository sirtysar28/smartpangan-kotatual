<div class="container-fluid">
    <div class="row">
        <div class="sidebar-admin p-0 d-none d-md-block" id="adminSidebar">
            <button class="sidebar-toggle-btn" id="sidebarCollapseBtn" title="Toggle sidebar">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="p-4 text-center border-bottom sidebar-brand" style="border-color:rgba(255,255,255,0.06)!important">
                <span class="d-inline-flex align-items-center gap-2">
                    <img src="{{ asset('logo.png') }}" alt="Pangan Cerdas (Panca) Maryadat Kota Tual" style="width:36px;height:36px;border-radius:8px">
                    <span class="sidebar-header-text">
                        <h6 class="fw-bold text-white mb-0" style="font-size:0.95rem">Pangan Cerdas (Panca) Maryadat <span style="background:linear-gradient(135deg,#10b981,#34d399);-webkit-background-clip:text;-webkit-text-fill-color:transparent">UMKM</span></h6>
                        <small style="color:rgba(255,255,255,0.5);font-size:0.7rem">UMKM Panel</small>
                    </span>
                </span>
            </div>
            <nav class="nav flex-column py-3 d-flex" style="min-height:100vh">
                <div>
                <a class="nav-link {{ request()->routeIs('umkm.dashboard')?'active':'' }}" href="{{ route('umkm.dashboard') }}"><i class="bi bi-grid-1x2"></i><span class="sidebar-text">Dashboard</span></a>
                <a class="nav-link {{ request()->routeIs('umkm.products.*')?'active':'' }}" href="{{ route('umkm.products.index') }}"><i class="bi bi-box-seam"></i><span class="sidebar-text">Produk</span></a>
                <a class="nav-link {{ request()->routeIs('umkm.orders.*')?'active':'' }}" href="{{ route('umkm.orders.index') }}"><i class="bi bi-bag-check"></i><span class="sidebar-text">Pesanan</span></a>
                <div style="padding:0.5rem 1.25rem;margin-top:0.5rem" class="sidebar-section-label">
                    <small style="color:rgba(255,255,255,0.2);font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;font-weight:700">Lainnya</small>
                </div>
                <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-globe2"></i><span class="sidebar-text">Marketplace</span></a>
                </div>

                <div class="mt-auto"></div>
                <div style="padding:0.75rem 20px;margin-top:auto" class="sidebar-section-label">
                    <div class="d-flex align-items-center justify-content-between">
                        <small style="color:rgba(255,255,255,0.6);font-size:0.75rem" class="sidebar-text"><i class="bi bi-moon-stars me-1"></i>Dark Mode</small>
                        <button class="dark-mode-toggle" onclick="toggleDarkMode(this)"></button>
                    </div>
                </div>
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit()" style="color:rgba(239,68,68,0.8)"><i class="bi bi-box-arrow-right"></i><span class="sidebar-text">Keluar</span></a>
                <form method="POST" action="{{ route('logout') }}" class="d-none">@csrf</form>
            </nav>
        </div>
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 d-md-none animate-fade-down">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm" id="sidebarToggle" style="background:var(--dark);color:#fff;border-radius:10px"><i class="bi bi-list"></i></button>
                    <span class="fw-bold" style="color:var(--text-main)"><img src="{{ asset('logo.png') }}" style="width:24px;height:24px;border-radius:6px"> Pangan Cerdas (Panca) Maryadat Kota Tual UMKM</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="dark-mode-toggle" onclick="toggleDarkMode(this)" style="width:36px;height:20px"></button>
                    <a href="{{ route('home') }}" class="btn btn-sm" style="background:var(--dark);color:#fff;border-radius:10px"><i class="bi bi-house"></i></a>
                </div>
            </div>
            @yield('content')
        </div>
    </div>
</div>
