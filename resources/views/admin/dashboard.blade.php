@extends('layouts.admin')
@section('title', 'Dashboard Admin - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<!-- Welcome -->
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem">📊 Monitoring Harga, Pasokan & Keamanan Pangan</h4>
        <p class="text-muted mb-0 small">PANCA — Layanan Pangan Cerdas: Sistem Monitoring Stabilisasi Harga, Pasokan, dan Keamanan Pangan Kota Tual</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>Online</span>
        <span class="badge-modern" style="background:rgba(100,116,139,0.1);color:#64748b">{{ now()->format('d M Y') }}</span>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 animate-fade-up delay-1">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#6366f1,#818cf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Total Komoditas</small>
                    <div class="stat-value mt-1" style="color:#6366f1">{{ $totalCommodities }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(99,102,241,0.1)"><i class="bi bi-graph-up-arrow" style="color:#6366f1"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-2">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#ef4444,#f97316);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Harga Naik 🔴</small>
                    <div class="stat-value mt-1" style="color:#ef4444">{{ $upCount }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(239,68,68,0.1)"><i class="bi bi-arrow-up-circle" style="color:#ef4444"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-3">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#10b981,#34d399);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Harga Turun 🟢</small>
                    <div class="stat-value mt-1" style="color:#10b981">{{ $downCount }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.1)"><i class="bi bi-arrow-down-circle" style="color:#10b981"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-4">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#0ea5e9,#38bdf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Harga Stabil 🔵</small>
                    <div class="stat-value mt-1" style="color:#0ea5e9">{{ $stableCount }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(14,165,233,0.1)"><i class="bi bi-dash-circle" style="color:#0ea5e9"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-4 mb-4">
    <div class="col-lg-8 animate-fade-up delay-3">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">📈 Tren Harga Komoditas</h6>
                    <small class="text-muted">14 hari terakhir — @if($commodityTrend) {{ count($commodityTrend) }} komoditas utama @endif</small>
                </div>
            </div>
            <div style="position:relative;height:320px">
                <canvas id="priceTrendChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 animate-fade-up delay-4">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">🎯 Status Harga</h6>
                    <small class="text-muted">Distribusi naik/turun/stabil</small>
                </div>
            </div>
            <div style="position:relative;height:240px">
                <canvas id="priceStatusChart"></canvas>
            </div>
            @if($avgChange != 0)
            <div class="text-center mt-3">
                <span class="badge-modern" style="font-size:0.8rem;background:{{ $avgChange > 0 ? 'rgba(239,68,68,0.1)' : 'rgba(16,185,129,0.1)' }};color:{{ $avgChange > 0 ? '#ef4444' : '#10b981' }}">
                    <i class="bi bi-{{ $avgChange > 0 ? 'trending-up' : 'trending-down' }}"></i>
                    Rata-rata {{ $avgChange > 0 ? '+' : '' }}{{ number_format($avgChange, 1) }}%
                </span>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ALERT: Kenaikan Harga -->
@if($priceIncreases->count() > 0)
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);border-left:4px solid #ef4444;background:linear-gradient(135deg,rgba(239,68,68,0.04),rgba(245,158,11,0.02))">
    <div class="p-3">
        <div class="d-flex align-items-start gap-3">
            <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:rgba(239,68,68,0.12)">
                <i class="bi bi-exclamation-triangle-fill" style="color:#ef4444;font-size:1.1rem"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold small" style="color:#ef4444">⚠️ {{ $priceIncreases->count() }} Komoditas Harga Naik (7 hari terakhir)</div>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    @foreach($priceIncreases->take(6) as $pi)
                    <span class="badge-modern" style="background:rgba(239,68,68,0.1);color:#ef4444;font-size:0.68rem">
                        <i class="bi bi-arrow-up-short"></i>{{ $pi->commodity->name }} +{{ number_format($pi->change_percent,1) }}%
                    </span>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('admin.ai-dashboard.inflation') }}" class="btn btn-sm" style="color:var(--primary);font-size:0.75rem">Detail <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>
@endif

<!-- ===== KETAHANAN PANGAN (BAPANAS) ===== -->
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="section-title">
                <span class="icon-circle" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-shield-check"></i></span>
                Status Ketahanan Pangan
            </div>
            <div class="d-flex align-items-center gap-3">
                <small class="text-muted">Indeks Keseluruhan</small>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:140px;height:10px;border-radius:10px;background:#e2e8f0;overflow:hidden">
                        <div style="width:{{ $foodSecurity['overall_index'] }}%;height:100%;border-radius:10px;background:{{ $foodSecurity['overall_index'] >= 70 ? 'linear-gradient(90deg,#10b981,#34d399)' : ($foodSecurity['overall_index'] >= 50 ? 'linear-gradient(90deg,#f59e0b,#fbbf24)' : 'linear-gradient(90deg,#ef4444,#f87171)') }};transition:width 1.5s ease"></div>
                    </div>
                    <span class="fw-bold fs-5" style="color:{{ $foodSecurity['overall_index'] >= 70 ? '#10b981' : ($foodSecurity['overall_index'] >= 50 ? '#f59e0b' : '#ef4444') }}">{{ $foodSecurity['overall_index'] }}</span>
                </div>
            </div>
        </div>

        <!-- Summary Badges -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-shield-check me-1"></i>Aman: {{ $foodSecurity['summary']['aman'] }}</span>
            <span class="badge-modern" style="background:rgba(14,165,233,0.1);color:#0ea5e9"><i class="bi bi-shield-fill-exclamation me-1"></i>Cukup: {{ $foodSecurity['summary']['cukup'] }}</span>
            <span class="badge-modern" style="background:rgba(245,158,11,0.1);color:#f59e0b"><i class="bi bi-exclamation-triangle me-1"></i>Rawan: {{ $foodSecurity['summary']['rawan'] }}</span>
            @if(!empty($foodSecurity['summary']['lonjakan']))
            <span class="badge-modern" style="background:rgba(249,115,22,0.1);color:#f97316"><i class="bi bi-exclamation-diamond me-1"></i>Lonjakan: {{ $foodSecurity['summary']['lonjakan'] }}</span>
            @endif
            <span class="badge-modern" style="background:rgba(239,68,68,0.1);color:#ef4444"><i class="bi bi-shield-fill-x me-1"></i>Kritis: {{ $foodSecurity['summary']['kritis'] }}</span>
        </div>

        <!-- Per Category -->
        @if(!empty($foodSecurity['by_category']))
        <div class="mb-3">
            <small class="fw-bold text-muted" style="font-size:0.75rem">RINGKASAN PER KATEGORI</small>
            <div class="row g-2 mt-1">
                @foreach($foodSecurity['by_category'] as $catName => $catData)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="p-2" style="border-radius:10px;background:rgba(0,0,0,0.02);border:1px solid #e2e8f0">
                        <div class="fw-semibold" style="font-size:0.78rem">{{ ucfirst($catName) }}</div>
                        <div class="d-flex gap-1 mt-1 flex-wrap">
                            @if($catData['aman'] > 0)<span style="font-size:0.65rem;color:#10b981">●{{ $catData['aman'] }}</span>@endif
                            @if($catData['cukup'] > 0)<span style="font-size:0.65rem;color:#0ea5e9">●{{ $catData['cukup'] }}</span>@endif
                            @if($catData['rawan'] > 0)<span style="font-size:0.65rem;color:#f59e0b">●{{ $catData['rawan'] }}</span>@endif
                            @if($catData['kritis'] > 0)<span style="font-size:0.65rem;color:#ef4444">●{{ $catData['kritis'] }}</span>@endif
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size:0.62rem">Rata-rata: {{ $catData['avg_change'] > 0 ? '+' : '' }}{{ $catData['avg_change'] }}%</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Commodity Grid with Status -->
        <div class="row g-2">
            @php $commoditySecurity = collect($foodSecurity['commodities'] ?? [])->filter(fn($c) => !empty($c['has_data']))->sortBy(fn($c) => ['kritis'=>0,'rawan'=>1,'cukup'=>2,'aman'=>3][$c['status'] ?? ''] ?? 4); @endphp
            @foreach($commoditySecurity as $cs)
            @php
                $csBg    = $cs['bg_color'] ?? 'transparent';
                $csBdr   = $cs['border_color'] ?? '#e2e8f0';
                $csClr   = $cs['status_color'] ?? '#64748b';
                $csIcon  = $cs['status_icon'] ?? 'dash-circle';
                $csLabel = $cs['status_label'] ?? '-';
                $csName  = $cs['name'] ?? '-';
                $csUnit  = $cs['unit'] ?? '';
                $csPrice = $cs['current_price'] ?? 0;
                $csChg   = $cs['change_percent'] ?? 0;
                $csPred  = $cs['prediction'] ?? [];
                $pdir    = $csPred['direction'] ?? 'unknown';
                $pdirIcon = match($pdir) { 'up' => 'arrow-up', 'up_slight' => 'arrow-up-right', 'stable' => 'arrow-right', 'down_slight' => 'arrow-down-right', 'down' => 'arrow-down', default => 'dash' };
                $pdirClr  = match($pdir) { 'up' => '#ef4444', 'up_slight' => '#f59e0b', 'stable' => '#0ea5e9', 'down_slight' => '#10b981', 'down' => '#10b981', default => '#64748b' };
            @endphp
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="commodity-card h-100" style="border-left:4px solid {{ $csClr }};background:{{ $csBg }}">
                    <div class="d-flex align-items-start justify-content-between mb-1">
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size:0.8rem">{{ $csName }}</h6>
                            <small class="text-muted" style="font-size:0.65rem">{{ $csUnit }}</small>
                        </div>
                        <span class="badge" style="background:{{ $csBdr }};color:{{ $csClr }};font-size:0.55rem;border-radius:6px;font-weight:600">
                            <i class="bi bi-{{ $csIcon }} me-1"></i>{{ $csLabel }}
                        </span>
                    </div>
                    <div class="fw-bold" style="font-size:0.95rem;color:{{ $csClr }}">Rp {{ number_format($csPrice, 0, ',', '.') }}</div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small style="font-size:0.65rem;color:{{ $pdirClr }}" title="{{ $csPred['note'] ?? '' }}">
                            <i class="bi bi-{{ $pdirIcon }} me-1"></i>{{ $csPred['direction_label'] ?? '-' }}
                        </small>
                        <small class="text-muted" style="font-size:0.62rem">{{ $csChg > 0 ? '+' : '' }}{{ number_format($csChg, 1) }}%</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Prediction Detail Table -->
        @php $alertItems = collect($foodSecurity['commodities'])->filter(fn($c) => in_array($c['status'], ['rawan', 'kritis']) && $c['has_data']); @endphp
        @if($alertItems->count() > 0)
        <div class="mt-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-megaphone-fill" style="color:#f59e0b"></i>
                <h6 class="fw-bold mb-0" style="font-size:0.9rem">Detail Prediksi Komoditas Bermasalah</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-modern" style="font-size:0.8rem">
                    <thead>
                        <tr>
                            <th>Komoditas</th>
                            <th>Status</th>
                            <th>Harga Saat Ini</th>
                            <th>Perubahan</th>
                            <th>Tren 7 Hari</th>
                            <th>Prediksi</th>
                            <th>Estimasi Harga</th>
                            <th>Akurasi</th>
                            <th>Analisis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alertItems as $ai)
                        @php
                            $aiBg    = $ai['bg_color'] ?? 'transparent';
                            $aiBdr   = $ai['border_color'] ?? '#e2e8f0';
                            $aiClr   = $ai['status_color'] ?? '#64748b';
                            $aiIcon  = $ai['status_icon'] ?? 'dash-circle';
                            $aiLabel = $ai['status_label'] ?? '-';
                            $aiName  = $ai['name'] ?? '-';
                            $aiPrice = $ai['current_price'] ?? 0;
                            $aiChg   = $ai['change_percent'] ?? 0;
                            $aiTrend = $ai['trend_7d'] ?? 0;
                            $aiPred  = $ai['prediction'] ?? [];
                            $tDir    = $aiPred['direction'] ?? 'unknown';
                            $tDirColor = match($tDir) { 'up' => '#ef4444', 'up_slight' => '#f59e0b', 'stable' => '#0ea5e9', 'down_slight' => '#10b981', 'down' => '#10b981', default => '#64748b' };
                        @endphp
                        <tr style="background:{{ $aiBg }}">
                            <td class="fw-semibold">{{ $aiName }}</td>
                            <td><span class="badge" style="background:{{ $aiBdr }};color:{{ $aiClr }};font-size:0.65rem;border-radius:6px"><i class="bi bi-{{ $aiIcon }} me-1"></i>{{ $aiLabel }}</span></td>
                            <td class="fw-bold">Rp {{ number_format($aiPrice, 0, ',', '.') }}</td>
                            <td style="color:{{ $aiChg > 0 ? '#ef4444' : '#10b981' }}">{{ $aiChg > 0 ? '+' : '' }}{{ number_format($aiChg, 1) }}%</td>
                            <td style="color:{{ $aiTrend > 0 ? '#ef4444' : '#10b981' }}">{{ $aiTrend > 0 ? '+' : '' }}{{ number_format($aiTrend, 1) }}%</td>
                            <td style="color:{{ $tDirColor }}">{{ $aiPred['direction_label'] ?? '-' }}</td>
                            <td class="small">{{ !empty($aiPred['predicted_min']) ? 'Rp ' . number_format($aiPred['predicted_min'], 0, ',', '.') . ' — Rp ' . number_format($aiPred['predicted_max'], 0, ',', '.') : '-' }}</td>
                            <td>{{ $aiPred['confidence'] ?? 0 }}%</td>
                            <td><small class="text-muted">{{ Str::limit($aiPred['note'] ?? '', 60) }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="text-center mt-2">
            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Analisis otomatis berdasarkan tren harga, volatilitas, dan pola perubahan. Update: {{ $foodSecurity['last_updated'] ? \Carbon\Carbon::parse($foodSecurity['last_updated'])->format('d M Y') : '-' }}</small>
        </div>
    </div>
</div>

<!-- MONITORING HARGA PANGAN GRID (dengan gambar) -->
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3 pb-0">
        <div class="d-flex justify-content-between align-items-center">
            <div class="section-title">
                <span class="icon-circle" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-bar-chart-line"></i></span>
                Monitoring Harga Pangan
            </div>
            <a href="{{ route('admin.commodities.index') }}" class="btn btn-sm fw-semibold" style="color:var(--primary);font-size:0.8rem">Kelola <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="p-3">
        <!-- Legend -->
        <div class="d-flex gap-3 mb-3 small">
            <span style="color:#ef4444"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>Naik</span>
            <span style="color:#10b981"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>Turun</span>
            <span style="color:#0ea5e9"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>Stabil</span>
        </div>
        <div class="row g-2">
            @foreach($commodities as $c)
            @php $ch = $c->latestPrice ? $c->latestPrice->change_percent : 0; @endphp
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="commodity-card h-100">
                    @if($c->image_url)
                    <div style="margin:-1rem -1rem 0.5rem;overflow:hidden;border-radius:var(--radius) var(--radius) 0 0;height:80px;position:relative">
                        <img src="{{ $c->image_url }}" alt="{{ $c->name }}" style="width:100%;height:100%;object-fit:cover" loading="lazy">
                        <div style="position:absolute;bottom:0;left:0;right:0;height:30px;background:linear-gradient(transparent,rgba(0,0,0,0.15))"></div>
                    </div>
                    @endif
                    <div class="d-flex align-items-start justify-content-between mb-1">
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size:0.8rem">{{ $c->name }}</h6>
                            <small class="text-muted" style="font-size:0.65rem">{{ $c->unit }}</small>
                        </div>
                        @if($c->latestPrice)
                            @if($ch > 0)
                                <span class="badge-modern" style="background:rgba(239,68,68,0.1);color:#ef4444;font-size:0.6rem"><i class="bi bi-arrow-up-short"></i>+{{ number_format($ch,1) }}%</span>
                            @elseif($ch < 0)
                                <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.6rem"><i class="bi bi-arrow-down-short"></i>{{ number_format($ch,1) }}%</span>
                            @else
                                <span class="badge-modern" style="background:rgba(14,165,233,0.1);color:#0ea5e9;font-size:0.6rem"><i class="bi bi-dash"></i>0%</span>
                            @endif
                        @endif
                    </div>
                    @if($c->latestPrice)
                        <div class="fw-bold" style="font-size:0.95rem;color:{{ $ch > 0 ? '#ef4444' : ($ch < 0 ? '#10b981' : 'var(--text-main)') }}">
                            Rp {{ number_format($c->latestPrice->price, 0, ',', '.') }}
                        </div>
                        <small class="text-muted" style="font-size:0.6rem"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($c->latestPrice->price_date)->format('d/m/Y') }}</small>
                    @else
                        <div class="text-muted small">Belum ada data</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- ===== KEAMANAN PANGAN ===== -->
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="section-title">
                <span class="icon-circle" style="background:rgba(14,165,233,0.1);color:#0ea5e9"><i class="bi bi-shield-check"></i></span>
                Keamanan Pangan
            </div>
            <a href="{{ route('admin.food-safety.index') }}" class="btn btn-sm fw-semibold" style="color:var(--primary);font-size:0.8rem">Kelola <i class="bi bi-arrow-right"></i></a>
        </div>

        <!-- Stats -->
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3">
                <div class="p-2 text-center" style="border-radius:10px;background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.12)">
                    <div class="fw-bold" style="font-size:1.3rem;color:#6366f1">{{ $foodSafetyStats['total_checks'] }}</div>
                    <small class="text-muted" style="font-size:0.7rem">Total Pemeriksaan</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2 text-center" style="border-radius:10px;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.12)">
                    <div class="fw-bold" style="font-size:1.3rem;color:#10b981">{{ $foodSafetyStats['layak'] }}</div>
                    <small class="text-muted" style="font-size:0.7rem">Layak Konsumsi</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2 text-center" style="border-radius:10px;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.12)">
                    <div class="fw-bold" style="font-size:1.3rem;color:#ef4444">{{ $foodSafetyStats['tidak_layak'] }}</div>
                    <small class="text-muted" style="font-size:0.7rem">Tidak Layak</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2 text-center" style="border-radius:10px;background:rgba(14,165,233,0.06);border:1px solid rgba(14,165,233,0.12)">
                    <div class="fw-bold" style="font-size:1.3rem;color:#0ea5e9">{{ $foodSafetyStats['total_activities'] }}</div>
                    <small class="text-muted" style="font-size:0.7rem">Kegiatan</small>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-3 mb-3">
            <div class="col-lg-8">
                <small class="fw-bold text-muted d-block mb-2" style="font-size:0.72rem">TREN PEMERIKSAAN (6 BULAN TERAKHIR)</small>
                <div style="position:relative;height:220px">
                    <canvas id="foodSafetyTrendChart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <small class="fw-bold text-muted d-block mb-2" style="font-size:0.72rem">DISTRIBUSI HASIL PEMERIKSAAN</small>
                <div style="position:relative;height:170px">
                    <canvas id="foodSafetyResultChart"></canvas>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-2 justify-content-center">
                    <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.62rem"><i class="bi bi-flower1 me-1"></i>Tumbuhan: {{ $foodSafetyBySource['tumbuhan'] }}</span>
                    <span class="badge-modern" style="background:rgba(14,165,233,0.1);color:#0ea5e9;font-size:0.62rem"><i class="bi bi-fish me-1"></i>Ikan: {{ $foodSafetyBySource['ikan'] }}</span>
                    <span class="badge-modern" style="background:rgba(245,158,11,0.1);color:#f59e0b;font-size:0.62rem"><i class="bi bi-egg me-1"></i>Hewan: {{ $foodSafetyBySource['hewan'] }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Checks + Activities -->
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0" style="font-size:0.85rem"><i class="bi bi-clock-history me-2" style="color:#0ea5e9"></i>Pemeriksaan Terbaru</h6>
                    <a href="{{ route('admin.food-safety.index') }}" class="small text-decoration-none fw-semibold" style="color:var(--primary)">Lihat Semua <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern mb-0" style="font-size:0.8rem">
                        <thead>
                            <tr><th>Nama Pangan</th><th>Asal</th><th>Hasil</th><th>Tgl Cek</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentFoodSafetyChecks as $fc)
                            @php
                                $fcClr = match($fc->inspection_result) { 'layak'=>'#10b981','tidak_layak'=>'#ef4444','perlu_tindak_lanjut'=>'#f59e0b', default=>'#64748b' };
                                $fcIcon = match($fc->inspection_result) { 'layak'=>'check-circle-fill','tidak_layak'=>'x-circle-fill','perlu_tindak_lanjut'=>'exclamation-triangle-fill', default=>'dash-circle' };
                                $fcLabel = $fc->inspection_result_label;
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $fc->food_name }}</td>
                                <td><small>{{ $fc->source_type_label }}</small></td>
                                <td><span class="badge" style="background:{{ $fcClr }}22;color:{{ $fcClr }};font-size:0.62rem"><i class="bi bi-{{ $fcIcon }} me-1"></i>{{ $fcLabel }}</span></td>
                                <td><small class="text-muted">{{ $fc->check_date?->format('d/m/Y') }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted small py-3">Belum ada data pemeriksaan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0" style="font-size:0.85rem"><i class="bi bi-people me-2" style="color:#f59e0b"></i>Kegiatan Terbaru</h6>
                    <a href="{{ route('admin.food-safety.index') }}#activityPanel" class="small text-decoration-none fw-semibold" style="color:var(--primary)">Lihat Semua <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="d-flex flex-column gap-2">
                    @forelse($recentFoodSafetyActivities as $fa)
                    <div class="d-flex gap-2 p-2" style="border-radius:10px;background:rgba(0,0,0,0.02);border:1px solid #e2e8f0">
                        <div style="width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:rgba(245,158,11,0.1)">
                            <i class="bi bi-{{ $fa->activity_type_icon }}" style="color:#f59e0b;font-size:0.9rem"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:0.8rem">{{ Str::limit($fa->title, 45) }}</div>
                            <small class="text-muted" style="font-size:0.68rem">{{ $fa->activity_type_label }} · {{ $fa->activity_date?->format('d M Y') }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted small py-3">Belum ada kegiatan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AI Insights -->
@if($aiInsights->count() > 0)
<div class="card border-0 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0" style="font-size:0.95rem"><i class="bi bi-robot me-2" style="color:#6366f1"></i>AI Insights Terbaru</h6>
            <a href="{{ route('admin.ai-dashboard.index') }}" class="small text-decoration-none fw-semibold" style="color:var(--primary)">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        @foreach($aiInsights->take(3) as $latestInsight)
        @php
            $severity = $latestInsight->severity;
            if ($severity === 'critical') {
                $bgStyle = 'rgba(239,68,68,0.06)'; $borderStyle = '#ef4444'; $iconBg = 'rgba(239,68,68,0.12)'; $iconClass = 'exclamation-triangle-fill'; $iconColor = '#ef4444';
            } elseif ($severity === 'warning') {
                $bgStyle = 'rgba(245,158,11,0.06)'; $borderStyle = '#f59e0b'; $iconBg = 'rgba(245,158,11,0.12)'; $iconClass = 'exclamation-circle-fill'; $iconColor = '#f59e0b';
            } else {
                $bgStyle = 'rgba(99,102,241,0.06)'; $borderStyle = '#6366f1'; $iconBg = 'rgba(99,102,241,0.12)'; $iconClass = 'lightbulb-fill'; $iconColor = '#6366f1';
            }
        @endphp
        <div class="d-flex gap-3 p-2 mb-2" style="border-radius:10px;background:{{ $bgStyle }};border-left:3px solid {{ $borderStyle }}">
            <div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $iconBg }}">
                <i class="bi bi-{{ $iconClass }}" style="font-size:1rem;color:{{ $iconColor }}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold small">{{ $latestInsight->title }}</div>
                <small class="text-muted">{{ Str::limit($latestInsight->description, 100) }}</small>
            </div>
            <small class="text-muted" style="font-size:0.68rem;white-space:nowrap">{{ $latestInsight->created_at->diffForHumans() }}</small>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- ===== PERIZINAN / UMKM ===== -->
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="section-title">
                <span class="icon-circle" style="background:rgba(139,92,246,0.1);color:#8b5cf6"><i class="bi bi-file-earmark-text"></i></span>
                Perizinan Pangan
            </div>
            <a href="{{ route('admin.umkms.index') }}" class="btn btn-sm fw-semibold" style="color:var(--primary);font-size:0.8rem">Kelola UMKM <i class="bi bi-arrow-right"></i></a>
        </div>

        <!-- Stats -->
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3">
                <div class="p-2 text-center" style="border-radius:10px;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.12)">
                    <div class="fw-bold" style="font-size:1.3rem;color:#10b981">{{ $permitStats['verified'] }}</div>
                    <small class="text-muted" style="font-size:0.7rem">Terverifikasi</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2 text-center" style="border-radius:10px;background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.12)">
                    <div class="fw-bold" style="font-size:1.3rem;color:#f59e0b">{{ $permitStats['pending'] }}</div>
                    <small class="text-muted" style="font-size:0.7rem">Menunggu Verifikasi</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2 text-center" style="border-radius:10px;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.12)">
                    <div class="fw-bold" style="font-size:1.3rem;color:#ef4444">{{ $permitStats['rejected'] }}</div>
                    <small class="text-muted" style="font-size:0.7rem">Ditolak</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2 text-center" style="border-radius:10px;background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.12)">
                    <div class="fw-bold" style="font-size:1.3rem;color:#6366f1">{{ $permitStats['total'] }}</div>
                    <small class="text-muted" style="font-size:0.7rem">Total UMKM</small>
                </div>
            </div>
        </div>

        <!-- Pending Permits Table -->
        @if($pendingPermits->count() > 0)
        <h6 class="fw-bold mb-2" style="font-size:0.9rem"><i class="bi bi-hourglass-split me-2" style="color:#f59e0b"></i>Perizinan Menunggu Verifikasi</h6>
        <div class="table-responsive mb-3">
            <table class="table table-modern mb-0" style="font-size:0.8rem">
                <thead>
                    <tr>
                        <th>Nama Usaha</th>
                        <th>Pemilik</th>
                        <th>Kategori</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingPermits as $pu)
                    <tr>
                        <td class="fw-semibold">{{ $pu->business_name }}</td>
                        <td>{{ $pu->user?->name ?? '-' }}</td>
                        <td><small>{{ $pu->business_category ?? '-' }}</small></td>
                        <td><small>{{ $pu->created_at?->format('d/m/Y') }}</small></td>
                        <td>
                            <div class="d-flex gap-1">
                                <form method="POST" action="{{ route('admin.umkms.verify', $pu) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.7rem;padding:0.25rem 0.5rem" title="Verifikasi"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <form method="POST" action="{{ route('admin.umkms.reject', $pu) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;font-size:0.7rem;padding:0.25rem 0.5rem" title="Tolak"><i class="bi bi-x-lg"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-3">
            <i class="bi bi-check-circle" style="font-size:1.5rem;color:#10b981;opacity:0.5"></i>
            <p class="text-muted small mt-1 mb-0">Tidak ada perizinan yang menunggu verifikasi.</p>
        </div>
        @endif

        <!-- Recent UMKM -->
        <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
            <h6 class="fw-bold mb-0" style="font-size:0.85rem"><i class="bi bi-clock-history me-2" style="color:#0ea5e9"></i>UMKM Terbaru</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-modern mb-0" style="font-size:0.78rem">
                <tbody>
                @foreach($recentUmkms as $ru)
                @php
                    $ruStatus = $ru->status;
                    $ruClr = match($ruStatus) {
                        'verified' => '#10b981',
                        'active' => '#10b981',
                        'pending' => '#f59e0b',
                        'rejected' => '#ef4444',
                        'suspended' => '#ef4444',
                        default => '#64748b'
                    };
                    $ruIcon = match($ruStatus) {
                        'verified' => 'patch-check-fill',
                        'active' => 'check-circle-fill',
                        'pending' => 'hourglass-split',
                        'rejected' => 'x-circle-fill',
                        'suspended' => 'pause-circle-fill',
                        default => 'dash-circle'
                    };
                    $ruLabel = match($ruStatus) {
                        'verified' => 'Terverifikasi',
                        'active' => 'Aktif',
                        'pending' => 'Menunggu',
                        'rejected' => 'Ditolak',
                        'suspended' => 'Ditangguhkan',
                        default => ucfirst($ruStatus ?? '-')
                    };
                @endphp
                <tr>
                    <td class="fw-semibold" style="font-size:0.75rem">{{ $ru->business_name }}</td>
                    <td><small>{{ $ru->user?->name ?? '-' }}</small></td>
                    <td><span class="badge" style="background:{{ $ruClr }}22;color:{{ $ruClr }};font-size:0.6rem"><i class="bi bi-{{ $ruIcon }} me-1"></i>{{ $ruLabel }}</span></td>
                    <td><small class="text-muted">{{ $ru->created_at?->format('d/m/Y') }}</small></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const RED = '#ef4444', GREEN = '#10b981', BLUE = '#0ea5e9', PURPLE = '#6366f1', ORANGE = '#f59e0b';

// ===== PRICE TREND CHART =====
(function() {
    const ctx = document.getElementById('priceTrendChart');
    if (!ctx) return;

    const trendData = @json($commodityTrend);
    if (!trendData || trendData.length === 0) return;

    const colors = [PURPLE, RED, GREEN, BLUE, ORANGE, '#ec4899', '#8b5cf6', '#14b8a6'];
    const datasets = trendData.map(function(item, i) {
        return {
            label: item.name,
            data: item.prices,
            borderColor: colors[i % colors.length],
            backgroundColor: colors[i % colors.length] + '18',
            borderWidth: 2.5,
            tension: 0.35,
            pointRadius: 3,
            pointHoverRadius: 7,
            pointBackgroundColor: '#fff',
            pointBorderColor: colors[i % colors.length],
            pointBorderWidth: 2,
            fill: true,
        };
    });

    const allDates = trendData[0].dates || [];
    new Chart(ctx, {
        type: 'line',
        data: { labels: allDates, datasets: datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 11, family: "'Inter', sans-serif" }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15,23,42,0.9)',
                    titleFont: { size: 12 },
                    bodyFont: { size: 11 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        callback: function(v) { return 'Rp ' + (v/1000) + 'K'; },
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 }, maxRotation: 0 }
                }
            },
            interaction: { intersect: false, mode: 'index' },
        }
    });
})();

// ===== PRICE STATUS BAR =====
(function() {
    const ctx = document.getElementById('priceStatusChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Naik 🔴', 'Turun 🟢', 'Stabil 🔵'],
            datasets: [{
                label: 'Jumlah Komoditas',
                data: [{{ $upCount }}, {{ $downCount }}, {{ $stableCount }}],
                backgroundColor: [RED, GREEN, BLUE],
                borderRadius: 8,
                borderSkipped: false,
                maxBarThickness: 72,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15,23,42,0.9)',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: { label: function(ctx) { return ctx.parsed.y + ' komoditas'; } }
                }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0, font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false }, ticks: { font: { size: 12, weight: '600' } } }
            }
        }
    });
})();

// ===== FOOD SAFETY TREND (stacked bar, 6 bulan) =====
(function() {
    const ctx = document.getElementById('foodSafetyTrendChart');
    if (!ctx) return;

    const trend = @json($foodSafetyTrend);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: trend.map(function(t) { return t.label; }),
            datasets: [
                { label: 'Layak', data: trend.map(function(t){return t.layak;}), backgroundColor: GREEN, borderRadius: 4, stack: 's', maxBarThickness: 40 },
                { label: 'Tidak Layak', data: trend.map(function(t){return t.tidak_layak;}), backgroundColor: RED, borderRadius: 4, stack: 's', maxBarThickness: 40 },
                { label: 'Perlu Tindak Lanjut', data: trend.map(function(t){return t.perlu_tindak_lanjut;}), backgroundColor: ORANGE, borderRadius: 4, stack: 's', maxBarThickness: 40 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', font: { size: 11 }, padding: 12 } },
                tooltip: { backgroundColor: 'rgba(15,23,42,0.9)', padding: 10, cornerRadius: 8 }
            },
            scales: {
                x: { stacked: true, grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { stacked: true, beginAtZero: true, ticks: { stepSize: 1, precision: 0, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.05)' } }
            }
        }
    });
})();

// ===== FOOD SAFETY RESULT (horizontal bar) =====
(function() {
    const ctx = document.getElementById('foodSafetyResultChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Layak', 'Tidak Layak', 'Tindak Lanjut'],
            datasets: [{
                label: 'Pemeriksaan',
                data: [{{ $foodSafetyStats['layak'] }}, {{ $foodSafetyStats['tidak_layak'] }}, {{ $foodSafetyStats['perlu_tindak_lanjut'] }}],
                backgroundColor: [GREEN, RED, ORANGE],
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 28,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: 'rgba(15,23,42,0.9)', padding: 10, cornerRadius: 8 }
            },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.05)' } },
                y: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' } } }
            }
        }
    });
})();
</script>
@endsection
