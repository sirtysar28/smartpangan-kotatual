<footer class="mt-5" style="background:var(--dark)">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3" style="color:#ffffff !important">
                    <span class="d-inline-flex align-items-center gap-2">
                        <img src="{{ asset('logo.png') }}" alt="Pangan Cerdas (Panca) Maryadat Kota Tual" style="width:32px;height:32px;border-radius:8px">
                        Pangan Cerdas <span style="background:linear-gradient(135deg,#f59e0b,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent">(Panca)</span>
                    </span>
                </h5>
                <p class="small" style="color:rgba(255,255,255,0.65) !important">Layanan Pangan Cerdas — Sistem Monitoring Harga Pangan & Ketersediaan Pangan untuk Pemerintah Daerah Kota Tual, Provinsi Maluku. Didukung teknologi AI untuk pengendalian inflasi berbasis data.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.7) !important;border-radius:10px;width:38px;height:38px;display:flex;align-items:center;justify-content:center"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.7) !important;border-radius:10px;width:38px;height:38px;display:flex;align-items:center;justify-content:center"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.7) !important;border-radius:10px;width:38px;height:38px;display:flex;align-items:center;justify-content:center"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="fw-bold mb-3 small text-uppercase" style="color:#ffffff !important;letter-spacing:1px">Menu</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="{{ route('home') }}#profil" class="text-decoration-none" style="color:rgba(255,255,255,0.65) !important">Profil</a></li>
                    <li><a href="{{ route('home') }}" class="text-decoration-none" style="color:rgba(255,255,255,0.65) !important">Beranda</a></li>
                    <li><a href="{{ route('frontend.commodities') }}" class="text-decoration-none" style="color:rgba(255,255,255,0.65) !important">Panel Harga Pangan</a></li>
                    <li><a href="{{ route('home') }}#ketersediaan-pangan" class="text-decoration-none" style="color:rgba(255,255,255,0.65) !important">Ketersediaan Pangan</a></li>
                    <li><a href="{{ route('home') }}#kerawanan-pangan" class="text-decoration-none" style="color:rgba(255,255,255,0.65) !important">Kerawanan Pangan</a></li>
                    <li><a href="{{ route('home') }}#keamanan-pangan" class="text-decoration-none" style="color:rgba(255,255,255,0.65) !important">Keamanan Pangan</a></li>
                    <li><a href="{{ route('home') }}#perizinan-informasi" class="text-decoration-none" style="color:rgba(255,255,255,0.65) !important">Laporan Perizinan</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-6">
                <h6 class="fw-bold mb-3 small text-uppercase" style="color:#ffffff !important;letter-spacing:1px">Kontak</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2" style="color:rgba(255,255,255,0.65) !important">
                    <li><i class="bi bi-geo-alt me-2" style="color:#6366f1"></i>Kota Tual, Maluku</li>
                    <li><i class="bi bi-telephone me-2" style="color:#6366f1"></i>(0916) 000000</li>
                    <li><i class="bi bi-envelope me-2" style="color:#6366f1"></i>info@pancactical.id</li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="fw-bold mb-3 small text-uppercase" style="color:#ffffff !important;letter-spacing:1px">Didukung</h6>
                <div class="glass-card p-3 text-center" style="background:rgba(255,255,255,0.05);border-color:rgba(255,255,255,0.08)">
                    <i class="bi bi-robot display-5" style="background:linear-gradient(135deg,#6366f1,#0ea5e9);-webkit-background-clip:text;-webkit-text-fill-color:transparent"></i>
                    <p class="small mt-2 mb-0" style="color:rgba(255,255,255,0.55) !important">Powered by Groq AI</p>
                </div>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,0.08);margin:2rem 0 1rem">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <p class="small mb-0" style="color:rgba(255,255,255,0.45) !important">&copy; {{ date('Y') }} Pangan Cerdas (Panca) Maryadat Kota Tual — Pemerintah Kota Tual</p>
            <p class="small mb-0" style="color:rgba(255,255,255,0.45) !important">
                Developed by <a href="https://ciptakaryadigital.id/new" target="_blank" rel="noopener" class="text-decoration-none fw-semibold" style="color:rgba(255,255,255,0.75) !important">CV Cipta Karya Digital Indonesia</a>
            </p>
        </div>
    </div>

    {{-- ===== 5 ICON NAVIGASI KHUSUS MOBILE (bottom nav ala aplikasi) ===== --}}
    @include('frontend.partials.mobile-nav')
</footer>
