{{-- ===== BOTTOM NAV 5 ICON (KHUSUS MOBILE) — ala aplikasi native ===== --}}
@php
    $navAdmin = $admin ?? false;
    $navItems = $navAdmin ? [
        ['url' => route('admin.dashboard'),        'match' => 'admin.dashboard',        'icon' => 'bi-grid-1x2',              'label' => 'Dashboard'],
        ['url' => route('admin.commodities.index'),'match' => 'admin.commodities.*',    'icon' => 'bi-tag',                   'label' => 'Harga'],
        ['url' => route('admin.availability.index'),'match' => 'admin.availability.*',  'icon' => 'bi-box-seam',              'label' => 'Pasokan'],
        ['url' => route('admin.food-safety.index'), 'match' => 'admin.food-safety.*',   'icon' => 'bi-shield-check',          'label' => 'Keamanan'],
        ['url' => route('admin.reports.index'),     'match' => 'admin.reports.*',       'icon' => 'bi-file-earmark-bar-graph','label' => 'Laporan'],
    ] : [
        ['url' => route('home'),                     'match' => 'home',                   'icon' => 'bi-house-door',      'label' => 'Beranda'],
        ['url' => route('frontend.commodities'),     'match' => 'frontend.commodities',   'icon' => 'bi-tag',             'label' => 'Harga'],
        ['url' => route('frontend.availability'),    'match' => 'frontend.availability',  'icon' => 'bi-box-seam',        'label' => 'Pasokan'],
        ['url' => route('frontend.food-safety'),     'match' => 'frontend.food-safety',   'icon' => 'bi-shield-check',    'label' => 'Keamanan'],
        ['url' => route('frontend.permits'),         'match' => 'frontend.permits',       'icon' => 'bi-file-earmark-text','label' => 'Laporan Perizinan'],
    ];
@endphp
<nav class="mobile-bottom-nav d-md-none" aria-label="Navigasi mobile">
    @foreach($navItems as $item)
    <a href="{{ $item['url'] }}" class="mobile-nav-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
        <i class="bi {{ $item['icon'] }}"></i>
        <span>{{ $item['label'] }}</span>
    </a>
    @endforeach
</nav>

<style>
    .mobile-bottom-nav {
        position: fixed; bottom: 0; left: 0; right: 0; z-index: 1045;
        display: flex; justify-content: space-around; align-items: stretch;
        background: rgba(15,23,42,0.97); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
        border-top: 1px solid rgba(255,255,255,0.08);
        padding: 6px 4px calc(6px + env(safe-area-inset-bottom, 0px));
        box-shadow: 0 -8px 30px rgba(0,0,0,0.25);
    }
    .mobile-bottom-nav .mobile-nav-item {
        flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px;
        color: rgba(255,255,255,0.55); text-decoration: none; font-size: 0.62rem; font-weight: 600;
        padding: 6px 2px; border-radius: 12px; transition: color .25s ease, background .25s ease, transform .25s ease;
        -webkit-tap-highlight-color: transparent;
    }
    .mobile-bottom-nav .mobile-nav-item i { font-size: 1.25rem; line-height: 1; transition: transform .25s ease; }
    .mobile-bottom-nav .mobile-nav-item.active { color: #fff; background: rgba(99,102,241,0.18); }
    .mobile-bottom-nav .mobile-nav-item.active i { transform: translateY(-2px) scale(1.1); }
    .mobile-bottom-nav .mobile-nav-item:active i { transform: scale(0.9); }
    @media (max-width: 767.98px) {
        body { padding-bottom: 76px; }
    }
</style>
