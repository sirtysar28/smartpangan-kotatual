{{-- ===== FOOTER DASHBOARD ADMIN — lengkap ala landing page ===== --}}
<footer class="admin-footer mt-4">
    <div class="container-fluid px-3 px-md-4">
        <div class="row g-4 py-4">
            {{-- Brand --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3" style="color:#fff">
                    <span class="d-inline-flex align-items-center gap-2">
                        <img src="{{ asset('logo.png') }}" alt="Pangan Cerdas (Panca) Maryadat Kota Tual" style="width:32px;height:32px;border-radius:8px">
                        Pangan Cerdas <span style="background:linear-gradient(135deg,#f59e0b,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent">(Panca)</span>
                    </span>
                </h5>
                <p class="small mb-3" style="color:rgba(255,255,255,0.6)">Layanan Pangan Cerdas — Sistem Monitoring Harga Pangan &amp; Ketersediaan Pangan untuk Pemerintah Daerah Kota Tual, Provinsi Maluku. Didukung teknologi AI untuk pengendalian inflasi berbasis data.</p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-sm af-social" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-sm af-social" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-sm af-social" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            {{-- Menu admin --}}
            <div class="col-lg-2 col-6">
                <h6 class="fw-bold mb-3 small text-uppercase af-heading">Menu</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                    <li><a href="{{ route('admin.dashboard') }}" class="af-link">Dashboard</a></li>
                    <li><a href="{{ route('admin.commodities.index') }}" class="af-link">Panel Harga</a></li>
                    <li><a href="{{ route('admin.availability.index') }}" class="af-link">Ketersediaan</a></li>
                    <li><a href="{{ route('admin.food-vulnerability.index') }}" class="af-link">Kerawanan Pangan</a></li>
                    <li><a href="{{ route('admin.food-safety.index') }}" class="af-link">Keamanan Pangan</a></li>
                    <li><a href="{{ route('admin.permits.index') }}" class="af-link">Perizinan</a></li>
                    <li><a href="{{ route('admin.reports.index') }}" class="af-link">Laporan</a></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div class="col-lg-3 col-6">
                <h6 class="fw-bold mb-3 small text-uppercase af-heading">Kontak</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0" style="color:rgba(255,255,255,0.6)">
                    <li><i class="bi bi-geo-alt me-2" style="color:#818cf8"></i>Kota Tual, Maluku</li>
                    <li><i class="bi bi-telephone me-2" style="color:#818cf8"></i>(0916) 000000</li>
                    <li><i class="bi bi-envelope me-2" style="color:#818cf8"></i>info@pancactical.id</li>
                </ul>
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-sm mt-3 af-visit">
                    <i class="bi bi-globe2 me-1"></i>Lihat Halaman Publik
                </a>
            </div>

            {{-- Didukung --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3 small text-uppercase af-heading">Didukung</h6>
                <div class="af-glass p-3 text-center">
                    <i class="bi bi-robot display-6" style="background:linear-gradient(135deg,#6366f1,#0ea5e9);-webkit-background-clip:text;-webkit-text-fill-color:transparent"></i>
                    <p class="small mt-2 mb-1" style="color:rgba(255,255,255,0.55)">Powered by Groq AI</p>
                    <p class="small mb-0" style="color:rgba(255,255,255,0.35)">GPT-OSS 120B &middot; Realtime</p>
                </div>
            </div>
        </div>

        <hr class="af-divider">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-3">
            <p class="small mb-0 af-copy">&copy; {{ date('Y') }} Pangan Cerdas (Panca) Maryadat Kota Tual — Pemerintah Kota Tual</p>
            <p class="small mb-0 af-copy">
                Developed by <a href="https://ciptakaryadigital.id/new" target="_blank" rel="noopener" class="text-decoration-none fw-semibold" style="color:rgba(255,255,255,0.75)">CV Cipta Karya Digital Indonesia</a>
            </p>
        </div>
    </div>
</footer>

<style>
    .admin-footer {
        background: linear-gradient(180deg, #0f172a 0%, #0a0f1c 100%);
        border-radius: 20px 20px 0 0;
        box-shadow: 0 -6px 30px rgba(2,6,23,0.18);
        position: relative;
        overflow: hidden;
    }
    .admin-footer::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, #6366f1, #0ea5e9, #f59e0b);
        border-radius: 20px 20px 0 0;
    }
    .admin-footer .af-heading { color: #fff; letter-spacing: 1px; }
    .admin-footer .af-link {
        color: rgba(255,255,255,0.6); text-decoration: none; transition: all .2s ease;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .admin-footer .af-link::before { content: '\25B8'; font-size: .6rem; opacity: 0; margin-left: -8px; transition: all .2s ease; }
    .admin-footer .af-link:hover { color: #fff; }
    .admin-footer .af-link:hover::before { opacity: 1; margin-left: 0; }
    .admin-footer .af-social {
        background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.7);
        border-radius: 10px; width: 38px; height: 38px;
        display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.06);
        transition: all .25s ease;
    }
    .admin-footer .af-social:hover {
        background: linear-gradient(135deg, var(--primary, #6366f1), var(--secondary, #0ea5e9));
        color: #fff; transform: translateY(-2px); box-shadow: 0 6px 18px rgba(99,102,241,0.35);
    }
    .admin-footer .af-visit {
        background: rgba(99,102,241,0.12); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.25);
        border-radius: 10px; font-weight: 600; transition: all .25s ease;
    }
    .admin-footer .af-visit:hover { background: linear-gradient(135deg, #6366f1, #0ea5e9); color: #fff; transform: translateY(-2px); }
    .admin-footer .af-glass {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px;
    }
    .admin-footer .af-divider { border-color: rgba(255,255,255,0.08); margin: 0 0 1rem; opacity: 1; }
    .admin-footer .af-copy { color: rgba(255,255,255,0.4); }
    @media (max-width: 767.98px) {
        .admin-footer { border-radius: 16px 16px 0 0; }
        .admin-footer .py-4 { padding-top: 1.25rem !important; padding-bottom: 1.25rem !important; }
    }
</style>
