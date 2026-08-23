@extends('layouts.app')
@section('title', 'Pangan Cerdas (Panca) Maryadat Kota Tual - Monitoring Harga & Ketahanan Pangan Kota Tual')

@section('styles')
<style>
    /* ===== KEPALA DINAS PROFILE CARD ===== */
    .kadis-profile-card {
        position: relative;
        background: var(--bg-card);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        border: 1px solid rgba(245,158,11,0.12);
        transition: transform 0.4s cubic-bezier(0.16,1,0.3,1), box-shadow 0.4s ease;
        height: 100%;
    }
    .kadis-profile-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 50px rgba(15,23,42,0.18);
    }
    .kadis-profile-photo-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
        cursor: pointer;
    }
    .kadis-profile-photo-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        transition: transform 0.6s cubic-bezier(0.16,1,0.3,1);
    }
    .kadis-profile-card:hover .kadis-profile-photo-wrap img { transform: scale(1.05); }
    .kadis-profile-photo-gradient {
        position: absolute; bottom: 0; left: 0; right: 0; height: 60%;
        background: linear-gradient(transparent, rgba(15,23,42,0.92));
    }
    .kadis-profile-role-badge {
        position: absolute; top: 14px; left: 14px;
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,0.92); backdrop-filter: blur(10px);
        border-radius: 30px; padding: 0.35rem 0.8rem;
        font-size: 0.68rem; font-weight: 700;
        color: #f59e0b;
        text-transform: uppercase; letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .kadis-profile-info {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 1.1rem 1.25rem; color: #fff;
    }
    .kadis-profile-info h5 {
        font-weight: 700; margin-bottom: 0.2rem;
        font-size: 1.02rem; line-height: 1.3;
        text-shadow: 0 2px 8px rgba(0,0,0,0.4);
    }
    .kadis-profile-info .kadis-instansi {
        font-size: 0.78rem; opacity: 0.85;
        display: flex; align-items: center; gap: 5px;
    }
    .kadis-profile-detail-btn {
        display: inline-flex; align-items: center; gap: 6px;
        margin-top: 0.6rem;
        font-size: 0.72rem; font-weight: 600;
        color: #fbbf24;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 20px; padding: 0.3rem 0.75rem;
        cursor: pointer; transition: all 0.25s ease;
        backdrop-filter: blur(8px);
    }
    .kadis-profile-detail-btn:hover {
        background: rgba(251,191,36,0.2);
        border-color: rgba(251,191,36,0.4);
        transform: translateX(3px);
    }
    html.dark-mode .kadis-profile-card { border-color: rgba(255,255,255,0.06); }
    @keyframes kadisPopupFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes kadisPopupSlideUp { from { opacity: 0; transform: translateY(40px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .kadis-popup-overlay {
        position: fixed; inset: 0; z-index: 100000;
        background: rgba(0,0,0,0.65); backdrop-filter: blur(10px);
        display: none; align-items: center; justify-content: center;
        padding: 1rem;
        animation: kadisPopupFadeIn 0.25s ease;
    }
    .kadis-popup-overlay.active { display: flex; }
    .kadis-popup-modal {
        background: var(--bg-card); border-radius: 24px;
        width: 100%; max-width: 440px;
        box-shadow: 0 25px 80px rgba(0,0,0,0.3);
        animation: kadisPopupSlideUp 0.4s cubic-bezier(0.16,1,0.3,1);
        position: relative; overflow: hidden;
    }
    .kadis-popup-close {
        position: absolute; top: 14px; right: 14px; z-index: 10;
        width: 36px; height: 36px; border-radius: 50%;
        background: rgba(0,0,0,0.45); backdrop-filter: blur(8px);
        border: none; color: #fff; font-size: 0.85rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    .kadis-popup-close:hover { background: rgba(239,68,68,0.8); transform: scale(1.1); }
    .kadis-popup-img-wrap {
        width: 100%; height: 340px; position: relative;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        overflow: hidden;
    }
    .kadis-popup-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .kadis-popup-img-gradient {
        position: absolute; bottom: 0; left: 0; right: 0; height: 100px;
        background: linear-gradient(transparent, rgba(0,0,0,0.45));
    }
    .kadis-popup-content { padding: 1.5rem; }
    .kadis-popup-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, rgba(251,191,36,0.12), rgba(245,158,11,0.06));
        border: 1px solid rgba(251,191,36,0.2);
        border-radius: 10px; padding: 0.35rem 0.75rem;
        font-size: 0.7rem; font-weight: 700; color: #f59e0b;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    html.dark-mode .kadis-popup-modal { background: #1e293b; }
    html.dark-mode .kadis-popup-img-wrap { background: linear-gradient(135deg, #334155, #1e293b); }
    @media (max-width: 575.98px) {
        .kadis-popup-img-wrap { height: 260px; }
        .kadis-popup-modal { max-width: 100%; border-radius: 20px 20px 0 0; }
        .kadis-popup-overlay.active { align-items: flex-end; }
    }

    /* ===== MENU THUMBNAIL GRID ===== */
    .menu-thumb-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 220px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        text-decoration: none;
        color: #fff;
        background: #1e293b;
    }
    .menu-thumb-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    .menu-thumb-card .thumb-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        transition: transform 0.6s ease;
        z-index: 0;
    }
    .menu-thumb-card:hover .thumb-bg {
        transform: scale(1.08);
    }
    .menu-thumb-card .thumb-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 30%, rgba(15,23,42,0.92) 100%);
        z-index: 1;
    }
    .menu-thumb-card .thumb-content {
        position: relative;
        z-index: 2;
        padding: 1.25rem;
    }
    .menu-thumb-card .thumb-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 0.75rem;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.15);
    }
    .menu-thumb-card .thumb-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        letter-spacing: -0.3px;
    }
    .menu-thumb-card .thumb-desc {
        font-size: 0.75rem;
        opacity: 0.75;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .menu-thumb-card .thumb-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 2;
        padding: 0.3rem 0.65rem;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 700;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.15);
    }
    .menu-thumb-card .thumb-arrow {
        position: absolute;
        bottom: 16px;
        right: 16px;
        z-index: 2;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: translateX(-8px);
        transition: all 0.3s ease;
    }
    .menu-thumb-card:hover .thumb-arrow {
        opacity: 1;
        transform: translateX(0);
    }

    /* Large card variant */
    .menu-thumb-card.thumb-lg {
        height: 260px;
    }
    .menu-thumb-card.thumb-lg .thumb-title {
        font-size: 1.15rem;
    }
    .menu-thumb-card.thumb-lg .thumb-icon {
        width: 56px;
        height: 56px;
        font-size: 1.5rem;
    }

    /* Stats mini card */
    .stats-mini-card {
        padding: 0.75rem;
        border-radius: 12px;
        text-align: center;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(16px);
        transition: transform 0.3s;
    }
    .stats-mini-card:hover {
        transform: translateY(-3px);
    }
</style>
@endsection
@section('content')

<!-- ============================= -->
<!-- HERO SECTION -->
<!-- ============================= -->
<section class="text-white" style="padding:4.5rem 0 4rem;background-image:url('{{ asset('BG_ketahanan_pangan.jpeg') }}');background-size:cover;background-position:center;background-blend-mode:multiply;position:relative;min-height:520px;display:flex;align-items:center">
    <div style="position:absolute;inset:0;background:rgba(15,23,42,0.75);z-index:0"></div>
    <div class="container position-relative" style="z-index:2">
        <div class="row align-items-center g-5">
            <div class="col-lg-9 animate-fade-up">
                <span class="badge mb-3 px-3 py-2" style="background:rgba(99,102,241,0.25);color:#a5b4fc;border-radius:20px;font-size:0.75rem;backdrop-filter:blur(8px);border:1px solid rgba(99,102,241,0.2)">
                    <i class="bi bi-stars me-1"></i>Monitoring Harga Pangan & Ketahanan Pangan Kota Tual
                </span>
                <h1 class="fw-bold mb-3" style="letter-spacing:-1px;line-height:1.15;font-size:clamp(1.5rem,3.8vw,2.6rem)">
                    <span class="d-block" style="text-shadow:0 2px 10px rgba(0,0,0,0.3)">Pangan Cerdas <span style="background:linear-gradient(135deg,#f59e0b,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent">(Panca)</span></span>
                    <span class="d-block" style="background:linear-gradient(135deg,#10b981,#34d399);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-size:0.55em;font-weight:600">Maryadat Kota Tual</span>
                </h1>
                <p class="lead mb-1 opacity-90 fw-medium" style="font-size:1.05rem;text-shadow:0 1px 6px rgba(0,0,0,0.3)">Layanan Pangan Cerdas — Sistem Monitoring Harga Pangan & Ketersediaan Pangan Kota Tual</p>
                <p class="mb-4 opacity-60" style="font-size:0.85rem;text-shadow:0 1px 4px rgba(0,0,0,0.3)">Pemerintah Daerah Kota Tual, Provinsi Maluku</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#layanan" class="btn btn-primary-modern btn-lg px-4"><i class="bi bi-grid-3x3-gap me-2"></i>Layanan</a>
                    <a href="{{ route('frontend.commodities') }}" class="btn btn-glass btn-lg px-4"><i class="bi bi-bar-chart-line me-2"></i>Panel Harga</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- KADIS POPUP MODAL -->
<div class="kadis-popup-overlay" id="kadisPopupOverlay">
    <div class="kadis-popup-modal">
        <button class="kadis-popup-close" id="kadisPopupClose" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
        <div class="kadis-popup-img-wrap">
            <img src="{{ asset('kadis_pangan.jpeg') }}" alt="Ibu Darnawati Amir, S.KM, MM">
            <div class="kadis-popup-img-gradient"></div>
        </div>
        <div class="kadis-popup-content">
            <div class="text-center">
                <div class="mb-3">
                    <span class="kadis-popup-badge"><i class="bi bi-patch-check-fill"></i>Kepala Dinas</span>
                </div>
                <h5 class="fw-bold mb-1" style="font-size:1.15rem">Ibu Darnawati Amir, S.KM, MM</h5>
                <p class="text-muted mb-3" style="font-size:0.85rem">Dinas Ketahanan Pangan Kota Tual</p>
                <div class="row g-2 text-start">
                    <div class="col-6">
                        <div class="p-2" style="border-radius:12px;background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.1)">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-building" style="color:var(--primary);font-size:0.85rem"></i>
                                <div>
                                    <div style="font-size:0.6rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;font-weight:600">Instansi</div>
                                    <div class="fw-semibold" style="font-size:0.8rem">Pemerintah Kota Tual</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2" style="border-radius:12px;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.1)">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt" style="color:#10b981;font-size:0.85rem"></i>
                                <div>
                                    <div style="font-size:0.6rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;font-weight:600">Wilayah</div>
                                    <div class="fw-semibold" style="font-size:0.8rem">Kota Tual, Maluku</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2" style="border-radius:12px;background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.1)">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-award" style="color:#f59e0b;font-size:0.85rem"></i>
                                <div>
                                    <div style="font-size:0.6rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;font-weight:600">Pendidikan</div>
                                    <div class="fw-semibold" style="font-size:0.8rem">S.KM, MM</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2" style="border-radius:12px;background:rgba(14,165,233,0.06);border:1px solid rgba(14,165,233,0.1)">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check" style="color:#0ea5e9;font-size:0.85rem"></i>
                                <div>
                                    <div style="font-size:0.6rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;font-weight:600">Bidang</div>
                                    <div class="fw-semibold" style="font-size:0.8rem">Ketahanan Pangan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const overlay = document.getElementById('kadisPopupOverlay');
    const closeBtn = document.getElementById('kadisPopupClose');
    if (!overlay || !closeBtn) return;

    function openPopup() {
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closePopup() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    // Event delegation — works for any .kadis-popup-trigger (photo or button)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.kadis-popup-trigger')) {
            e.preventDefault();
            openPopup();
        }
    });
    closeBtn.addEventListener('click', closePopup);
    overlay.addEventListener('click', function(e) { if (e.target === overlay) closePopup(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closePopup(); });
})();
</script>

@include('frontend.partials.leaders')

<!-- ============================= -->
<!-- SECTION: KEPALA DINAS KETAHANAN PANGAN KOTA TUAL -->
<!-- ============================= -->
<section class="py-5" id="kepala-dinas" style="background:linear-gradient(180deg,rgba(245,158,11,0.04),rgba(99,102,241,0.03))">
    <div class="container">
        <div class="text-center mb-4" data-animate>
            <div class="section-title justify-content-center">
                <span class="icon-circle" style="background:rgba(245,158,11,0.12);color:var(--accent)"><i class="bi bi-person-workspace"></i></span>
                Kepala Dinas Ketahanan Pangan
            </div>
            <p class="text-muted mt-2 mb-0 mx-auto" style="font-size:0.85rem;max-width:640px">Pimpinan Dinas Ketahanan Pangan Kota Tual yang mengarahkan layanan Pangan Cerdas (Panca) Maryadat Kota Tual.</p>
        </div>
        <div class="row g-4 justify-content-center" data-animate style="max-width:380px;margin:0 auto">
            <div class="col-12">
                <div class="kadis-profile-card">
                    <div class="kadis-profile-photo-wrap kadis-popup-trigger" role="button" tabindex="0" aria-label="Lihat profil Kepala Dinas">
                        <img src="{{ asset('kadis_pangan.jpeg') }}" alt="Ibu Darnawati Amir, S.KM, MM" loading="lazy">
                        <div class="kadis-profile-photo-gradient"></div>
                        <span class="kadis-profile-role-badge"><i class="bi bi-patch-check-fill"></i>Kepala Dinas</span>
                        <div class="kadis-profile-info">
                            <h5>Ibu Darnawati Amir, S.KM, MM</h5>
                            <div class="kadis-instansi"><i class="bi bi-building-check"></i>Dinas Ketahanan Pangan Kota Tual</div>
                            <button type="button" class="kadis-profile-detail-btn"><i class="bi bi-info-circle"></i>Lihat Profil</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ALERT: Kenaikan Harga -->
@if($priceIncreases->count() > 0)
<section class="py-3" style="background:linear-gradient(135deg,rgba(239,68,68,0.08),rgba(245,158,11,0.05))">
    <div class="container">
        <div class="d-flex align-items-start gap-3 p-3" style="border-radius:12px;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.1)">
            <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:rgba(239,68,68,0.1)">
                <i class="bi bi-exclamation-triangle-fill" style="color:#ef4444;font-size:1.1rem"></i>
            </div>
            <div>
                <div class="fw-bold small" style="color:#ef4444">⚠️ Perhatian: {{ $priceIncreases->count() }} Komoditas Mengalami Kenaikan Harga</div>
                <small class="text-muted">
                    @foreach($priceIncreases->take(5) as $pi)
                    <span class="me-3"><strong>{{ $pi->commodity->name }}</strong> +{{ number_format($pi->change_percent,1) }}%</span>
                    @endforeach
                </small>
            </div>
        </div>
    </div>
</section>
@endif

<!-- ============================= -->
<!-- SECTION: LAYANAN — Thumbnail Grid dari Menu Header -->
<!-- ============================= -->
<section class="py-5" id="layanan">
    <div class="container">
        <div class="text-center mb-5" data-animate>
            <span class="badge mb-2 px-3 py-2" style="background:rgba(99,102,241,0.08);color:var(--primary);border-radius:20px;font-size:0.75rem;font-weight:600">
                <i class="bi bi-grid-3x3-gap me-1"></i>Layanan Utama
            </span>
            <h3 class="fw-bold mb-2" style="font-size:1.5rem;letter-spacing:-0.5px">Layanan Pangan Cerdas</h3>
            <p class="text-muted mx-auto" style="max-width:560px;font-size:0.9rem">Akses cepat ke seluruh layanan monitoring dan informasi pangan Kota Tual</p>
        </div>

        <div class="row g-4">

            <!-- 1. Panel Harga Pangan (Large) -->
            <div class="col-md-6 col-xl-4" data-animate>
                <a href="{{ route('frontend.commodities') }}" class="menu-thumb-card thumb-lg text-decoration-none" style="color:#fff">
                    <div class="thumb-bg" style="background-image:url('{{ asset('BG_ketahanan_pangan.jpeg') }}')"></div>
                    <div class="thumb-overlay"></div>
                    <div class="thumb-badge" style="background:rgba(99,102,241,0.25);color:#a5b4fc">
                        <i class="bi bi-graph-up-arrow me-1"></i>{{ $commodities->flatten()->count() }} Komoditas
                    </div>
                    <div class="thumb-content">
                        <div class="thumb-icon" style="background:rgba(99,102,241,0.3);color:#a5b4fc">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="thumb-title">Panel Harga Pangan</div>
                        <div class="thumb-desc">Data harga komoditas strategis hari ini, perbandingan bulan lalu, prediksi AI, dan status ketahanan pangan Kota Tual.</div>
                    </div>
                    <div class="thumb-arrow"><i class="bi bi-arrow-right"></i></div>
                </a>
            </div>

            <!-- 2. Ketersediaan Pangan -->
            <div class="col-sm-6 col-xl-4" data-animate>
                <a href="{{ route('frontend.availability') }}" class="menu-thumb-card text-decoration-none" style="color:#fff">
                    <div class="thumb-bg" style="background:linear-gradient(135deg,#064e3b,#10b981)"></div>
                    <div class="thumb-overlay"></div>
                    <div class="thumb-badge" style="background:rgba(16,185,129,0.25);color:#6ee7b7">
                        <i class="bi bi-boxes me-1"></i>{{ $stockStats['total'] }} Data
                    </div>
                    <div class="thumb-content">
                        <div class="thumb-icon" style="background:rgba(16,185,129,0.3);color:#6ee7b7">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <div class="thumb-title">Ketersediaan Pangan</div>
                        <div class="thumb-desc">Informasi stok dan ketahanan pangan di Kota Tual.</div>
                    </div>
                    <div class="thumb-arrow"><i class="bi bi-arrow-right"></i></div>
                </a>
            </div>

            <!-- 3. Kerawanan Pangan -->
            <div class="col-sm-6 col-xl-4" data-animate>
                <a href="{{ route('frontend.food-vulnerability') }}" class="menu-thumb-card text-decoration-none" style="color:#fff">
                    <div class="thumb-bg" style="background:linear-gradient(135deg,#78350f,#f59e0b)"></div>
                    <div class="thumb-overlay"></div>
                    <div class="thumb-badge" style="background:rgba(245,158,11,0.25);color:#fde68a">
                        <i class="bi bi-exclamation-diamond-fill me-1"></i>FSVA
                    </div>
                    <div class="thumb-content">
                        <div class="thumb-icon" style="background:rgba(245,158,11,0.3);color:#fde68a">
                            <i class="bi bi-exclamation-diamond-fill"></i>
                        </div>
                        <div class="thumb-title">Kerawanan Pangan</div>
                        <div class="thumb-desc">Analisis kerawanan pangan berdasarkan FSVA dan infografis.</div>
                    </div>
                    <div class="thumb-arrow"><i class="bi bi-arrow-right"></i></div>
                </a>
            </div>

            <!-- 4. Keamanan Pangan -->
            <div class="col-sm-6 col-xl-3" data-animate>
                <a href="{{ route('frontend.food-safety') }}" class="menu-thumb-card text-decoration-none" style="color:#fff">
                    <div class="thumb-bg" style="background:linear-gradient(135deg,#0c4a6e,#0ea5e9)"></div>
                    <div class="thumb-overlay"></div>
                    <div class="thumb-badge" style="background:rgba(14,165,233,0.25);color:#7dd3fc">
                        <i class="bi bi-shield-check me-1"></i>{{ $safetyStats['layak'] }} Layak
                    </div>
                    <div class="thumb-content">
                        <div class="thumb-icon" style="background:rgba(14,165,233,0.3);color:#7dd3fc">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="thumb-title">Keamanan Pangan</div>
                        <div class="thumb-desc">Data pemeriksaan keamanan dan mutu pangan.</div>
                    </div>
                    <div class="thumb-arrow"><i class="bi bi-arrow-right"></i></div>
                </a>
            </div>

            <!-- 5. Perizinan Pangan -->
            <div class="col-sm-6 col-xl-3" data-animate>
                <a href="{{ route('frontend.permits') }}" class="menu-thumb-card text-decoration-none" style="color:#fff">
                    <div class="thumb-bg" style="background:linear-gradient(135deg,#3b0764,#8b5cf6)"></div>
                    <div class="thumb-overlay"></div>
                    <div class="thumb-badge" style="background:rgba(139,92,246,0.25);color:#c4b5fd">
                        <i class="bi bi-file-earmark-text me-1"></i>{{ $totalVerifiedUmkms }} UMKM
                    </div>
                    <div class="thumb-content">
                        <div class="thumb-icon" style="background:rgba(139,92,246,0.3);color:#c4b5fd">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="thumb-title">Perizinan Pangan</div>
                        <div class="thumb-desc">Data perizinan bagi pelaku usaha pangan di Kota Tual.</div>
                    </div>
                    <div class="thumb-arrow"><i class="bi bi-arrow-right"></i></div>
                </a>
            </div>

            {{-- === MENU THUMB MARKETPLACE UMKM DINONAKTIFKAN (revisi 7 Juli 2026) === --}}
            {{--
            <!-- 6. Marketplace UMKM -->
            <div class="col-sm-6 col-xl-3" data-animate>
                <a href="{{ route('products.index') }}" class="menu-thumb-card text-decoration-none" style="color:#fff">
                    <div class="thumb-bg" style="background:linear-gradient(135deg,#831843,#ec4899)"></div>
                    <div class="thumb-overlay"></div>
                    <div class="thumb-badge" style="background:rgba(236,72,153,0.25);color:#f9a8d4">
                        <i class="bi bi-shop me-1"></i>{{ $totalUmkms }} UMKM
                    </div>
                    <div class="thumb-content">
                        <div class="thumb-icon" style="background:rgba(236,72,153,0.3);color:#f9a8d4">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div class="thumb-title">Marketplace UMKM</div>
                        <div class="thumb-desc">Belanja produk lokal UMKM Kota Tual secara digital.</div>
                    </div>
                    <div class="thumb-arrow"><i class="bi bi-arrow-right"></i></div>
                </a>
            </div>
            --}}

            <!-- 7. Profil / Informasi Kota Tual (Large) -->
            <div class="col-sm-6 col-xl-3" data-animate>
                @if($sitePages && $sitePages->count() > 0)
                <a href="{{ route('site.page', $sitePages->first()->slug) }}" class="menu-thumb-card text-decoration-none" style="color:#fff">
                @else
                <a href="{{ route('home') }}#profil" class="menu-thumb-card text-decoration-none" style="color:#fff">
                @endif
                    <div class="thumb-bg" style="background:linear-gradient(135deg,#1e3a5f,#3b82f6)"></div>
                    <div class="thumb-overlay"></div>
                    <div class="thumb-badge" style="background:rgba(59,130,246,0.25);color:#93c5fd">
                        <i class="bi bi-info-circle me-1"></i>Profil
                    </div>
                    <div class="thumb-content">
                        <div class="thumb-icon" style="background:rgba(59,130,246,0.3);color:#93c5fd">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="thumb-title">Profil Kota Tual</div>
                        <div class="thumb-desc">Informasi umum Kota Tual dan Dinas Ketahanan Pangan.</div>
                    </div>
                    <div class="thumb-arrow"><i class="bi bi-arrow-right"></i></div>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ============================= -->
<!-- SECTION: PANEL HARGA PANGAN (Ringkasan) -->
<!-- ============================= -->
@if($foodSecurity['summary']['total'] > 0)
<section class="py-5" id="panel-harga" style="background:var(--bg-card)">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="section-title" data-animate>
                <span class="icon-circle" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-shield-check"></i></span>
                Status Ketahanan Pangan Kota Tual
            </div>
            <a href="{{ route('frontend.commodities') }}" class="btn btn-sm fw-semibold" style="color:var(--primary)">Lihat Detail <i class="bi bi-arrow-right"></i></a>
        </div>

        <!-- Index Bar -->
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap" data-animate>
            <small class="text-muted fw-medium">Indeks Keseluruhan:</small>
            <div style="width:160px;height:10px;border-radius:10px;background:#e2e8f0;overflow:hidden">
                @php $fsIndex = $foodSecurity['overall_index'] ?? 0; @endphp
                <div style="width:{{ $fsIndex }}%;height:100%;border-radius:10px;background:{{ $fsIndex >= 70 ? 'linear-gradient(90deg,#10b981,#34d399)' : ($fsIndex >= 50 ? 'linear-gradient(90deg,#f59e0b,#fbbf24)' : 'linear-gradient(90deg,#ef4444,#f87171)') }};transition:width 1s ease"></div>
            </div>
            <span class="fw-bold" style="font-size:1.1rem;color:{{ $fsIndex >= 70 ? '#10b981' : ($fsIndex >= 50 ? '#f59e0b' : '#ef4444') }}">{{ $fsIndex }}/100</span>
            <div class="d-flex flex-wrap gap-2 ms-auto">
                <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.72rem"><i class="bi bi-shield-check me-1"></i>Aman {{ $foodSecurity['summary']['aman'] ?? 0 }}</span>
                <span class="badge-modern" style="background:rgba(14,165,233,0.1);color:#0ea5e9;font-size:0.72rem"><i class="bi bi-shield-fill-exclamation me-1"></i>Cukup {{ $foodSecurity['summary']['cukup'] ?? 0 }}</span>
                <span class="badge-modern" style="background:rgba(245,158,11,0.1);color:#f59e0b;font-size:0.72rem"><i class="bi bi-exclamation-triangle me-1"></i>Rawan {{ $foodSecurity['summary']['rawan'] ?? 0 }}</span>
                <span class="badge-modern" style="background:rgba(239,68,68,0.1);color:#ef4444;font-size:0.72rem"><i class="bi bi-shield-fill-x me-1"></i>Kritis {{ $foodSecurity['summary']['kritis'] ?? 0 }}</span>
            </div>
        </div>

        <!-- Commodity Grid Summary -->
        @php
            $commodityWithSecurity = collect($foodSecurity['commodities'] ?? [])
                ->filter(fn($c) => !empty($c['has_data']))
                ->sortBy(fn($c) => ['kritis'=>0,'rawan'=>1,'cukup'=>2,'aman'=>3][$c['status'] ?? ''] ?? 4);
        @endphp
        <div class="row g-2" data-animate>
            @foreach($commodityWithSecurity->take(12) as $cs)
            @php
                $csBg     = $cs['bg_color'] ?? 'transparent';
                $csBdr    = $cs['border_color'] ?? '#e2e8f0';
                $csClr    = $cs['status_color'] ?? '#64748b';
                $csIcon   = $cs['status_icon'] ?? 'dash-circle';
                $csLabel  = $cs['status_label'] ?? '-';
                $csName   = $cs['name'] ?? '-';
                $csUnit   = $cs['unit'] ?? '';
                $csImg    = $cs['image_url'] ?? null;
                $csPrice  = $cs['current_price'] ?? 0;
                $csChange = $cs['change_percent'] ?? 0;
                $csPred   = $cs['prediction'] ?? [];
                $pdir     = $csPred['direction'] ?? 'unknown';
                $pdirIcon = match($pdir) { 'up' => 'arrow-up', 'up_slight' => 'arrow-up-right', 'stable' => 'arrow-right', 'down_slight' => 'arrow-down-right', 'down' => 'arrow-down', default => 'dash' };
                $pdirClr  = match($pdir) { 'up' => '#ef4444', 'up_slight' => '#f59e0b', 'stable' => '#0ea5e9', 'down_slight' => '#10b981', 'down' => '#10b981', default => '#64748b' };
            @endphp
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="{{ route('frontend.commodities') }}" class="text-decoration-none" style="color:inherit">
                    <div class="commodity-card h-100 commodity-popup-trigger" style="border-left:4px solid {{ $csClr }};background:{{ $csBg }}"
                        data-commodity-name="{{ $csName }}"
                        data-commodity-unit="{{ $csUnit }}"
                        data-commodity-img="{{ $csImg }}"
                        data-commodity-price="{{ number_format($csPrice, 0, ',', '.') }}"
                        data-commodity-price-previous="0"
                        data-commodity-change="{{ $csChange }}"
                        data-commodity-date="-"
                        data-commodity-desc="Status: {{ $csLabel }}"
                        data-commodity-het="{{ isset($cs['het']) ? number_format($cs['het'], 0, ',', '.') : '' }}"
                        data-commodity-hap="{{ isset($cs['hap']) ? number_format($cs['hap'], 0, ',', '.') : '' }}"
                        data-commodity-hpp="{{ isset($cs['hpp']) ? number_format($cs['hpp'], 0, ',', '.') : '' }}">
                        @if($csImg)
                        <div style="margin:-1rem -1rem 0.5rem;overflow:hidden;border-radius:var(--radius) var(--radius) 0 0;height:64px">
                            <img src="{{ $csImg }}" alt="{{ $csName }}" style="width:100%;height:100%;object-fit:cover" loading="lazy">
                        </div>
                        @endif
                        <div class="d-flex align-items-start justify-content-between mb-1">
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size:0.78rem">{{ $csName }}</h6>
                                <small class="text-muted" style="font-size:0.62rem">{{ $csUnit }}</small>
                            </div>
                            <span class="badge" style="background:{{ $csBdr }};color:{{ $csClr }};font-size:0.52rem;border-radius:6px;font-weight:600">
                                <i class="bi bi-{{ $csIcon }} me-1"></i>{{ $csLabel }}
                            </span>
                        </div>
                        <div class="fw-bold" style="font-size:0.9rem;color:{{ $csClr }}">Rp {{ number_format($csPrice, 0, ',', '.') }}</div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <small style="font-size:0.6rem;color:{{ $pdirClr }}">
                                <i class="bi bi-{{ $pdirIcon }} me-1"></i>{{ $csPred['direction_label'] ?? '-' }}
                            </small>
                            <small class="text-muted" style="font-size:0.58rem">{{ $csChange > 0 ? '+' : '' }}{{ number_format($csChange, 1) }}%</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('frontend.commodities') }}" class="btn btn-primary-modern btn-sm px-4"><i class="bi bi-bar-chart-line me-2"></i>Lihat Panel Harga Lengkap</a>
        </div>
    </div>
</section>
@endif

<!-- ============================= -->
<!-- SECTION: PROFIL — Singkat dari database -->
<!-- ============================= -->
@if ($sitePages && $sitePages->count() > 0)
<section class="py-5" id="profil">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="section-title" data-animate>
                <span class="icon-circle" style="background:rgba(99,102,241,0.1);color:var(--primary)"><i class="bi bi-info-circle"></i></span>
                Profil
            </div>
            <a href="{{ route('site.page', $sitePages->first()->slug) }}" class="btn btn-sm fw-semibold" style="color:var(--primary)">Lihat Detail <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4" data-animate>
            @foreach($sitePages as $page)
            <div class="col-md-6">
                <a href="{{ route('site.page', $page->slug) }}" class="text-decoration-none" style="color:inherit">
                    <div class="service-card" style="border-top:4px solid {{ $page->color ?? 'var(--primary)' }};cursor:pointer">
                        <div class="d-flex align-items-start gap-3">
                            @if($page->image)
                                <img src="{{ Storage::url($page->image) }}" alt="{{ $page->title }}" style="width:48px;height:48px;border-radius:12px;object-fit:cover;flex-shrink:0">
                            @else
                                <div class="service-icon" style="background:{{ $page->color ?? 'var(--primary)' }}20;color:{{ $page->color ?? 'var(--primary)' }};width:48px;height:48px;border-radius:12px">
                                    <i class="bi bi-{{ $page->icon ?? 'building' }}"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1" style="font-size:0.95rem">{{ $page->title }}</h5>
                                <p class="text-muted mb-2" style="font-size:0.8rem;line-height:1.5">{{ Str::limit($page->content, 150) }}</p>
                                <span class="fw-semibold" style="font-size:0.75rem;color:{{ $page->color ?? 'var(--primary)' }}"><i class="bi bi-arrow-right me-1"></i>Baca selengkapnya</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- === SECTION MARKETPLACE UMKM DINONAKTIFKAN (revisi 7 Juli 2026) === --}}
{{--
<!-- MARKPLACE SECTION (Ringkasan) -->
<section class="py-5" style="background:var(--bg-card)">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="section-title" data-animate>
                <span class="icon-circle" style="background:rgba(245,158,11,0.1);color:var(--accent)"><i class="bi bi-shop"></i></span>
                Marketplace UMKM
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-sm fw-semibold" style="color:var(--primary)">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            @foreach($featured->take(8) as $product)
            <div class="col-6 col-md-4 col-lg-3" data-animate>
                <div class="card card-product h-100">
                    <div class="img-wrapper position-relative">
                        <img src="{{ $product->image_url ?? 'https://placehold.co/400x300/1e1b4b/a5b4fc?text='.urlencode($product->name) }}" class="product-img card-img-top" alt="{{ $product->name }}" loading="lazy">
                        @if($product->discount_price)
                            <span class="badge badge-discount" style="background:linear-gradient(135deg,#ef4444,#f97316)">-{{ $product->discount_percent }}%</span>
                        @endif
                    </div>
                    <div class="card-body p-3">
                        <small class="text-muted" style="font-size:0.72rem">{{ $product->umkm->business_name ?? 'UMKM' }}</small>
                        <h6 class="fw-bold mb-1 mt-1" style="font-size:0.85rem;min-height:36px">{{ Str::limit($product->name, 35) }}</h6>
                        <div class="fw-bold" style="color:var(--primary);font-size:1rem">Rp {{ number_format($product->effective_price, 0, ',', '.') }}</div>
                        @if($product->discount_price)
                            <span class="price-original" style="font-size:0.75rem">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                        <div class="d-flex justify-content-between mt-2 align-items-center">
                            <small class="text-muted"><i class="bi bi-star-fill text-warning" style="font-size:0.65rem"></i> {{ $product->rating }}</small>
                            <small class="text-muted" style="font-size:0.72rem">Terjual {{ $product->total_sold }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach>
        </div>
    </div>
</section>
--}}

<!-- ============================= -->
<!-- COMMODITY POPUP MODAL (with HET/HAP/HPP) -->
<!-- ============================= -->
<div class="commodity-popup-overlay" id="commodityPopupOverlay">
    <div class="commodity-popup-modal" id="commodityPopupModal">
        <button class="commodity-popup-close" id="commodityPopupClose" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
        <div class="commodity-popup-body">
            <div class="commodity-popup-img-wrap" id="popupImageWrap">
                <img id="popupImage" src="" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius) var(--radius) 0 0">
                <div class="commodity-popup-img-placeholder" id="popupImagePlaceholder">
                    <i class="bi bi-basket" style="font-size:2.5rem;opacity:0.3"></i>
                </div>
            </div>
            <div class="commodity-popup-content">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div>
                        <h5 class="fw-bold mb-0" id="popupName" style="font-size:1.1rem"></h5>
                        <small class="text-muted" id="popupUnit" style="font-size:0.78rem"></small>
                    </div>
                    <span class="badge-modern" id="popupChangeBadge" style="font-size:0.75rem"></span>
                </div>

                <!-- Price Info -->
                <div class="commodity-popup-price-box mb-3">
                    <div class="commodity-popup-current-price" id="popupPrice">Rp 0</div>
                    <div class="d-flex align-items-center gap-3 mt-1">
                        <small class="price-original" id="popupPricePrevious" style="font-size:0.78rem"></small>
                        <small class="text-muted" id="popupDate" style="font-size:0.72rem"><i class="bi bi-clock me-1"></i></small>
                    </div>
                    <div class="mt-2" id="popupChangeInfo"></div>
                </div>

                <!-- HET / HAP / HPP -->
                <div class="mb-3" id="popupHETWrap" style="display:none">
                    <h6 class="fw-bold mb-2" style="font-size:0.82rem;color:var(--primary)"><i class="bi bi-tags me-1"></i>Informasi Harga Referensi</h6>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="p-2 text-center" style="border-radius:10px;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.12)">
                                <div class="fw-bold" style="font-size:0.65rem;color:#ef4444;margin-bottom:2px">HET</div>
                                <div class="fw-bold" id="popupHET" style="font-size:0.85rem;color:#ef4444">-</div>
                                <div style="font-size:0.55rem;color:#94a3b8">Harga Eceran Tertinggi</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 text-center" style="border-radius:10px;background:rgba(14,165,233,0.06);border:1px solid rgba(14,165,233,0.12)">
                                <div class="fw-bold" style="font-size:0.65rem;color:#0ea5e9;margin-bottom:2px">HAP</div>
                                <div class="fw-bold" id="popupHAP" style="font-size:0.85rem;color:#0ea5e9">-</div>
                                <div style="font-size:0.55rem;color:#94a3b8">Harga Acuan Pemerintah</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 text-center" style="border-radius:10px;background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.12)">
                                <div class="fw-bold" style="font-size:0.65rem;color:#f59e0b;margin-bottom:2px">HPP</div>
                                <div class="fw-bold" id="popupHPP" style="font-size:0.85rem;color:#f59e0b">-</div>
                                <div style="font-size:0.55rem;color:#94a3b8">Harga Pokok Penjualan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3" id="popupDescWrap" style="display:none">
                    <h6 class="fw-bold mb-1" style="font-size:0.82rem;color:var(--primary)"><i class="bi bi-info-circle me-1"></i>Informasi</h6>
                    <p class="text-muted small mb-0" id="popupDesc" style="font-size:0.82rem;line-height:1.6"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Commodity Popup */
.commodity-popup-overlay {
    position: fixed; inset: 0; z-index: 100000;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);
    display: none; align-items: center; justify-content: center;
    padding: 1rem;
    animation: popupFadeIn 0.25s ease;
}
.commodity-popup-overlay.active { display: flex; }
@keyframes popupFadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes popupSlideUp { from { opacity: 0; transform: translateY(40px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }

.commodity-popup-modal {
    background: var(--bg-card); border-radius: 20px;
    width: 100%; max-width: 480px;
    box-shadow: 0 25px 80px rgba(0,0,0,0.3);
    animation: popupSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative; overflow: hidden;
    max-height: 90vh; overflow-y: auto;
}
.commodity-popup-close {
    position: absolute; top: 12px; right: 12px; z-index: 10;
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(0,0,0,0.5); backdrop-filter: blur(8px);
    border: none; color: #fff; font-size: 0.85rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.2s;
}
.commodity-popup-close:hover { background: rgba(239,68,68,0.8); transform: scale(1.1); }

.commodity-popup-img-wrap {
    width: 100%; height: 180px; position: relative;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
}
.commodity-popup-img-wrap img { display: block; }
.commodity-popup-img-placeholder {
    position: absolute; inset: 0; display: flex;
    align-items: center; justify-content: center;
}
.commodity-popup-img-placeholder { display: none; }
.commodity-popup-img-wrap.no-img .commodity-popup-img-placeholder { display: flex; }
.commodity-popup-img-wrap.no-img img { display: none; }

.commodity-popup-content { padding: 1.25rem; }

.commodity-popup-price-box {
    background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(14,165,233,0.04));
    border: 1px solid rgba(99,102,241,0.1);
    border-radius: 12px; padding: 1rem;
}
.commodity-popup-current-price {
    font-size: 1.5rem; font-weight: 800; color: var(--primary);
}

html.dark-mode .commodity-popup-modal { background: #1e293b; }
html.dark-mode .commodity-popup-img-wrap { background: linear-gradient(135deg, #334155, #1e293b); }
html.dark-mode .commodity-popup-price-box { background: rgba(99,102,241,0.08); border-color: rgba(99,102,241,0.15); }

@media (max-width: 575.98px) {
    .commodity-popup-modal { max-width: 100%; border-radius: 16px 16px 0 0; margin-top: auto; max-height: 85vh; }
    .commodity-popup-overlay.active { align-items: flex-end; }
    .commodity-popup-img-wrap { height: 140px; }
}
</style>

<script>
(function() {
    const overlay = document.getElementById('commodityPopupOverlay');
    const modal = document.getElementById('commodityPopupModal');
    const closeBtn = document.getElementById('commodityPopupClose');
    if (!overlay || !modal || !closeBtn) return;

    function openPopup(card) {
        const name = card.dataset.commodityName || '-';
        const unit = card.dataset.commodityUnit || '';
        const img = card.dataset.commodityImg || '';
        const desc = card.dataset.commodityDesc || '';
        const price = card.dataset.commodityPrice || '0';
        const pricePrev = card.dataset.commodityPricePrevious || '0';
        const change = parseFloat(card.dataset.commodityChange) || 0;
        const date = card.dataset.commodityDate || '-';
        const het = card.dataset.commodityHet || '';
        const hap = card.dataset.commodityHap || '';
        const hpp = card.dataset.commodityHpp || '';

        // Image
        const imgWrap = document.getElementById('popupImageWrap');
        const imgEl = document.getElementById('popupImage');
        if (img) {
            imgEl.src = img;
            imgEl.alt = name;
            imgWrap.classList.remove('no-img');
        } else {
            imgWrap.classList.add('no-img');
        }

        // Name & Unit
        document.getElementById('popupName').textContent = name;
        document.getElementById('popupUnit').textContent = unit;

        // Price
        document.getElementById('popupPrice').textContent = 'Rp ' + price;

        // Previous price
        const prevEl = document.getElementById('popupPricePrevious');
        if (pricePrev !== price && parseFloat(pricePrev) > 0) {
            prevEl.textContent = 'Rp ' + pricePrev;
            prevEl.style.display = '';
        } else {
            prevEl.style.display = 'none';
        }

        // Date
        document.getElementById('popupDate').innerHTML = '<i class="bi bi-clock me-1"></i>' + date;

        // Change badge
        const badge = document.getElementById('popupChangeBadge');
        const changeInfo = document.getElementById('popupChangeInfo');
        if (change > 0) {
            badge.className = 'badge-modern price-up';
            badge.innerHTML = '<i class="bi bi-arrow-up-short"></i>+' + change.toFixed(1) + '%';
            changeInfo.innerHTML = '<span style="color:#ef4444;font-size:0.78rem"><i class="bi bi-arrow-up-circle me-1"></i>Harga naik ' + change.toFixed(1) + '% dibanding periode sebelumnya</span>';
        } else if (change < 0) {
            badge.className = 'badge-modern price-down';
            badge.innerHTML = '<i class="bi bi-arrow-down-short"></i>' + change.toFixed(1) + '%';
            changeInfo.innerHTML = '<span style="color:#10b981;font-size:0.78rem"><i class="bi bi-arrow-down-circle me-1"></i>Harga turun ' + Math.abs(change).toFixed(1) + '% dibanding periode sebelumnya</span>';
        } else {
            badge.className = 'badge-modern price-stable';
            badge.innerHTML = '<i class="bi bi-dash"></i>0%';
            changeInfo.innerHTML = '<span style="color:#64748b;font-size:0.78rem"><i class="bi bi-dash-circle me-1"></i>Harga stabil</span>';
        }

        // HET / HAP / HPP
        const hetWrap = document.getElementById('popupHETWrap');
        const hasHET = het && het !== '' && het !== '0';
        const hasHAP = hap && hap !== '' && hap !== '0';
        const hasHPP = hpp && hpp !== '' && hpp !== '0';
        if (hasHET || hasHAP || hasHPP) {
            hetWrap.style.display = '';
            document.getElementById('popupHET').textContent = hasHET ? 'Rp ' + het : '-';
            document.getElementById('popupHAP').textContent = hasHAP ? 'Rp ' + hap : '-';
            document.getElementById('popupHPP').textContent = hasHPP ? 'Rp ' + hpp : '-';
        } else {
            hetWrap.style.display = 'none';
        }

        // Description
        const descWrap = document.getElementById('popupDescWrap');
        if (desc) {
            document.getElementById('popupDesc').textContent = desc;
            descWrap.style.display = '';
        } else {
            descWrap.style.display = 'none';
        }

        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closePopup() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    closeBtn.addEventListener('click', closePopup);
    overlay.addEventListener('click', function(e) { if (e.target === overlay) closePopup(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closePopup(); });

    document.addEventListener('click', function(e) {
        const card = e.target.closest('.commodity-popup-trigger');
        if (card) {
            e.preventDefault();
            openPopup(card);
        }
    });
})();
</script>
@endsection
