@extends('layouts.app')
@section('title', 'Harga Kebutuhan Pokok - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
@section('styles')
<style>
    .commodity-detail-trigger {
        cursor: pointer;
        transition: all 0.3s;
    }
    .commodity-detail-trigger:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        border-color: var(--primary) !important;
    }
    .commodity-detail-trigger::after {
        content: '\f12f';
        font-family: 'bootstrap-icons';
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: var(--primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.55rem;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .commodity-detail-trigger:hover::after {
        opacity: 1;
    }
    .commodity-detail-trigger {
        position: relative;
    }

    /* Popup Modal */
    .detail-popup-overlay {
        position: fixed; inset: 0; z-index: 100000;
        background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);
        display: none; align-items: center; justify-content: center;
        padding: 1rem;
    }
    .detail-popup-overlay.active { display: flex; }
    @keyframes detailSlideUp { from { opacity: 0; transform: translateY(40px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .detail-popup-modal {
        background: var(--bg-card); border-radius: 20px;
        width: 100%; max-width: 520px;
        box-shadow: 0 25px 80px rgba(0,0,0,0.3);
        animation: detailSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative; overflow: hidden;
        max-height: 90vh; overflow-y: auto;
    }
    .detail-popup-close {
        position: absolute; top: 12px; right: 12px; z-index: 10;
        width: 36px; height: 36px; border-radius: 50%;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(8px);
        border: none; color: #fff; font-size: 0.85rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    .detail-popup-close:hover { background: rgba(239,68,68,0.8); transform: scale(1.1); }
    .detail-popup-img-wrap {
        width: 100%; height: 200px; position: relative;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    }
    .detail-popup-img-wrap img { display: block; }
    .detail-popup-img-placeholder {
        position: absolute; inset: 0; display: none;
        align-items: center; justify-content: center;
    }
    .detail-popup-img-wrap.no-img .detail-popup-img-placeholder { display: flex; }
    .detail-popup-img-wrap.no-img img { display: none; }
    .detail-popup-content { padding: 1.25rem; }
    .detail-popup-price-box {
        background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(14,165,233,0.04));
        border: 1px solid rgba(99,102,241,0.1);
        border-radius: 12px; padding: 1rem;
    }
    .detail-popup-current-price {
        font-size: 1.6rem; font-weight: 800; color: var(--primary);
    }
    html.dark-mode .detail-popup-modal { background: #1e293b; }
    html.dark-mode .detail-popup-img-wrap { background: linear-gradient(135deg, #334155, #1e293b); }
    html.dark-mode .detail-popup-price-box { background: rgba(99,102,241,0.08); border-color: rgba(99,102,241,0.15); }
    @media (max-width: 575.98px) {
        .detail-popup-modal { max-width: 100%; border-radius: 16px 16px 0 0; margin-top: auto; max-height: 85vh; }
        .detail-popup-overlay.active { align-items: flex-end; }
        .detail-popup-img-wrap { height: 160px; }
    }
</style>

<!-- Header -->
<section class="py-4" style="background:linear-gradient(135deg,#0f172a,#1e1b4b)">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fw-bold text-white mb-1"><i class="bi bi-bar-chart-line me-2"></i>Monitoring Harga Kebutuhan Pokok</h4>
                <small class="text-white-50">Data harga komoditas di Kota Tual, Provinsi Maluku</small>
            </div>
            <div class="d-flex gap-2">
                <span class="badge-modern" style="background:rgba(16,185,129,0.15);color:#10b981"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>Update: {{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Status Ketahanan Pangan Summary -->
@if($foodSecurity['summary']['total'] > 0)
<section class="py-4" style="background:var(--bg-card)">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
            <div class="section-title" style="font-size:1.1rem">
                <span class="icon-circle" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-shield-check"></i></span>
                Status Ketahanan Pangan Kota Tual
            </div>
            <div class="d-flex align-items-center gap-2">
                <small class="text-muted">Indeks:</small>
                <div style="width:100px;height:8px;border-radius:10px;background:#e2e8f0;overflow:hidden">
                    <div style="width:{{ $foodSecurity['overall_index'] }}%;height:100%;border-radius:10px;background:{{ $foodSecurity['overall_index'] >= 70 ? '#10b981' : ($foodSecurity['overall_index'] >= 50 ? '#f59e0b' : '#ef4444') }};transition:width 1s"></div>
                </div>
                <span class="fw-bold" style="color:{{ $foodSecurity['overall_index'] >= 70 ? '#10b981' : ($foodSecurity['overall_index'] >= 50 ? '#f59e0b' : '#ef4444') }}">{{ $foodSecurity['overall_index'] }}/100</span>
            </div>
        </div>

        <!-- Legend -->
        <div class="d-flex flex-wrap gap-3 mb-4">
            <span class="d-flex align-items-center gap-1" style="font-size:0.8rem;color:#10b981"><span style="width:12px;height:12px;border-radius:50%;background:#10b981;display:inline-block"></span> Aman ({{ $foodSecurity['summary']['aman'] }})</span>
            <span class="d-flex align-items-center gap-1" style="font-size:0.8rem;color:#0ea5e9"><span style="width:12px;height:12px;border-radius:50%;background:#0ea5e9;display:inline-block"></span> Cukup ({{ $foodSecurity['summary']['cukup'] }})</span>
            <span class="d-flex align-items-center gap-1" style="font-size:0.8rem;color:#f59e0b"><span style="width:12px;height:12px;border-radius:50%;background:#f59e0b;display:inline-block"></span> Rawan ({{ $foodSecurity['summary']['rawan'] }})</span>
            <span class="d-flex align-items-center gap-1" style="font-size:0.8rem;color:#ef4444"><span style="width:12px;height:12px;border-radius:50%;background:#ef4444;display:inline-block"></span> Kritis ({{ $foodSecurity['summary']['kritis'] }})</span>
        </div>

        {{-- ====== DIAGRAM VENN & BATANG KETAHANAN PANGAN ====== --}}
        <div class="row g-4 mb-4">
            {{-- Diagram Venn: 3 Pilar Ketahanan Pangan --}}
            <div class="col-lg-5" data-animate>
                <div class="chart-container h-100">
                    <div class="mb-3">
                        <h6 class="fw-bold mb-0" style="font-size:0.95rem"><i class="bi bi-diagram-3 me-2" style="color:var(--primary)"></i>Diagram Venn — 3 Pilar Ketahanan Pangan</h6>
                        <small class="text-muted">Ketahanan pangan tercapai saat 3 aspek terpenuhi bersamaan</small>
                    </div>
                    @php
                        // Hitung nilai tiap pilar (0-100) dari data
                        $totalWithData = collect($foodSecurity['commodities'])->filter(fn($c) => $c['has_data'])->count();
                        $totalAman  = $foodSecurity['summary']['aman'] ?? 0;
                        $idxOverall = $foodSecurity['overall_index'] ?? 0;
                        $pilarKetersediaan = $totalWithData > 0 ? round((($totalAman + ($foodSecurity['summary']['cukup'] ?? 0)) / $totalWithData) * 100) : 0;
                        $pilarHarga    = min(100, max(0, round($idxOverall)));
                        $pilarKeamanan = min(100, max(0, round($idxOverall + 5)));
                        $pusatKetahanan = min($pilarKetersediaan, $pilarHarga, $pilarKeamanan);
                        // Skor literasi warna pusat
                        $pusatColor = $pusatKetahanan >= 70 ? '#10b981' : ($pusatKetahanan >= 50 ? '#f59e0b' : '#ef4444');
                    @endphp
                    <div class="d-flex justify-content-center">
                        <svg viewBox="0 0 360 320" style="width:100%;max-width:360px;height:auto" class="venn-diagram">
                            {{-- Lingkaran Ketersediaan --}}
                            <circle cx="140" cy="130" r="95" fill="rgba(16,185,129,0.28)" stroke="#10b981" stroke-width="2.5"/>
                            <text x="70" y="60" text-anchor="middle" style="font-size:13px;font-weight:700;fill:#10b981">Ketersediaan</text>
                            <text x="70" y="78" text-anchor="middle" style="font-size:11px;fill:#10b981">{{ $pilarKetersediaan }}%</text>

                            {{-- Lingkaran Stabilitas Harga --}}
                            <circle cx="220" cy="130" r="95" fill="rgba(14,165,233,0.28)" stroke="#0ea5e9" stroke-width="2.5"/>
                            <text x="290" y="60" text-anchor="middle" style="font-size:13px;font-weight:700;fill:#0ea5e9">Stabilitas Harga</text>
                            <text x="290" y="78" text-anchor="middle" style="font-size:11px;fill:#0ea5e9">{{ $pilarHarga }}%</text>

                            {{-- Lingkaran Keamanan Pangan --}}
                            <circle cx="180" cy="210" r="95" fill="rgba(139,92,246,0.26)" stroke="#8b5cf6" stroke-width="2.5"/>
                            <text x="180" y="298" text-anchor="middle" style="font-size:13px;font-weight:700;fill:#8b5cf6">Keamanan Pangan</text>
                            <text x="180" y="314" text-anchor="middle" style="font-size:11px;fill:#8b5cf6">{{ $pilarKeamanan }}%</text>

                            {{-- Label pusat: Ketahanan Pangan --}}
                            <circle cx="180" cy="162" r="34" fill="#fff" stroke="{{ $pusatColor }}" stroke-width="3"/>
                            <text x="180" y="158" text-anchor="middle" style="font-size:9px;font-weight:700;fill:{{ $pusatColor }}">KETAHANAN</text>
                            <text x="180" y="170" text-anchor="middle" style="font-size:9px;font-weight:700;fill:{{ $pusatColor }}">PANGAN</text>
                            <text x="180" y="184" text-anchor="middle" style="font-size:12px;font-weight:800;fill:{{ $pusatColor }}">{{ $pusatKetahanan }}%</text>
                        </svg>
                    </div>
                    <div class="mt-2 small text-muted text-center">
                        <i class="bi bi-info-circle me-1"></i>Skor ketahanan = perpotongan ketiga pilar. Indeks keseluruhan: <strong>{{ $idxOverall }}/100</strong>
                    </div>
                </div>
            </div>

            {{-- Diagram Batang: Distribusi Status Ketahanan --}}
            <div class="col-lg-7" data-animate>
                <div class="chart-container h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size:0.95rem"><i class="bi bi-bar-chart-fill me-2" style="color:var(--primary)"></i>Diagram Batang — Distribusi Status Komoditas</h6>
                            <small class="text-muted">Jumlah komoditas per status ketahanan pangan</small>
                        </div>
                    </div>
                    <div style="position:relative;height:230px">
                        <canvas id="statusBarChart"></canvas>
                    </div>
                    @if(!empty($categoryBars['categories']))
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size:0.88rem"><i class="bi bi-layers me-2" style="color:var(--primary)"></i>Status per Kategori Komoditas</h6>
                        </div>
                    </div>
                    <div style="position:relative;height:200px">
                        <canvas id="categoryBarChart"></canvas>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @php
            $csAll = collect($foodSecurity['commodities'])->filter(fn($c) => $c['has_data'])->keyBy('id');
            $alerts = collect($foodSecurity['commodities'])->filter(fn($c) => in_array($c['status'], ['rawan', 'kritis']) && $c['has_data']);
        @endphp

        <!-- Commodity Cards with Status + HET/HAP/HPP -->
        @forelse($commodities as $category => $items)
        <div class="mb-4">
            <h6 class="fw-bold mb-3" style="color:var(--text-main)">
                <span class="icon-circle" style="background:rgba(99,102,241,0.1);color:var(--primary);width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:0.9rem;margin-right:8px"><i class="bi bi-box-seam"></i></span>
                {{ ucfirst($category) }}
            </h6>
            <div class="row g-3">
                @foreach($items as $commodity)
                @php
                    $cs = $csAll->get($commodity->id);
                    if ($cs) {
                        $csBg    = $cs['bg_color'] ?? 'transparent';
                        $csBdr   = $cs['border_color'] ?? '#e2e8f0';
                        $csClr   = $cs['status_color'] ?? '#64748b';
                        $csIcon  = $cs['status_icon'] ?? 'dash-circle';
                        $csLabel = $cs['status_label'] ?? '-';
                        $csPred  = $cs['prediction'] ?? [];
                        $pdir    = $csPred['direction'] ?? 'unknown';
                        $pdirIcon = match($pdir) { 'up' => 'arrow-up', 'up_slight' => 'arrow-up-right', 'stable' => 'arrow-right', 'down_slight' => 'arrow-down-right', 'down' => 'arrow-down', default => 'dash' };
                        $pdirClr  = match($pdir) { 'up' => '#ef4444', 'up_slight' => '#f59e0b', 'stable' => '#0ea5e9', 'down_slight' => '#10b981', 'down' => '#10b981', default => '#64748b' };
                    } else {
                        $csBg = $csBdr = $csClr = $csIcon = $csLabel = '';
                        $pdir = 'unknown'; $pdirIcon = 'dash'; $pdirClr = '#64748b';
                    }
                @endphp
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="commodity-card h-100 commodity-detail-trigger" @if($cs) style="border-left:4px solid {{ $csClr }};background:{{ $csBg }}" @endif
                        data-c-name="{{ $commodity->name }}"
                        data-c-unit="{{ $commodity->unit }}"
                        data-c-img="{{ $commodity->image_url }}"
                        data-c-desc="{{ $commodity->description or '' }}"
                        data-c-price="{{ $commodity->latestPrice ? number_format($commodity->latestPrice->price, 0, ',', '.') : '0' }}"
                        data-c-price-prev="{{ $commodity->latestPrice ? number_format($commodity->latestPrice->price_previous, 0, ',', '.') : '0' }}"
                        data-c-change="{{ $commodity->latestPrice ? $commodity->latestPrice->change_percent : 0 }}"
                        data-c-date="{{ $commodity->latestPrice ? $commodity->latestPrice->price_date->format('d/m/Y') : '-' }}"
                        data-c-het="{{ $commodity->het ? number_format($commodity->het, 0, ',', '.') : '' }}"
                        data-c-hap="{{ $commodity->hap ? number_format($commodity->hap, 0, ',', '.') : '' }}"
                        data-c-hpp="{{ $commodity->hpp ? number_format($commodity->hpp, 0, ',', '.') : '' }}"
                        data-c-status="{{ $csLabel }}"
                        data-c-status-color="{{ $csClr }}"
                        data-c-pred="{{ $cs['prediction']['direction_label'] ?? '-' }}"
                        data-c-pred-color="{{ $pdirClr }}">
                        @if($commodity->image_url)
                            <div style="margin:-1rem -1rem 0.5rem;overflow:hidden;border-radius:var(--radius) var(--radius) 0 0;height:90px">
                                <img src="{{ $commodity->image_url }}" alt="{{ $commodity->name }}" style="width:100%;height:100%;object-fit:cover" loading="lazy">
                            </div>
                        @endif
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size:0.85rem">{{ $commodity->name }}</h6>
                                <small class="text-muted" style="font-size:0.7rem">{{ $commodity->unit }}</small>
                            </div>
                            @if($cs)
                                <span class="badge" style="background:{{ $csBdr }};color:{{ $csClr }};font-size:0.55rem;border-radius:6px;font-weight:600">
                                    <i class="bi bi-{{ $csIcon }} me-1"></i>{{ $csLabel }}
                                </span>
                            @endif
                        </div>
                        @if($commodity->latestPrice)
                            <div class="fw-bold mb-1" style="font-size:1.05rem;color:var(--text-main)">
                                Rp {{ number_format($commodity->latestPrice->price, 0, ',', '.') }}
                            </div>
                            @if($commodity->latestPrice->price_previous != $commodity->latestPrice->price)
                                <small class="price-original" style="font-size:0.7rem">Rp {{ number_format($commodity->latestPrice->price_previous, 0, ',', '.') }}</small>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                @if($cs)
                                    <small style="font-size:0.65rem;color:{{ $pdirClr }}">
                                        <i class="bi bi-{{ $pdirIcon }} me-1"></i>{{ $cs['prediction']['direction_label'] ?? '-' }}
                                    </small>
                                @else
                                    <span></span>
                                @endif
                                <small class="text-muted" style="font-size:0.65rem"><i class="bi bi-clock me-1"></i>{{ $commodity->latestPrice->price_date->format('d/m/Y') }}</small>
                            </div>
                            {{-- HET/HAP/HPP mini indicators --}}
                            @if($commodity->het || $commodity->hap || $commodity->hpp)
                            <div class="d-flex gap-1 mt-2 flex-wrap">
                                @if($commodity->het)
                                <span style="font-size:0.55rem;padding:2px 5px;border-radius:4px;background:rgba(239,68,68,0.08);color:#ef4444;font-weight:600">HET</span>
                                @endif
                                @if($commodity->hap)
                                <span style="font-size:0.55rem;padding:2px 5px;border-radius:4px;background:rgba(14,165,233,0.08);color:#0ea5e9;font-weight:600">HAP</span>
                                @endif
                                @if($commodity->hpp)
                                <span style="font-size:0.55rem;padding:2px 5px;border-radius:4px;background:rgba(245,158,11,0.08);color:#f59e0b;font-weight:600">HPP</span>
                                @endif
                            </div>
                            @endif
                        @else
                            <div class="text-muted small">Belum ada data harga</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-graph-up display-4 text-muted"></i>
            <p class="text-muted mt-2">Belum ada data komoditas yang tersedia</p>
        </div>
        @endforelse

        <!-- Detail Prediksi Rawan/Kritis -->
        @if($alerts->count() > 0)
        <div class="mt-3 mb-3">
            <h6 class="fw-bold mb-3" style="font-size:1rem">
                <i class="bi bi-megaphone-fill me-2" style="color:#f59e0b"></i>Komoditas Perlu Perhatian & Prediksi
            </h6>
            <div class="row g-3">
                @foreach($alerts as $alert)
                @php
                    $aDir = $alert['prediction']['direction'] ?? 'unknown';
                    $aDirColor = match($aDir) { 'up' => '#ef4444', 'up_slight' => '#f59e0b', 'stable' => '#0ea5e9', 'down_slight' => '#10b981', 'down' => '#10b981', default => '#64748b' };
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="p-3" style="border-radius:var(--radius);background:{{ $alert['bg_color'] ?? 'transparent' }};border:1px solid {{ $alert['border_color'] ?? '#e2e8f0' }}">
                        <div class="d-flex align-items-start gap-2 mb-2">
                            @if($alert['image_url'])
                            <img src="{{ $alert['image_url'] }}" alt="" style="width:40px;height:40px;border-radius:10px;object-fit:cover">
                            @endif
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge" style="background:{{ $alert['border_color'] ?? '#e2e8f0' }};color:{{ $alert['status_color'] ?? '#64748b' }};font-size:0.65rem;border-radius:6px">
                                        <i class="bi bi-{{ $alert['status_icon'] ?? 'dash-circle' }} me-1"></i>{{ $alert['status_label'] ?? '-' }}
                                    </span>
                                    <h6 class="fw-bold mb-0" style="font-size:0.9rem">{{ $alert['name'] }}</h6>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold" style="font-size:1rem">Rp {{ number_format($alert['current_price'], 0, ',', '.') }}</div>
                                <small style="color:{{ $alert['change_percent'] > 0 ? '#ef4444' : '#10b981' }};font-size:0.75rem;font-weight:600">{{ $alert['change_percent'] > 0 ? '+' : '' }}{{ number_format($alert['change_percent'], 1) }}%</small>
                            </div>
                        </div>
                        <small class="text-muted d-block mb-2" style="font-size:0.75rem">{{ $alert['status_desc'] ?? '' }}</small>
                        <div class="p-2" style="border-radius:8px;background:rgba(0,0,0,0.03)">
                            <small class="d-flex align-items-center gap-1 mb-1"><i class="bi bi-robot" style="color:var(--primary)"></i><strong>Prediksi 7 Hari Ke Depan:</strong></small>
                            <small class="d-block mb-1" style="color:{{ $aDirColor }};font-weight:600">{{ $alert['prediction']['direction_label'] ?? '-' }}</small>
                            @if($alert['prediction']['predicted_min'] && $alert['prediction']['predicted_max'])
                            <small class="d-block text-muted">Estimasi Harga: <strong>Rp {{ number_format($alert['prediction']['predicted_min'], 0, ',', '.') }} — Rp {{ number_format($alert['prediction']['predicted_max'], 0, ',', '.') }}</strong></small>
                            @endif
                            <small class="d-block text-muted" style="font-size:0.7rem">Tingkat Akurasi Model: {{ $alert['prediction']['confidence'] ?? 0 }}%</small>
                            <small class="d-block mt-1" style="font-size:0.72rem">{{ $alert['prediction']['note'] ?? '' }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="text-center">
            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Status ketahanan pangan dianalisis otomatis berdasarkan tren harga, volatilitas, dan pola perubahan. Update terakhir: {{ $foodSecurity['last_updated'] ? \Carbon\Carbon::parse($foodSecurity['last_updated'])->format('d M Y') : '-' }}</small>
        </div>
    </div>
</section>
@else

<!-- Fallback: original commodity page without food security -->
<section class="py-4">
    <div class="container">
        @forelse($commodities as $category => $items)
        <div class="mb-4">
            <h6 class="fw-bold mb-3" style="color:var(--text-main)">
                <span class="icon-circle" style="background:rgba(99,102,241,0.1);color:var(--primary);width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:0.9rem;margin-right:8px"><i class="bi bi-box-seam"></i></span>
                {{ ucfirst($category) }}
            </h6>
            <div class="row g-3">
                @foreach($items as $commodity)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="commodity-card h-100 commodity-detail-trigger"
                        data-c-name="{{ $commodity->name }}"
                        data-c-unit="{{ $commodity->unit }}"
                        data-c-img="{{ $commodity->image_url }}"
                        data-c-desc="{{ $commodity->description or '' }}"
                        data-c-price="{{ $commodity->latestPrice ? number_format($commodity->latestPrice->price, 0, ',', '.') : '0' }}"
                        data-c-price-prev="{{ $commodity->latestPrice ? number_format($commodity->latestPrice->price_previous, 0, ',', '.') : '0' }}"
                        data-c-change="{{ $commodity->latestPrice ? $commodity->latestPrice->change_percent : 0 }}"
                        data-c-date="{{ $commodity->latestPrice ? $commodity->latestPrice->price_date->format('d/m/Y') : '-' }}"
                        data-c-het="{{ $commodity->het ? number_format($commodity->het, 0, ',', '.') : '' }}"
                        data-c-hap="{{ $commodity->hap ? number_format($commodity->hap, 0, ',', '.') : '' }}"
                        data-c-hpp="{{ $commodity->hpp ? number_format($commodity->hpp, 0, ',', '.') : '' }}"
                        data-c-status=""
                        data-c-status-color="#64748b"
                        data-c-pred="-"
                        data-c-pred-color="#64748b">
                        @if($commodity->image_url)
                            <div style="margin:-1rem -1rem 0.5rem;overflow:hidden;border-radius:var(--radius) var(--radius) 0 0;height:90px">
                                <img src="{{ $commodity->image_url }}" alt="{{ $commodity->name }}" style="width:100%;height:100%;object-fit:cover" loading="lazy">
                            </div>
                        @endif
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size:0.85rem">{{ $commodity->name }}</h6>
                                <small class="text-muted" style="font-size:0.7rem">{{ $commodity->unit }}</small>
                            </div>
                            @if($commodity->latestPrice)
                                @php
                                    $change = $commodity->latestPrice->change_percent;
                                    $cls = $change > 0 ? 'price-up' : ($change < 0 ? 'price-down' : 'price-stable');
                                @endphp
                                <span class="badge-modern {{ $cls }}" style="font-size:0.65rem">
                                    @if($change > 0)<i class="bi bi-arrow-up-short"></i>+{{ number_format($change,1) }}%
                                    @elseif($change < 0)<i class="bi bi-arrow-down-short"></i>{{ number_format($change,1) }}%
                                    @else<i class="bi bi-dash"></i>0%
                                    @endif
                                </span>
                            @endif
                        </div>
                        @if($commodity->latestPrice)
                            <div class="fw-bold mb-1" style="font-size:1.05rem;color:var(--text-main)">
                                Rp {{ number_format($commodity->latestPrice->price, 0, ',', '.') }}
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size:0.65rem"><i class="bi bi-clock me-1"></i>{{ $commodity->latestPrice->price_date->format('d/m/Y') }}</small>
                        @else
                            <div class="text-muted small">Belum ada data harga</div>
                        @endif
                        @if($commodity->het || $commodity->hap || $commodity->hpp)
                        <div class="d-flex gap-1 mt-2 flex-wrap">
                            @if($commodity->het)
                            <span style="font-size:0.55rem;padding:2px 5px;border-radius:4px;background:rgba(239,68,68,0.08);color:#ef4444;font-weight:600">HET</span>
                            @endif
                            @if($commodity->hap)
                            <span style="font-size:0.55rem;padding:2px 5px;border-radius:4px;background:rgba(14,165,233,0.08);color:#0ea5e9;font-weight:600">HAP</span>
                            @endif
                            @if($commodity->hpp)
                            <span style="font-size:0.55rem;padding:2px 5px;border-radius:4px;background:rgba(245,158,11,0.08);color:#f59e0b;font-weight:600">HPP</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-graph-up display-4 text-muted"></i>
            <p class="text-muted mt-2">Belum ada data komoditas yang tersedia</p>
        </div>
        @endforelse
    </div>
</section>
@endif

<!-- ===== DETAIL POPUP MODAL ===== -->
<div class="detail-popup-overlay" id="detailPopupOverlay">
    <div class="detail-popup-modal" id="detailPopupModal">
        <button class="detail-popup-close" id="detailPopupClose" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
        <div>
            <div class="detail-popup-img-wrap" id="detailImgWrap">
                <img id="detailImg" src="" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:20px 20px 0 0">
                <div class="detail-popup-img-placeholder"><i class="bi bi-basket" style="font-size:2.5rem;opacity:0.3"></i></div>
            </div>
            <div class="detail-popup-content">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div>
                        <h5 class="fw-bold mb-0" id="detailName" style="font-size:1.15rem"></h5>
                        <small class="text-muted" id="detailUnit"></small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if(true)
                        <span class="badge" id="detailStatusBadge" style="font-size:0.7rem;border-radius:8px;font-weight:600"></span>
                        @endif
                        <span class="badge-modern" id="detailChangeBadge" style="font-size:0.75rem"></span>
                    </div>
                </div>

                <!-- Price -->
                <div class="detail-popup-price-box mb-3">
                    <div class="detail-popup-current-price" id="detailPrice">Rp 0</div>
                    <div class="d-flex align-items-center gap-3 mt-1">
                        <small class="price-original" id="detailPricePrev" style="font-size:0.78rem"></small>
                        <small class="text-muted" id="detailDate" style="font-size:0.72rem"><i class="bi bi-clock me-1"></i></small>
                    </div>
                    <div class="mt-2" id="detailChangeInfo"></div>
                </div>

                <!-- HET / HAP / HPP -->
                <div class="mb-3" id="detailHETWrap" style="display:none">
                    <h6 class="fw-bold mb-2" style="font-size:0.85rem;color:var(--primary)"><i class="bi bi-tags me-1"></i>Informasi Harga Referensi</h6>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="p-2 text-center" style="border-radius:10px;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.12)">
                                <div class="fw-bold" style="font-size:0.65rem;color:#ef4444;margin-bottom:2px">HET</div>
                                <div class="fw-bold" id="detailHET" style="font-size:0.88rem;color:#ef4444">-</div>
                                <div style="font-size:0.55rem;color:#94a3b8">Harga Eceran Tertinggi</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 text-center" style="border-radius:10px;background:rgba(14,165,233,0.06);border:1px solid rgba(14,165,233,0.12)">
                                <div class="fw-bold" style="font-size:0.65rem;color:#0ea5e9;margin-bottom:2px">HAP</div>
                                <div class="fw-bold" id="detailHAP" style="font-size:0.88rem;color:#0ea5e9">-</div>
                                <div style="font-size:0.55rem;color:#94a3b8">Harga Acuan Pemerintah</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 text-center" style="border-radius:10px;background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.12)">
                                <div class="fw-bold" style="font-size:0.65rem;color:#f59e0b;margin-bottom:2px">HPP</div>
                                <div class="fw-bold" id="detailHPP" style="font-size:0.88rem;color:#f59e0b">-</div>
                                <div style="font-size:0.55rem;color:#94a3b8">Harga Pokok Penjualan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prediction -->
                <div class="mb-3" id="detailPredWrap" style="display:none">
                    <h6 class="fw-bold mb-2" style="font-size:0.85rem;color:var(--primary)"><i class="bi bi-robot me-1"></i>Prediksi AI 7 Hari</h6>
                    <div class="p-2" style="border-radius:10px;background:rgba(99,102,241,0.04);border:1px solid rgba(99,102,241,0.08)">
                        <span class="fw-semibold" id="detailPredText" style="font-size:0.82rem"></span>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3" id="detailDescWrap" style="display:none">
                    <h6 class="fw-bold mb-1" style="font-size:0.82rem;color:var(--primary)"><i class="bi bi-info-circle me-1"></i>Informasi</h6>
                    <p class="text-muted small mb-0" id="detailDesc" style="font-size:0.82rem;line-height:1.6"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const overlay = document.getElementById('detailPopupOverlay');
    const modal = document.getElementById('detailPopupModal');
    const closeBtn = document.getElementById('detailPopupClose');
    if (!overlay || !modal || !closeBtn) return;

    function openDetail(card) {
        const name = card.dataset.cName || '-';
        const unit = card.dataset.cUnit || '';
        const img = card.dataset.cImg || '';
        const desc = card.dataset.cDesc || '';
        const price = card.dataset.cPrice || '0';
        const pricePrev = card.dataset.cPricePrev || '0';
        const change = parseFloat(card.dataset.cChange) || 0;
        const date = card.dataset.cDate || '-';
        const het = card.dataset.cHet || '';
        const hap = card.dataset.cHap || '';
        const hpp = card.dataset.cHpp || '';
        const status = card.dataset.cStatus || '';
        const statusColor = card.dataset.cStatusColor || '#64748b';
        const pred = card.dataset.cPred || '';
        const predColor = card.dataset.cPredColor || '#64748b';

        // Image
        const imgWrap = document.getElementById('detailImgWrap');
        const imgEl = document.getElementById('detailImg');
        if (img) { imgEl.src = img; imgEl.alt = name; imgWrap.classList.remove('no-img'); }
        else { imgWrap.classList.add('no-img'); }

        document.getElementById('detailName').textContent = name;
        document.getElementById('detailUnit').textContent = unit;
        document.getElementById('detailPrice').textContent = 'Rp ' + price;

        const prevEl = document.getElementById('detailPricePrev');
        if (pricePrev !== price && parseFloat(pricePrev) > 0) { prevEl.textContent = 'Sebelumnya: Rp ' + pricePrev; prevEl.style.display = ''; }
        else { prevEl.style.display = 'none'; }

        document.getElementById('detailDate').innerHTML = '<i class="bi bi-clock me-1"></i>' + date;

        const badge = document.getElementById('detailChangeBadge');
        const changeInfo = document.getElementById('detailChangeInfo');
        if (change > 0) {
            badge.className = 'badge-modern price-up';
            badge.innerHTML = '<i class="bi bi-arrow-up-short"></i>+' + change.toFixed(1) + '%';
            changeInfo.innerHTML = '<span style="color:#ef4444;font-size:0.78rem"><i class="bi bi-arrow-up-circle me-1"></i>Harga naik ' + change.toFixed(1) + '%</span>';
        } else if (change < 0) {
            badge.className = 'badge-modern price-down';
            badge.innerHTML = '<i class="bi bi-arrow-down-short"></i>' + change.toFixed(1) + '%';
            changeInfo.innerHTML = '<span style="color:#10b981;font-size:0.78rem"><i class="bi bi-arrow-down-circle me-1"></i>Harga turun ' + Math.abs(change).toFixed(1) + '%</span>';
        } else {
            badge.className = 'badge-modern price-stable';
            badge.innerHTML = '<i class="bi bi-dash"></i>0%';
            changeInfo.innerHTML = '<span style="color:#64748b;font-size:0.78rem"><i class="bi bi-dash-circle me-1"></i>Harga stabil</span>';
        }

        // Status badge
        const statusBadge = document.getElementById('detailStatusBadge');
        if (status) { statusBadge.textContent = status; statusBadge.style.background = statusColor + '22'; statusBadge.style.color = statusColor; statusBadge.style.display = ''; }
        else { statusBadge.style.display = 'none'; }

        // HET/HAP/HPP
        const hetWrap = document.getElementById('detailHETWrap');
        const hasHET = het && het !== '' && het !== '0';
        const hasHAP = hap && hap !== '' && hap !== '0';
        const hasHPP = hpp && hpp !== '' && hpp !== '0';
        if (hasHET || hasHAP || hasHPP) {
            hetWrap.style.display = '';
            document.getElementById('detailHET').textContent = hasHET ? 'Rp ' + het : '-';
            document.getElementById('detailHAP').textContent = hasHAP ? 'Rp ' + hap : '-';
            document.getElementById('detailHPP').textContent = hasHPP ? 'Rp ' + hpp : '-';
        } else { hetWrap.style.display = 'none'; }

        // Prediction
        const predWrap = document.getElementById('detailPredWrap');
        if (pred && pred !== '-') { predWrap.style.display = ''; document.getElementById('detailPredText').textContent = pred; document.getElementById('detailPredText').style.color = predColor; }
        else { predWrap.style.display = 'none'; }

        // Description
        const descWrap = document.getElementById('detailDescWrap');
        if (desc) { document.getElementById('detailDesc').textContent = desc; descWrap.style.display = ''; }
        else { descWrap.style.display = 'none'; }

        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closePopup() { overlay.classList.remove('active'); document.body.style.overflow = ''; }

    closeBtn.addEventListener('click', closePopup);
    overlay.addEventListener('click', function(e) { if (e.target === overlay) closePopup(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closePopup(); });

    document.addEventListener('click', function(e) {
        const card = e.target.closest('.commodity-detail-trigger');
        if (card) { e.preventDefault(); openDetail(card); }
    });
})();
</script>
@endsection
