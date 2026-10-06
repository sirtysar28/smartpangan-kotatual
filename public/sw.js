/* ============================================================
   PANCA — Pangan Cerdas Maryadat Kota Tual
   Service Worker (PWA): precache app shell + runtime caching
   ============================================================ */
const CACHE_VERSION = 'panca-tual-v1';
const OFFLINE_URL = '/offline.html';

/* App shell yang di-cache saat install */
const PRECACHE_ASSETS = [
    '/',
    OFFLINE_URL,
    '/logo.png',
    '/favicon.png',
    '/manifest.json',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css',
    'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap'
];

/* Install: precache app shell */
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_VERSION)
            .then((cache) => cache.addAll(PRECACHE_ASSETS.map(u => new Request(u, { cache: 'reload' }))).catch(() => null))
            .then(() => self.skipWaiting())
    );
});

/* Activate: hapus cache lama */
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter(k => k !== CACHE_VERSION).map(k => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

/* Strategy:
   - Navigasi (HTML)  : network-first, fallback cache, fallback offline.html
   - Asset statis      : cache-first (font/gambar/css/js CDN)
   - POST / API        : selalu network (tanpa cache)
*/
self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') return;

    const url = new URL(request.url);

    // Jangan cache panel admin/umkm/login (data dinamis) — tetap network-first
    const isHtmlNavigation = request.mode === 'navigate' || (request.headers.get('accept') || '').includes('text/html');

    if (isHtmlNavigation) {
        if (url.pathname.startsWith('/admin') || url.pathname.startsWith('/umkm') || url.pathname === '/login') {
            // Halaman privat: network-only dengan fallback offline
            event.respondWith(
                fetch(request).catch(() => caches.match(OFFLINE_URL))
            );
            return;
        }

        event.respondWith(
            fetch(request)
                .then((response) => {
                    const copy = response.clone();
                    caches.open(CACHE_VERSION).then((cache) => cache.put(request, copy)).catch(() => null);
                    return response;
                })
                .catch(() => caches.match(request).then((cached) => cached || caches.match(OFFLINE_URL)))
        );
        return;
    }

    // Asset statis: cache-first
    if (['https://cdn.jsdelivr.net', 'https://fonts.googleapis.com', 'https://fonts.gstatic.com'].some(h => url.origin === h) ||
        /\.(png|jpe?g|gif|webp|svg|ico|css|js|woff2?)$/.test(url.pathname)) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;
                return fetch(request).then((response) => {
                    if (response.ok || response.type === 'opaque') {
                        const copy = response.clone();
                        caches.open(CACHE_VERSION).then((cache) => cache.put(request, copy)).catch(() => null);
                    }
                    return response;
                }).catch(() => cached);
            })
        );
    }
});

/* Pesan dari klien: skip waiting saat update */
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') self.skipWaiting();
});
