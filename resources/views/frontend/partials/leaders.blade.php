{{--
    PIMPINAN DAERAH KOTA TUAL (Wali Kota & Wakil Wali Kota)
    Self-contained: CSS + Section + Popup Modal + Script (event delegation).
    Include anywhere: @include('frontend.partials.leaders')
--}}
<style>
    .leader-card {
        position: relative;
        background: var(--bg-card);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: transform 0.4s cubic-bezier(0.16,1,0.3,1), box-shadow 0.4s ease;
        border: 1px solid rgba(99,102,241,0.08);
        height: 100%;
    }
    .leader-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 50px rgba(15,23,42,0.18);
    }
    .leader-photo-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
    }
    .leader-photo-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        transition: transform 0.6s cubic-bezier(0.16,1,0.3,1);
    }
    .leader-card:hover .leader-photo-wrap img { transform: scale(1.05); }
    .leader-photo-gradient {
        position: absolute; bottom: 0; left: 0; right: 0; height: 60%;
        background: linear-gradient(transparent, rgba(15,23,42,0.92));
    }
    .leader-role-badge {
        position: absolute; top: 14px; left: 14px;
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,0.92); backdrop-filter: blur(10px);
        border-radius: 30px; padding: 0.35rem 0.8rem;
        font-size: 0.68rem; font-weight: 700;
        color: var(--primary);
        text-transform: uppercase; letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .leader-role-badge.wakil { color: var(--accent); }
    .leader-info {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 1.1rem 1.25rem; color: #fff;
    }
    .leader-info h5 {
        font-weight: 700; margin-bottom: 0.2rem;
        font-size: 1.02rem; line-height: 1.3;
        text-shadow: 0 2px 8px rgba(0,0,0,0.4);
    }
    .leader-info .leader-instansi {
        font-size: 0.78rem; opacity: 0.85;
        display: flex; align-items: center; gap: 5px;
    }
    .leader-detail-btn {
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
    .leader-detail-btn:hover {
        background: rgba(251,191,36,0.2);
        border-color: rgba(251,191,36,0.4);
        transform: translateX(3px);
    }
    html.dark-mode .leader-card { border-color: rgba(255,255,255,0.06); }

    /* POPUP MODAL */
    @keyframes leaderPopupFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes leaderPopupSlideUp { from { opacity: 0; transform: translateY(40px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .leader-popup-overlay {
        position: fixed; inset: 0; z-index: 100000;
        background: rgba(0,0,0,0.65); backdrop-filter: blur(10px);
        display: none; align-items: center; justify-content: center;
        padding: 1rem;
        animation: leaderPopupFadeIn 0.25s ease;
    }
    .leader-popup-overlay.active { display: flex; }
    .leader-popup-modal {
        background: var(--bg-card); border-radius: 24px;
        width: 100%; max-width: 440px;
        box-shadow: 0 25px 80px rgba(0,0,0,0.3);
        animation: leaderPopupSlideUp 0.4s cubic-bezier(0.16,1,0.3,1);
        position: relative; overflow: hidden;
    }
    .leader-popup-close {
        position: absolute; top: 14px; right: 14px; z-index: 10;
        width: 36px; height: 36px; border-radius: 50%;
        background: rgba(0,0,0,0.45); backdrop-filter: blur(8px);
        border: none; color: #fff; font-size: 0.85rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    .leader-popup-close:hover { background: rgba(239,68,68,0.8); transform: scale(1.1); }
    .leader-popup-img-wrap {
        width: 100%; height: 340px; position: relative;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        overflow: hidden;
    }
    .leader-popup-img-wrap img { width: 100%; height: 100%; object-fit: cover; object-position: top center; }
    .leader-popup-img-gradient {
        position: absolute; bottom: 0; left: 0; right: 0; height: 100px;
        background: linear-gradient(transparent, rgba(0,0,0,0.45));
    }
    .leader-popup-content { padding: 1.5rem; }
    .leader-popup-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, rgba(251,191,36,0.12), rgba(245,158,11,0.06));
        border: 1px solid rgba(251,191,36,0.2);
        border-radius: 10px; padding: 0.35rem 0.75rem;
        font-size: 0.7rem; font-weight: 700; color: #f59e0b;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    html.dark-mode .leader-popup-modal { background: #1e293b; }
    html.dark-mode .leader-popup-img-wrap { background: linear-gradient(135deg, #334155, #1e293b); }
    @media (max-width: 575.98px) {
        .leader-popup-img-wrap { height: 260px; }
        .leader-popup-modal { max-width: 100%; border-radius: 20px 20px 0 0; }
        .leader-popup-overlay.active { align-items: flex-end; }
    }
</style>

<!-- ============================= -->
<!-- SECTION: PIMPINAN DAERAH KOTA TUAL -->
<!-- ============================= -->
<section class="py-5" id="pimpinan-daerah" style="background:linear-gradient(180deg,rgba(99,102,241,0.03),rgba(245,158,11,0.03))">
    <div class="container">
        <div class="text-center mb-4" data-animate>
            <div class="section-title justify-content-center">
                <span class="icon-circle" style="background:rgba(99,102,241,0.1);color:var(--primary)"><i class="bi bi-people-fill"></i></span>
                Pimpinan Pemerintah Kota Tual
            </div>
            <p class="text-muted mt-2 mb-0 mx-auto" style="font-size:0.85rem;max-width:620px">Maryadat Kota Tual berkomitmen mewujudkan ketahanan pangan yang berdaya saing dan berkelanjutan di bawah arahan pimpinan daerah.</p>
        </div>
        <div class="row g-4 justify-content-center" data-animate style="max-width:880px;margin:0 auto">
            <!-- WALI KOTA -->
            <div class="col-md-6">
                <div class="leader-card">
                    <div class="leader-photo-wrap">
                        <img src="{{ asset('wali_kota_tual.png') }}" alt="Hi. Akhmad Yani Renuat, S.Sos., M.Si., M.H" loading="lazy">
                        <div class="leader-photo-gradient"></div>
                        <span class="leader-role-badge"><i class="bi bi-award-fill"></i>Wali Kota</span>
                        <div class="leader-info">
                            <h5>Hi. Akhmad Yani Renuat, S.Sos., M.Si., M.H</h5>
                            <div class="leader-instansi"><i class="bi bi-building-check"></i>Pemerintah Kota Tual</div>
                            <button type="button" class="leader-detail-btn" data-leader="wali"><i class="bi bi-info-circle"></i>Lihat Profil</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- WAKIL WALI KOTA -->
            <div class="col-md-6">
                <div class="leader-card">
                    <div class="leader-photo-wrap">
                        <img src="{{ asset('wakil_wali_kota_tual.png') }}" alt="Hi. Amir Rumra, S.Pi., M.Si" loading="lazy">
                        <div class="leader-photo-gradient"></div>
                        <span class="leader-role-badge wakil"><i class="bi bi-person-badge-fill"></i>Wakil Wali Kota</span>
                        <div class="leader-info">
                            <h5>Hi. Amir Rumra, S.Pi., M.Si</h5>
                            <div class="leader-instansi"><i class="bi bi-building-check"></i>Pemerintah Kota Tual</div>
                            <button type="button" class="leader-detail-btn" data-leader="wakil"><i class="bi bi-info-circle"></i>Lihat Profil</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LEADER POPUP MODAL -->
<div class="leader-popup-overlay" id="leaderPopupOverlay">
    <div class="leader-popup-modal">
        <button class="leader-popup-close" id="leaderPopupClose" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
        <div class="leader-popup-img-wrap">
            <img id="leaderPopupImg" src="" alt="">
            <div class="leader-popup-img-gradient"></div>
        </div>
        <div class="leader-popup-content">
            <div class="text-center">
                <div class="mb-3">
                    <span class="leader-popup-badge" id="leaderPopupBadge"><i class="bi bi-award-fill"></i>Wali Kota</span>
                </div>
                <h5 class="fw-bold mb-1" id="leaderPopupName" style="font-size:1.15rem"></h5>
                <p class="text-muted mb-3" style="font-size:0.85rem" id="leaderPopupRole"></p>
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
                                <i class="bi bi-bank2" style="color:#f59e0b;font-size:0.85rem"></i>
                                <div>
                                    <div style="font-size:0.6rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;font-weight:600">Periode</div>
                                    <div class="fw-semibold" style="font-size:0.8rem">2025–2030</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2" style="border-radius:12px;background:rgba(14,165,233,0.06);border:1px solid rgba(14,165,233,0.1)">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-flag-fill" style="color:#0ea5e9;font-size:0.85rem"></i>
                                <div>
                                    <div style="font-size:0.6rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;font-weight:600">Visi</div>
                                    <div class="fw-semibold" style="font-size:0.8rem">Tual Maju & Sejahtera</div>
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
    if (window.__leadersInit) return;
    window.__leadersInit = true;

    var leaderData = {
        wali: {
            name: 'Hi. Akhmad Yani Renuat, S.Sos., M.Si., M.H',
            role: 'Wali Kota Tual',
            badge: 'Wali Kota',
            icon: 'award-fill',
            img: '{{ asset("wali_kota_tual.png") }}'
        },
        wakil: {
            name: 'Hi. Amir Rumra, S.Pi., M.Si',
            role: 'Wakil Wali Kota Tual',
            badge: 'Wakil Wali Kota',
            icon: 'person-badge-fill',
            img: '{{ asset("wakil_wali_kota_tual.png") }}'
        }
    };

    function openPopup(key) {
        var data = leaderData[key];
        if (!data) return;
        var overlay = document.getElementById('leaderPopupOverlay');
        if (!overlay) return;
        document.getElementById('leaderPopupImg').src = data.img;
        document.getElementById('leaderPopupImg').alt = data.name;
        document.getElementById('leaderPopupName').textContent = data.name;
        document.getElementById('leaderPopupRole').textContent = data.role;
        var badge = document.getElementById('leaderPopupBadge');
        badge.innerHTML = '<i class="bi bi-' + data.icon + '"></i>' + data.badge;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closePopup() {
        var overlay = document.getElementById('leaderPopupOverlay');
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Event delegation — works regardless of where buttons live in DOM
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.leader-detail-btn');
        if (btn) {
            e.preventDefault();
            openPopup(btn.getAttribute('data-leader'));
            return;
        }
        if (e.target.closest('.leader-popup-close')) { closePopup(); return; }
        if (e.target.classList && e.target.classList.contains('leader-popup-overlay')) { closePopup(); return; }
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePopup();
    });
})();
</script>
