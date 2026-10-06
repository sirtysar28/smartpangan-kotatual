{{-- ===== NOTIFIKASI ADMIN: harga, perizinan, keamanan pangan ===== --}}
@php
    $notifId = $notifId ?? 'notifDesktop';

    // Cache per-request: partial di-include 2x (desktop & mobile)
    $notifItems = app()->bound('panca.notif.items') ? app('panca.notif.items') : null;

    if ($notifItems === null) {
        try {
            $notifPrices = \App\Models\CommodityPrice::with('commodity')
                ->whereHas('commodity', fn($q) => $q->where('is_active', true))
                ->where('change_percent', '!=', 0)
                ->where('price_date', '>=', now()->subDays(3))
                ->orderByDesc('price_date')
                ->get()
                ->groupBy(fn($p) => $p->commodity_id)
                ->take(5);

            $notifPending = \App\Models\Umkm::with('user')->where('status', 'pending')->latest()->take(3)->get();
            $notifRapidPos = \App\Models\RapidTest::where('result', 'positif')->latest('test_date')->take(2)->get();
        } catch (\Throwable $e) {
            $notifPrices = collect(); $notifPending = collect(); $notifRapidPos = collect();
        }

        $notifItems = collect();

    foreach ($notifPrices as $price) {
        $p = $price->first();
        if (!$p || !$p->commodity) continue;
        $chg = (float) $p->change_percent;
        $notifItems->push([
            'icon' => $chg > 0 ? 'bi-arrow-up-circle-fill' : 'bi-arrow-down-circle-fill',
            'color' => $chg > 0 ? '#ef4444' : '#10b981',
            'title' => $p->commodity->name . ' ' . ($chg > 0 ? 'naik' : 'turun') . ' ' . abs(number_format($chg, 1)) . '%',
            'desc' => 'Rp ' . number_format($p->price, 0, ',', '.') . ' / ' . ($p->commodity->unit ?? '-') . ' • ' . optional($p->price_date)->translatedFormat('d M'),
            'url' => route('admin.commodities.index'),
        ]);
    }
    foreach ($notifPending as $um) {
        $notifItems->push([
            'icon' => 'bi-hourglass-split',
            'color' => '#f59e0b',
            'title' => 'Perizinan menunggu: ' . $um->business_name,
            'desc' => ($um->user?->name ?? '-') . ' • ' . optional($um->created_at)->translatedFormat('d M'),
            'url' => route('admin.permits.index'),
        ]);
    }
    foreach ($notifRapidPos as $rt) {
        $notifItems->push([
            'icon' => 'bi-shield-fill-x',
            'color' => '#a855f7',
            'title' => 'Rapid Test POSITIF: ' . $rt->commodity_name,
            'desc' => ($rt->type_label ?? 'Uji') . ' • ' . optional($rt->test_date)->translatedFormat('d M'),
            'url' => route('admin.food-safety.index', ['tab' => 'rapid']),
        ]);
    }

        app()->instance('panca.notif.items', $notifItems);
    }
    $notifCount = $notifItems->count();
@endphp

<div class="dropdown">
    <button class="btn position-relative" data-bs-toggle="dropdown" data-bs-auto-close="outside"
            aria-expanded="false" title="Notifikasi"
            style="background:var(--bg-card);border:1px solid rgba(0,0,0,0.06);border-radius:12px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;box-shadow:var(--shadow-sm)">
        <i class="bi bi-bell" style="font-size:1.05rem;color:var(--text-main)"></i>
        @if($notifCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill {{ $notifId }}-pulse"
              style="background:#ef4444;font-size:0.6rem;font-weight:700;padding:0.32em 0.55em;border:2px solid var(--bg-card)">
            {{ $notifCount > 99 ? '99+' : $notifCount }}
        </span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-end shadow"
         style="border:none;border-radius:14px;width:330px;max-width:calc(100vw - 24px);padding:0.5rem;background:var(--bg-card)">
        <div class="d-flex align-items-center justify-content-between px-2 py-1 mb-1">
            <span class="fw-bold" style="font-size:0.85rem;color:var(--text-main)">Notifikasi</span>
            @if($notifCount > 0)
            <span class="badge rounded-pill" style="background:rgba(239,68,68,0.12);color:#ef4444;font-size:0.65rem">{{ $notifCount }} baru</span>
            @endif
        </div>
        <div style="max-height:320px;overflow-y:auto">
            @forelse($notifItems as $item)
            <a href="{{ $item['url'] }}" class="dropdown-item d-flex gap-2 align-items-start rounded-3"
               style="padding:0.6rem 0.7rem;margin-bottom:2px">
                <span class="d-flex align-items-center justify-content-center flex-shrink-0"
                      style="width:34px;height:34px;border-radius:10px;background:{{ $item['color'] }}1A;color:{{ $item['color'] }}">
                    <i class="bi {{ $item['icon'] }}" style="font-size:0.9rem"></i>
                </span>
                <span class="flex-grow-1 min-w-0">
                    <span class="d-block fw-semibold text-truncate" style="font-size:0.78rem;color:var(--text-main)">{{ $item['title'] }}</span>
                    <span class="d-block text-truncate" style="font-size:0.68rem;color:var(--text-muted)">{{ $item['desc'] }}</span>
                </span>
            </a>
            @empty
            <div class="text-center py-4">
                <i class="bi bi-bell-slash" style="font-size:1.6rem;color:var(--gray-light);opacity:.5"></i>
                <p class="mb-0 mt-2" style="font-size:0.75rem;color:var(--text-muted)">Belum ada notifikasi baru</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    @keyframes notifPulse {
        0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239,68,68,0.5); }
        50% { transform: scale(1.12); box-shadow: 0 0 0 6px rgba(239,68,68,0); }
    }
    .{{ $notifId }}-pulse { animation: notifPulse 2s ease-in-out infinite; }
    html.dark-mode .dropdown-item:hover { background: rgba(99,102,241,0.1); }
</style>
