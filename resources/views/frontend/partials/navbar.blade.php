<nav class="navbar-modern" id="navbarModern">
    <div class="nav-inner">
        <!-- BRAND (simple) -->
        <a class="nav-brand" href="{{ route('home') }}">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="nav-logo">
            <img src="{{ asset('Lambang_Kota_Tual.png') }}" alt="Lambang Kota Tual" class="nav-logo-city">
            <div class="nav-brand-text">
                <span class="nav-brand-name">Pangan Cerdas (Panca)</span>
            </div>
        </a>

        <!-- NAV LINKS (Desktop) -->
        <div class="nav-menu" id="navMenu">
            <a href="{{ route('home') }}" class="nav-link-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i>
                <span class="nav-link-text">Beranda</span>
            </a>

            <!-- Profil  -->
            <div class="nav-dd" id="ddProfil">
                <div class="nav-link-item-wrap {{ request()->is('halaman/profil-informasi-umum') ? 'active' : '' }}" onclick="toggleDD('ddProfil')">
                    <a href="{{ url('halaman/profil-informasi-umum') }}" class="nav-link-item" onclick="event.stopPropagation()">
                        <i class="bi bi-info-circle-fill"></i>
                        <span class="nav-link-text">Profil</span>
                    </a>
                    <button class="dd-toggle-arrow" onclick="event.stopPropagation();toggleDD('ddProfil')">
                        <i class="bi bi-chevron-down dd-arrow"></i>
                    </button>
                </div>
                <div class="dd-panel">
                    <a href="{{ route('home') }}#pimpinan-daerah" class="dd-item" onclick="closeDD()">
                        <i class="bi bi-people-fill"></i><span>Pimpinan Daerah</span>
                    </a>
                    <a href="{{ route('home') }}#profil" class="dd-item" onclick="closeDD()">
                        <i class="bi bi-building"></i><span>Informasi Dinas</span>
                    </a>
                </div>
            </div>

            <!-- Panel Harga Pangan (dropdown + clickable) -->
            <div class="nav-dd" id="ddHarga">
                <div class="nav-link-item-wrap {{ request()->routeIs('frontend.commodities') ? 'active' : '' }}" onclick="toggleDD('ddHarga')">
                    <a href="{{ route('frontend.commodities') }}" class="nav-link-item" onclick="event.stopPropagation()" {{ request()->routeIs('frontend.commodities') ? 'style="pointer-events:none"' : '' }}>
                        <i class="bi bi-graph-up-arrow"></i>
                        <span class="nav-link-text">Panel Harga Pangan</span>
                    </a>
                    <button class="dd-toggle-arrow" onclick="event.stopPropagation();toggleDD('ddHarga')">
                        <i class="bi bi-chevron-down dd-arrow"></i>
                    </button>
                </div>
                <div class="dd-panel">
                    <a href="{{ route('frontend.commodities') }}" class="dd-item" onclick="closeDD()">
                        <i class="bi bi-bar-chart-line"></i><span>Panel Harga</span>
                    </a>
                    <div class="dd-divider"></div>
                    <div class="dd-label">Kegiatan Pengendalian Inflasi</div>
                    <a href="{{ route('home') }}#pengendalian-inflasi" class="dd-item" onclick="closeDD()">
                        <i class="bi bi-cart4"></i><span>Gerakan Pangan Murah</span>
                    </a>
                    <a href="{{ route('home') }}#pengendalian-inflasi" class="dd-item" onclick="closeDD()">
                        <i class="bi bi-shop-window"></i><span>Kios Pangan</span>
                    </a>
                    <a href="{{ route('home') }}#pengendalian-inflasi" class="dd-item" onclick="closeDD()">
                        <i class="bi bi-eye-fill"></i><span>Sidak Operasi Pasar</span>
                    </a>
                    <a href="{{ route('home') }}#pengendalian-inflasi" class="dd-item" onclick="closeDD()">
                        <i class="bi bi-box-seam-fill"></i><span>Cadangan Pangan</span>
                    </a>
                    <a href="{{ route('home') }}#pengendalian-inflasi" class="dd-item" onclick="closeDD()">
                        <i class="bi bi-recycle"></i><span>Pemanfaatan Pangan Lestari</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('frontend.availability') }}" class="nav-link-item {{ request()->routeIs('frontend.availability') ? 'active' : '' }}">
                <i class="bi bi-boxes"></i>
                <span class="nav-link-text">Ketersediaan</span>
            </a>

            <a href="{{ route('frontend.food-vulnerability') }}" class="nav-link-item {{ request()->routeIs('frontend.food-vulnerability') ? 'active' : '' }}">
                <i class="bi bi-exclamation-diamond-fill"></i>
                <span class="nav-link-text">Kerawanan</span>
            </a>

            <a href="{{ route('frontend.food-safety') }}" class="nav-link-item {{ request()->routeIs('frontend.food-safety') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i>
                <span class="nav-link-text">Keamanan</span>
            </a>

            <a href="{{ route('frontend.permits') }}" class="nav-link-item {{ request()->routeIs('frontend.permits') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i>
                <span class="nav-link-text">Perizinan</span>
            </a>
        </div>

        <!-- RIGHT ACTIONS -->
        <div class="nav-actions">
            <button class="nav-icon-btn" onclick="toggleDarkMode(this)" title="Dark Mode" id="navDarkBtn">
                <i class="bi bi-moon-fill nav-icon-dark"></i>
                <i class="bi bi-sun-fill nav-icon-light"></i>
            </button>

            {{-- Marketplace dinonaktifkan (revisi 7 Juli 2026): tombol search & keranjang disembunyikan --}}
            {{--
            <button class="nav-icon-btn" onclick="toggleNavSearch()" title="Cari" id="navSearchToggle">
                <i class="bi bi-search"></i>
            </button>

            <a class="nav-icon-btn" href="{{ route('cart.index') }}" title="Keranjang" style="position:relative">
                <i class="bi bi-cart3"></i>
                @php $cartCount = count(session('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="nav-cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
            --}}
            @php $cartCount = count(session('cart', [])); @endphp

            @guest
                <a class="nav-btn-login" href="{{ route('login') }}" title="Masuk">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="nav-btn-text">Masuk</span>
                </a>
                <a class="nav-btn-register" href="{{ route('register') }}" title="Daftar">
                    <i class="bi bi-person-plus"></i>
                    <span class="nav-btn-text">Daftar</span>
                </a>
            @endguest
            @auth
                <div class="nav-profile-dd">
                    <button class="nav-profile-trigger" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="nav-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>
                        <span class="nav-profile-label">{{ auth()->user()->name }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="nav-profile-panel">
                        <div class="nav-profile-info">
                            <span class="nav-profile-name">{{ auth()->user()->name }}</span>
                            <span class="nav-profile-role">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                        <div class="dd-divider"></div>
                        <a href="{{ route('profile.edit') }}" class="dd-item">
                            <i class="bi bi-person-gear"></i><span>Profil Saya</span>
                        </a>
                        {{-- Marketplace dinonaktifkan: menu Pesanan Saya disembunyikan
                        <a href="{{ route('orders.index') }}" class="dd-item">
                            <i class="bi bi-bag-check"></i><span>Pesanan Saya</span>
                        </a>
                        --}}
                        @if(auth()->user()->isAdminLevel())
                            <a href="{{ route('admin.dashboard') }}" target="_blank" class="dd-item">
                                <i class="bi bi-shield-lock"></i><span>Admin Panel</span>
                            </a>
                        @endif
                        @if(auth()->user()->isUmkm())
                            <a href="{{ route('umkm.dashboard') }}" target="_blank" class="dd-item">
                                <i class="bi bi-shop"></i><span>UMKM Panel</span>
                            </a>
                        @endif
                        <div class="dd-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="dd-item dd-item-danger">
                                <i class="bi bi-box-arrow-right"></i><span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <!-- Mobile Hamburger -->
            <button class="nav-hamburger" onclick="toggleMobileMenu()" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="nav-search-bar" id="navSearchBar">
        <div class="nav-search-inner">
            <i class="bi bi-search"></i>
            <form action="{{ route('products.index') }}" method="GET" class="nav-search-form">
                <input type="text" name="search" placeholder="Cari produk UMKM..." value="{{ request('search') }}" id="navSearchInput">
            </form>
            <button onclick="toggleNavSearch()" class="nav-search-close"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
</nav>

<!-- MOBILE MENU OVERLAY -->
<div class="mob-overlay" id="mobOverlay" onclick="toggleMobileMenu()"></div>
<div class="mob-menu" id="mobMenu">
    <div class="mob-header">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="width:28px;height:28px;border-radius:8px">
            <img src="{{ asset('Lambang_Kota_Tual.png') }}" alt="Lambang Kota Tual" style="width:28px;height:28px;border-radius:8px">
            <span class="fw-bold" style="font-size:0.85rem;color:#fff">Pangan Cerdas <span style="color:#f59e0b">(Panca)</span></span>
        </div>
        <button onclick="toggleMobileMenu()" style="background:none;border:none;color:#fff;font-size:1.3rem;cursor:pointer"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="mob-body">
        <a href="{{ route('home') }}" class="mob-link {{ request()->routeIs('home') ? 'active' : '' }}" onclick="toggleMobileMenu()">
            <i class="bi bi-house-door-fill"></i><span>Beranda</span><i class="bi bi-chevron-right"></i>
        </a>

        <!-- Profil (collapsible) -->
        <div class="mob-collapse" id="mobProfil">
            <button class="mob-link" onclick="toggleMobCollapse('mobProfil')">
                <i class="bi bi-info-circle-fill"></i><span>Profil</span>
                <i class="bi bi-chevron-down mob-collapse-arrow"></i>
            </button>
            <div class="mob-collapse-body">
                <a href="{{ route('home') }}#pimpinan-daerah" class="mob-sublink" onclick="toggleMobileMenu()">
                    <i class="bi bi-people-fill"></i><span>Pimpinan Daerah</span>
                </a>
                @forelse($navPages ?? \App\Models\SitePage::active()->ordered()->take(10)->get() as $mp)
                <a href="{{ route('site.page', $mp->slug) }}" class="mob-sublink" onclick="toggleMobileMenu()">
                    <i class="bi bi-{{ $mp->icon ?? 'file-earmark-text' }}"></i><span>{{ $mp->title }}</span>
                </a>
                @empty
                <a href="{{ route('home') }}#profil" class="mob-sublink" onclick="toggleMobileMenu()">
                    <i class="bi bi-building"></i><span>Informasi Umum Kota Tual</span>
                </a>
                <a href="{{ route('home') }}#profil" class="mob-sublink" onclick="toggleMobileMenu()">
                    <i class="bi bi-people-fill"></i><span>Dinas Ketahanan Pangan</span>
                </a>
                @endforelse
            </div>
        </div>

        <!-- Panel Harga Pangan (collapsible) -->
        <div class="mob-collapse" id="mobHarga">
            <a href="{{ route('frontend.commodities') }}" class="mob-link {{ request()->routeIs('frontend.commodities') ? 'active' : '' }}" onclick="toggleMobileMenu()">
                <i class="bi bi-graph-up-arrow"></i><span>Panel Harga Pangan</span><i class="bi bi-chevron-right"></i>
            </a>
            <button class="mob-link mob-sub-toggle" onclick="toggleMobCollapse('mobHarga')">
                <i class="bi bi-speedometer2"></i><span>Pengendalian Inflasi</span>
                <i class="bi bi-chevron-down mob-collapse-arrow"></i>
            </button>
            <div class="mob-collapse-body">
                <a href="{{ route('home') }}#pengendalian-inflasi" class="mob-sublink" onclick="toggleMobileMenu()">
                    <i class="bi bi-cart4"></i><span>Gerakan Pangan Murah</span>
                </a>
                <a href="{{ route('home') }}#pengendalian-inflasi" class="mob-sublink" onclick="toggleMobileMenu()">
                    <i class="bi bi-shop-window"></i><span>Kios Pangan</span>
                </a>
                <a href="{{ route('home') }}#pengendalian-inflasi" class="mob-sublink" onclick="toggleMobileMenu()">
                    <i class="bi bi-eye-fill"></i><span>Sidak Operasi Pasar</span>
                </a>
                <a href="{{ route('home') }}#pengendalian-inflasi" class="mob-sublink" onclick="toggleMobileMenu()">
                    <i class="bi bi-box-seam-fill"></i><span>Cadangan Pangan</span>
                </a>
                <a href="{{ route('home') }}#pengendalian-inflasi" class="mob-sublink" onclick="toggleMobileMenu()">
                    <i class="bi bi-recycle"></i><span>Pemanfaatan Pangan Lestari</span>
                </a>
            </div>
        </div>

        <a href="{{ route('frontend.availability') }}" class="mob-link {{ request()->routeIs('frontend.availability') ? 'active' : '' }}" onclick="toggleMobileMenu()">
            <i class="bi bi-boxes"></i><span>Ketersediaan Pangan</span><i class="bi bi-chevron-right"></i>
        </a>
        <a href="{{ route('frontend.food-vulnerability') }}" class="mob-link {{ request()->routeIs('frontend.food-vulnerability') ? 'active' : '' }}" onclick="toggleMobileMenu()">
            <i class="bi bi-exclamation-diamond-fill"></i><span>Kerawanan Pangan</span><i class="bi bi-chevron-right"></i>
        </a>
        <a href="{{ route('frontend.food-safety') }}" class="mob-link {{ request()->routeIs('frontend.food-safety') ? 'active' : '' }}" onclick="toggleMobileMenu()">
            <i class="bi bi-shield-check"></i><span>Keamanan Pangan</span><i class="bi bi-chevron-right"></i>
        </a>
        <a href="{{ route('frontend.permits') }}" class="mob-link {{ request()->routeIs('frontend.permits') ? 'active' : '' }}" onclick="toggleMobileMenu()">
            <i class="bi bi-file-earmark-text"></i><span>Perizinan</span><i class="bi bi-chevron-right"></i>
        </a>

        {{-- Marketplace dinonaktifkan: menu Keranjang & Pesanan disembunyikan
        <div class="mob-divider"></div>
        <a href="{{ route('cart.index') }}" class="mob-link" onclick="toggleMobileMenu()">
            <i class="bi bi-cart3"></i><span>Keranjang</span>
            @if($cartCount > 0)<span class="nav-cart-badge" style="font-size:0.6rem;padding:2px 5px;position:static;margin-left:auto">{{ $cartCount }}</span>@endif
            <i class="bi bi-chevron-right"></i>
        </a>
        --}}

        @guest
            <div class="mob-divider"></div>
            <div class="d-flex gap-2 px-3 pt-1 pb-2">
                <a href="{{ route('login') }}" class="nav-btn-login flex-fill text-center d-flex align-items-center justify-content-center gap-1" style="text-decoration:none;padding:0.55rem" onclick="toggleMobileMenu()">
                    <i class="bi bi-box-arrow-in-right"></i><span>Masuk</span>
                </a>
                <a href="{{ route('register') }}" class="nav-btn-register flex-fill text-center d-flex align-items-center justify-content-center gap-1" style="text-decoration:none;padding:0.55rem" onclick="toggleMobileMenu()">
                    <i class="bi bi-person-plus"></i><span>Daftar</span>
                </a>
            </div>
        @endguest
        @auth
            <div class="mob-divider"></div>
            <div class="px-3 pt-1">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="nav-avatar" style="width:34px;height:34px;font-size:0.8rem">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>
                    <div>
                        <div class="fw-bold" style="font-size:0.82rem;color:#fff">{{ auth()->user()->name }}</div>
                        <small style="color:rgba(255,255,255,0.45)">{{ ucfirst(auth()->user()->role) }}</small>
                    </div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="mob-link" onclick="toggleMobileMenu()">
                <i class="bi bi-person-gear"></i><span>Profil Saya</span><i class="bi bi-chevron-right"></i>
            </a>
            {{-- Marketplace dinonaktifkan: menu Pesanan Saya disembunyikan
            <a href="{{ route('orders.index') }}" class="mob-link" onclick="toggleMobileMenu()">
                <i class="bi bi-bag-check"></i><span>Pesanan Saya</span><i class="bi bi-chevron-right"></i>
            </a>
            --}}
            @if(auth()->user()->isAdminLevel())
                <a href="{{ route('admin.dashboard') }}" target="_blank" class="mob-link" onclick="toggleMobileMenu()">
                    <i class="bi bi-shield-lock"></i><span>Admin Panel</span><i class="bi bi-chevron-right"></i>
                </a>
            @endif
            @if(auth()->user()->isUmkm())
                <a href="{{ route('umkm.dashboard') }}" target="_blank" class="mob-link" onclick="toggleMobileMenu()">
                    <i class="bi bi-shop"></i><span>UMKM Panel</span><i class="bi bi-chevron-right"></i>
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="px-3 pt-1">@csrf
                <button type="submit" class="mob-link w-100 d-flex align-items-center gap-3" style="color:#ef4444;background:none;border:none;width:100%;text-align:left;padding:0.65rem 0.85rem;cursor:pointer;border-radius:12px">
                    <i class="bi bi-box-arrow-right"></i><span>Keluar</span>
                </button>
            </form>
        @endauth
    </div>
</div>

<style>
/* ================================================
   NAVBAR — Pangan Cerdas (Panca) Maryadat Kota Tual
   ================================================ */
.navbar-modern {
    position: fixed; top: 0; left: 0; right: 0; z-index: 9999;
    background: rgba(15,23,42,0.92);
    backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
    border-bottom: 1px solid rgba(255,255,255,0.06);
    transition: background 0.3s, box-shadow 0.3s;
}
.navbar-modern.scrolled {
    background: rgba(15,23,42,0.98);
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.nav-inner {
    display: flex; align-items: center; gap: 0;
    max-width: 1360px; margin: 0 auto;
    padding: 0 1rem; height: 60px;
}

/* ---- BRAND (simple) ---- */
.nav-brand {
    display: flex; align-items: center; gap: 0.5rem;
    text-decoration: none; flex-shrink: 0; margin-right: 0.75rem;
}
.nav-brand:hover { opacity: 0.85; }
.nav-logo { width: 32px; height: 32px; border-radius: 8px; object-fit: cover; }
.nav-logo-city { width: 32px; height: 32px; border-radius: 8px; object-fit: cover; opacity: 0.9; transition: opacity 0.2s; }
.nav-brand:hover .nav-logo-city { opacity: 1; }
.nav-brand-name {
    color: #fff; font-weight: 700; font-size: 0.88rem;
    white-space: nowrap; letter-spacing: -0.3px;
}

/* ---- MENU ---- */
.nav-menu {
    display: flex; align-items: center; gap: 2px; flex-shrink: 1;
    overflow: hidden;
}

/* ---- LINK ITEM ---- */
.nav-link-item {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.4rem 0.65rem; border-radius: 8px;
    color: rgba(255,255,255,0.6); background: none; border: none;
    text-decoration: none; font-weight: 500; font-size: 0.78rem;
    white-space: nowrap; cursor: pointer; position: relative;
    transition: all 0.2s;
    max-width: 160px;
    overflow: hidden;
}
.nav-link-item:hover { color: #fff; background: rgba(255,255,255,0.08); }
.nav-link-item.active {
    color: #fff; font-weight: 600;
    background: linear-gradient(135deg, rgba(99,102,241,0.18), rgba(14,165,233,0.12));
}
.nav-link-item i:first-child { font-size: 0.82rem; flex-shrink: 0; }
.nav-link-text {
    overflow: hidden; text-overflow: ellipsis; display: -webkit-box;
    -webkit-line-clamp: 1; -webkit-box-orient: vertical;
}
.dd-arrow {
    font-size: 0.55rem !important; opacity: 0.4; margin-left: 1px;
    transition: transform 0.25s; flex-shrink: 0;
}
.nav-dd.open .dd-arrow { transform: rotate(180deg); }

/* ---- DROPDOWN (desktop) ---- */
.nav-dd { position: relative; }
/* Split button: text is link, arrow opens dropdown */
.nav-link-item-wrap {
    display: inline-flex; align-items: center; gap: 0;
    border-radius: 8px; cursor: pointer; transition: all 0.2s;
}
.nav-link-item-wrap:hover { background: rgba(255,255,255,0.08); }
.nav-link-item-wrap.active { background: linear-gradient(135deg, rgba(99,102,241,0.18), rgba(14,165,233,0.12)); }
.nav-link-item-wrap .nav-link-item { border-radius: 8px 0 0 8px; padding-right: 0.3rem; }
.dd-toggle-arrow {
    display: flex; align-items: center; justify-content: center;
    width: 22px; height: 28px; border: none; background: none;
    color: rgba(255,255,255,0.4); cursor: pointer; transition: all 0.15s;
    border-radius: 0 8px 8px 0; font-size: 0.55rem;
}
.dd-toggle-arrow:hover { color: #fff; background: rgba(255,255,255,0.08); }
.dd-panel {
    position: absolute; top: calc(100% + 6px); left: 0;
    min-width: 230px;
    background: rgba(15,23,42,0.98); backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.08); border-radius: 12px;
    padding: 0.4rem 0; box-shadow: 0 12px 40px rgba(0,0,0,0.35);
    opacity: 0; visibility: hidden;
    transform: translateY(-6px); transition: all 0.2s ease;
    z-index: 10001;
}
.nav-dd.open .dd-panel { opacity: 1; visibility: visible; transform: translateY(0); }
.dd-item {
    display: flex; align-items: center; gap: 0.55rem;
    padding: 0.5rem 0.85rem; color: rgba(255,255,255,0.65);
    text-decoration: none; font-size: 0.8rem; font-weight: 500;
    transition: all 0.15s; white-space: nowrap;
}
.dd-item:hover { color: #fff; background: rgba(99,102,241,0.12); }
.dd-item i { font-size: 0.82rem; width: 16px; text-align: center; color: #818cf8; flex-shrink: 0; }
.dd-item span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.dd-divider { height: 1px; background: rgba(255,255,255,0.06); margin: 0.3rem 0; }
.dd-label {
    padding: 0.35rem 0.85rem 0.15rem; font-size: 0.65rem; font-weight: 700;
    color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.7px;
}
.dd-item-danger { color: #f87171; }
.dd-item-danger:hover { background: rgba(239,68,68,0.1); }
.dd-item-danger i { color: #ef4444; }

/* ---- ACTIONS ---- */
.nav-actions {
    display: flex; align-items: center; gap: 0.3rem;
    margin-left: auto; flex-shrink: 0;
}
.nav-icon-btn {
    display: flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: 8px; border: none;
    background: transparent; color: rgba(255,255,255,0.55);
    cursor: pointer; transition: all 0.2s; text-decoration: none;
    font-size: 1rem; flex-shrink: 0;
}
.nav-icon-btn:hover { color: #fff; background: rgba(255,255,255,0.1); }

.nav-icon-light { display: none; }
html.dark-mode .nav-icon-dark { display: none; }
html.dark-mode .nav-icon-light { display: inline; }

/* Cart badge */
.nav-cart-badge {
    position: absolute; top: 1px; right: 1px;
    min-width: 14px; height: 14px; border-radius: 7px;
    background: linear-gradient(135deg,#ef4444,#f97316);
    color: #fff; font-size: 0.55rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    padding: 0 3px;
}

/* ---- AUTH BUTTONS ---- */
.nav-btn-login, .nav-btn-register {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.3rem;
    padding: 0.35rem 0.75rem; border-radius: 8px; font-weight: 600;
    font-size: 0.78rem; text-decoration: none; white-space: nowrap;
    transition: all 0.2s; cursor: pointer; border: none; flex-shrink: 0;
}
.nav-btn-login {
    color: rgba(255,255,255,0.75); background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.1);
}
.nav-btn-login:hover { color: #fff; background: rgba(255,255,255,0.13); }
.nav-btn-register {
    color: #fff; background: linear-gradient(135deg,#6366f1,#0ea5e9);
    box-shadow: 0 2px 8px rgba(99,102,241,0.25);
}
.nav-btn-register:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.4); color: #fff; }

/* ---- PROFILE DROPDOWN ---- */
.nav-profile-dd { position: relative; flex-shrink: 0; }
.nav-profile-trigger {
    display: flex; align-items: center; gap: 0.4rem;
    padding: 0.3rem 0.55rem 0.3rem 0.3rem; border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.07); background: rgba(255,255,255,0.04);
    color: #fff; cursor: pointer; transition: all 0.2s;
}
.nav-profile-trigger:hover { background: rgba(255,255,255,0.08); }
.nav-avatar {
    display: flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 8px;
    background: linear-gradient(135deg,#6366f1,#0ea5e9);
    color: #fff; font-weight: 700; font-size: 0.7rem; flex-shrink: 0;
}
.nav-profile-label {
    font-size: 0.78rem; font-weight: 600; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis; max-width: 100px;
}
.nav-profile-trigger .bi-chevron-down { font-size: 0.6rem; opacity: 0.5; transition: transform 0.25s; }
.nav-profile-trigger[aria-expanded="true"] .bi-chevron-down { transform: rotate(180deg); }

.nav-profile-panel {
    position: absolute; top: calc(100% + 8px); right: 0;
    min-width: 220px;
    background: rgba(15,23,42,0.98); backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.08); border-radius: 12px;
    padding: 0.4rem 0; box-shadow: 0 12px 40px rgba(0,0,0,0.35);
    opacity: 0; visibility: hidden; transform: translateY(-6px);
    transition: all 0.2s ease; z-index: 10001;
}
.nav-profile-trigger[aria-expanded="true"] ~ .nav-profile-panel,
.nav-profile-panel.show { opacity: 1; visibility: visible; transform: translateY(0); }
.nav-profile-info {
    padding: 0.5rem 0.85rem; display: flex; flex-direction: column; gap: 0.05rem;
}
.nav-profile-name { font-weight: 700; font-size: 0.82rem; color: #fff; }
.nav-profile-role { font-size: 0.68rem; color: rgba(255,255,255,0.4); text-transform: capitalize; }

/* ---- SEARCH ---- */
.nav-search-bar {
    max-height: 0; overflow: hidden;
    transition: max-height 0.3s ease; border-top: 1px solid transparent;
    background: rgba(15,23,42,0.98);
}
.nav-search-bar.open { max-height: 60px; border-top-color: rgba(255,255,255,0.06); }
.nav-search-inner {
    display: flex; align-items: center; gap: 0.6rem;
    max-width: 560px; margin: 0 auto; padding: 0.55rem 1rem;
}
.nav-search-inner > i { color: rgba(255,255,255,0.35); font-size: 0.9rem; flex-shrink: 0; }
.nav-search-form { flex: 1; }
.nav-search-form input {
    width: 100%; background: none; border: none; outline: none;
    color: #fff; font-size: 0.85rem; font-family: 'Plus Jakarta Sans', sans-serif;
}
.nav-search-form input::placeholder { color: rgba(255,255,255,0.3); }
.nav-search-close {
    background: none; border: none; color: rgba(255,255,255,0.45);
    cursor: pointer; font-size: 0.8rem; padding: 2px;
}
.nav-search-close:hover { color: #fff; }

/* ---- HAMBURGER ---- */
.nav-hamburger {
    display: none; flex-direction: column; justify-content: center; align-items: center;
    gap: 4px; width: 36px; height: 36px; border: none;
    background: rgba(255,255,255,0.06); border-radius: 8px;
    cursor: pointer; padding: 0; flex-shrink: 0;
}
.nav-hamburger span {
    display: block; width: 18px; height: 2px; border-radius: 2px;
    background: #fff; transition: all 0.3s;
}
.nav-hamburger.open span:nth-child(1) { transform: translateY(6px) rotate(45deg); }
.nav-hamburger.open span:nth-child(2) { opacity: 0; }
.nav-hamburger.open span:nth-child(3) { transform: translateY(-6px) rotate(-45deg); }

/* ================================================
   MOBILE MENU
   ================================================ */
.mob-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,0.5);
    backdrop-filter: blur(3px); z-index: 9998;
    opacity: 0; visibility: hidden; transition: all 0.3s;
}
.mob-overlay.open { opacity: 1; visibility: visible; }

.mob-menu {
    position: fixed; top: 0; right: 0; bottom: 0;
    width: 300px; max-width: 85vw;
    background: linear-gradient(180deg,#0f172a,#1e293b);
    z-index: 9999; transform: translateX(100%);
    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
    display: flex; flex-direction: column;
    box-shadow: -6px 0 24px rgba(0,0,0,0.35); overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}
.mob-menu.open { transform: translateX(0); }

.mob-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.85rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
}
.mob-body {
    padding: 0.5rem 0.65rem; flex: 1;
    display: flex; flex-direction: column; gap: 0.15rem;
    overflow-y: auto; -webkit-overflow-scrolling: touch;
}
.mob-link {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.6rem 0.75rem; border-radius: 10px;
    color: rgba(255,255,255,0.65); text-decoration: none;
    font-weight: 500; font-size: 0.82rem; transition: all 0.15s;
    background: none; border: none; width: 100%; text-align: left;
    cursor: pointer;
}
.mob-link:hover, .mob-link.active { color: #fff; background: rgba(99,102,241,0.15); }
.mob-link > i:first-child { width: 18px; text-align: center; font-size: 0.95rem; flex-shrink: 0; }
.mob-link > i:last-child { margin-left: auto; opacity: 0.25; font-size: 0.7rem; flex-shrink: 0; }
.mob-link span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.mob-divider { height: 1px; background: rgba(255,255,255,0.06); margin: 0.35rem 0; }
.mob-sub-toggle { padding-left: 1.35rem; }

/* Mobile collapsible */
.mob-collapse-body {
    max-height: 0; overflow: hidden; transition: max-height 0.3s ease;
    padding-left: 1.35rem;
}
.mob-collapse.open .mob-collapse-body { max-height: 300px; }
.mob-collapse.open .mob-collapse-arrow { transform: rotate(180deg); }
.mob-collapse-arrow {
    font-size: 0.6rem !important; opacity: 0.4;
    transition: transform 0.25s; margin-left: auto !important;
}
.mob-sublink {
    display: flex; align-items: center; gap: 0.5rem;
    padding: 0.4rem 0.6rem; border-radius: 8px;
    color: rgba(255,255,255,0.5); text-decoration: none;
    font-size: 0.78rem; transition: all 0.15s;
    margin-bottom: 2px;
}
.mob-sublink:hover { color: #fff; background: rgba(99,102,241,0.1); }
.mob-sublink i { font-size: 0.78rem; width: 16px; text-align: center; flex-shrink: 0; }
.mob-sublink span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ================================================
   RESPONSIVE
   ================================================ */

/* Large desktop: full menu */
@media (min-width: 1200px) {
    .nav-menu { gap: 2px; }
}

/* Medium: compact text, ellipsis */
@media (max-width: 1199.98px) and (min-width: 992px) {
    .nav-link-item { padding: 0.35rem 0.5rem; font-size: 0.75rem; max-width: 130px; }
    .nav-brand-name { font-size: 0.82rem; }
    .nav-btn-text { display: none; }
    .nav-btn-login, .nav-btn-register { padding: 0.35rem 0.5rem; width: 36px; height: 36px; }
    .nav-profile-label { display: none; }
}

/* Tablet: hide menu, show hamburger + icon buttons */
@media (max-width: 991.98px) {
    .nav-menu { display: none; }
    .nav-hamburger { display: flex; }
    .nav-profile-label { display: none; }
    .nav-profile-trigger .bi-chevron-down { display: none; }
    .nav-btn-text { display: none; }
    .nav-btn-login, .nav-btn-register { width: 36px; height: 36px; padding: 0; border-radius: 8px; }
    .nav-btn-login i, .nav-btn-register i { margin: 0; }
    .nav-profile-panel { right: -40px; }
}

/* Small */
@media (max-width: 575.98px) {
    .nav-inner { padding: 0 0.6rem; height: 54px; }
    .nav-brand { margin-right: 0.4rem; gap: 0.35rem; }
    .nav-logo { width: 28px; height: 28px; border-radius: 7px; }
    .nav-brand-name { font-size: 0.75rem; }
    .nav-actions { gap: 0.2rem; }
    .nav-icon-btn { width: 32px; height: 32px; font-size: 0.9rem; }
    .nav-btn-login, .nav-btn-register { width: 32px; height: 32px; font-size: 0.85rem; }
    .nav-avatar { width: 28px; height: 28px; font-size: 0.65rem; }
    .nav-hamburger { width: 32px; height: 32px; }
    .nav-hamburger span { width: 16px; }
    .nav-profile-panel { right: -50px; min-width: 200px; }
    .mob-menu { max-width: 88vw; }
}

body { padding-top: 60px !important; }
@media (max-width: 575.98px) { body { padding-top: 54px !important; } }

@keyframes navSlideIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
.navbar-modern { animation: navSlideIn 0.3s ease-out; }
</style>

<script>
// Scroll
(function() {
    const nav = document.getElementById('navbarModern');
    if (nav) window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY > 15), { passive: true });
})();

// Desktop dropdown
function toggleDD(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const wasOpen = el.classList.contains('open');
    closeDD();
    if (!wasOpen) el.classList.add('open');
}
function closeDD() {
    document.querySelectorAll('.nav-dd.open').forEach(d => d.classList.remove('open'));
}
document.addEventListener('click', e => { if (!e.target.closest('.nav-dd')) closeDD(); });

// Mobile menu
function toggleMobileMenu() {
    const m = document.getElementById('mobMenu');
    const o = document.getElementById('mobOverlay');
    const h = document.querySelector('.nav-hamburger');
    const open = m.classList.toggle('open');
    o.classList.toggle('open', open);
    if (h) h.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
}

// Mobile collapsible
function toggleMobCollapse(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle('open');
}

// Search
function toggleNavSearch() {
    const bar = document.getElementById('navSearchBar');
    const input = document.getElementById('navSearchInput');
    const btn = document.getElementById('navSearchToggle');
    bar.classList.toggle('open');
    if (bar.classList.contains('open')) {
        setTimeout(() => input && input.focus(), 300);
        btn.style.color = '#fff'; btn.style.background = 'rgba(99,102,241,0.2)';
    } else {
        btn.style.color = ''; btn.style.background = '';
    }
}

// Profile dropdown
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.nav-profile-trigger[data-bs-toggle="dropdown"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const panel = this.nextElementSibling;
            const isOpen = panel.classList.contains('show');
            document.querySelectorAll('.nav-profile-panel.show').forEach(p => p.classList.remove('show'));
            document.querySelectorAll('.nav-profile-trigger[aria-expanded="true"]').forEach(b => b.setAttribute('aria-expanded','false'));
            if (!isOpen) { panel.classList.add('show'); this.setAttribute('aria-expanded','true'); }
        });
    });
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-profile-dd')) {
            document.querySelectorAll('.nav-profile-panel.show').forEach(p => p.classList.remove('show'));
            document.querySelectorAll('.nav-profile-trigger[aria-expanded="true"]').forEach(b => b.setAttribute('aria-expanded','false'));
        }
    });
});

// Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        const bar = document.getElementById('navSearchBar');
        if (bar && bar.classList.contains('open')) toggleNavSearch();
        closeDD();
    }
});
</script>
