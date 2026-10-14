{{-- ===== PAGE LOADER saat load & pindah halaman =====
     Revisi 14 Okt 2026: animasi logo berputar (cincin spinner) DIHILANGKAN — logo statis.
     Include di layout: @include('partials.page-loader')
     Skip per-link/form: tambahkan attribute data-no-loader --}}
<div id="pageLoader" class="pl-overlay" aria-hidden="true">
    <div class="pl-box">
        <div class="pl-logo-wrap">
            <img src="{{ asset('logo.png') }}" alt="" class="pl-logo" draggable="false">
        </div>
        <div class="pl-text"><span class="pl-text-label">Memuat</span><span class="pl-dots"><i>.</i><i>.</i><i>.</i></span></div>
        <div class="pl-bar"><span></span></div>
    </div>
</div>
<noscript><style>#pageLoader{display:none !important}</style></noscript>

<style>
    /* Default TERLIHAT saat halaman baru dirender; auto-hide CSS failsafe 4s tanpa JS */
    .pl-overlay {
        position: fixed; inset: 0; z-index: 99999;
        display: flex; align-items: center; justify-content: center;
        background: radial-gradient(1200px 600px at 50% 40%, rgba(30,27,75,0.92), rgba(2,6,23,0.96));
        -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
        opacity: 1; visibility: visible;
        transition: opacity .3s ease, visibility .3s ease;
        animation: plAutoHide .35s ease 4s forwards;
    }
    .pl-overlay.pl-hide { opacity: 0; visibility: hidden; animation: none; }
    @keyframes plAutoHide { to { opacity: 0; visibility: hidden; } }

    .pl-box { display: flex; flex-direction: column; align-items: center; gap: 18px; padding: 24px; }

    /* Logo statis — tanpa animasi berputar (revisi 14 Okt 2026) */
    .pl-logo-wrap { position: relative; width: 92px; height: 92px; display: flex; align-items: center; justify-content: center; }
    .pl-logo {
        width: 60px; height: 60px; border-radius: 14px; object-fit: cover;
        box-shadow: 0 8px 30px rgba(99,102,241,0.45);
    }

    /* Teks */
    .pl-text {
        color: #fff; font-weight: 700; font-size: 0.95rem; letter-spacing: 1.5px; text-transform: uppercase;
        text-shadow: 0 2px 12px rgba(99,102,241,0.5);
    }
    .pl-dots i { font-style: normal; opacity: 0; animation: plDot 1.2s infinite; }
    .pl-dots i:nth-child(2) { animation-delay: .2s; }
    .pl-dots i:nth-child(3) { animation-delay: .4s; }
    @keyframes plDot { 0%,60%,100% { opacity: 0; } 30% { opacity: 1; } }

    /* Progress bar tipis */
    .pl-bar { width: 180px; height: 4px; border-radius: 99px; background: rgba(255,255,255,0.12); overflow: hidden; }
    .pl-bar span {
        display: block; height: 100%; width: 40%; border-radius: 99px;
        background: linear-gradient(90deg, #6366f1, #0ea5e9, #f59e0b);
        animation: plSlide 1.2s ease-in-out infinite;
    }
    @keyframes plSlide { 0% { transform: translateX(-120%); } 100% { transform: translateX(420%); } }

    @media (prefers-reduced-motion: reduce) {
        .pl-bar span, .pl-dots i { animation-duration: 2.5s; }
    }
    @media (max-width: 767.98px) {
        .pl-logo-wrap { width: 78px; height: 78px; }
        .pl-logo { width: 46px; height: 46px; }
        .pl-text { font-size: 0.8rem; letter-spacing: 1px; }
        .pl-bar { width: 140px; }
    }
</style>

<script>
(function () {
    var overlay = document.getElementById('pageLoader');
    if (!overlay) return;
    var label = overlay.querySelector('.pl-text-label');

    function show(text) {
        if (label && text) label.textContent = text;
        overlay.classList.remove('pl-hide');
    }
    function hide() { overlay.classList.add('pl-hide'); }
    window.plShowLoader = show;
    window.plHideLoader = hide;

    // Sembunyikan setelah halaman selesai dimuat (failsafe 2.5s utk aset CDN lambat)
    function hideWhenReady() { setTimeout(hide, 120); }
    if (document.readyState === 'complete') hideWhenReady();
    else window.addEventListener('load', hideWhenReady);
    setTimeout(hide, 2500);

    // Bfcache (tombol back browser)
    window.addEventListener('pageshow', function (e) { if (e.persisted) hide(); });

    // Tampilkan saat pindah halaman: klik link internal
    document.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        var a = e.target && e.target.closest ? e.target.closest('a[href]') : null;
        if (!a) return;
        if (a.target === '_blank' || a.hasAttribute('download') || a.hasAttribute('data-no-loader')) return;
        var href = a.getAttribute('href') || '';
        if (href.charAt(0) === '#' || href.indexOf('javascript:') === 0 ||
            href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0 || href.indexOf('wa.me') !== -1) return;
        try {
            var url = new URL(a.href, window.location.href);
            if (url.host !== window.location.host) return; // link eksternal
        } catch (_) { return; }
        show('Memuat');
    }, true);

    // Tampilkan saat submit form (login, filter, simpan data, dsb.)
    document.addEventListener('submit', function (e) {
        var f = e.target;
        if (!f || f.hasAttribute('data-no-loader')) return;
        show(f.getAttribute('data-loader-text') || 'Memproses');
    }, true);
})();
</script>
