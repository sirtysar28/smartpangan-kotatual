@extends('layouts.admin')
@section('title', 'AI Dashboard - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

@php
    // Ringkasan prediksi
    $monitored = $forecasts->count();
    $rising = $forecasts->filter(fn ($f) => $f['predicted_change_pct'] >= 0.5);
    $falling = $forecasts->filter(fn ($f) => $f['predicted_change_pct'] <= -0.5);
    $stable = $monitored - $rising->count() - $falling->count();
    $avgRise = $rising->count() ? $rising->avg('predicted_change_pct') : 0;
@endphp

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-robot me-2"></i>AI Market Intelligence 🤖</h4>
        <p class="text-muted mb-0 small">Prediksi kenaikan harga pangan & proyeksi harga 7 hari ke depan berbasis riwayat Panel Harga</p>
    </div>
    <form action="{{ route('admin.ai-dashboard.generate') }}" method="POST">@csrf
        <button class="btn btn-primary-modern"><i class="bi bi-lightning me-2"></i>Generate Insights</button>
    </form>
</div>

{{-- ===== RINGKASAN ===== --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 animate-fade-up delay-1">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#6366f1,#818cf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(99,102,241,0.1)"><i class="bi bi-basket" style="color:#6366f1"></i></div>
                <div><small class="text-muted">Komoditas Dipantau</small><div class="fw-bold fs-5">{{ $monitored }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-2">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#ef4444,#f97316);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(239,68,68,0.1)"><i class="bi bi-graph-up-arrow" style="color:#ef4444"></i></div>
                <div><small class="text-muted">Prediksi Naik</small><div class="fw-bold fs-5">{{ $rising->count() }} <span class="small fw-normal" style="color:#ef4444">±{{ number_format($avgRise, 1, ',', '.') }}%</span></div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-3">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#10b981,#34d399);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(16,185,129,0.1)"><i class="bi bi-graph-down-arrow" style="color:#10b981"></i></div>
                <div><small class="text-muted">Potensi Turun</small><div class="fw-bold fs-5">{{ $falling->count() }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-4">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#0ea5e9,#38bdf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(14,165,233,0.1)"><i class="bi bi-dash-circle" style="color:#0ea5e9"></i></div>
                <div><small class="text-muted">Stabil</small><div class="fw-bold fs-5">{{ max(0, $stable) }}</div></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- ===== PREDIKSI KENAIKAN HARGA PANGAN ===== --}}
    <div class="col-lg-5 animate-fade-up delay-1">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3 pb-0">
                <h6 class="fw-bold" style="font-size:0.95rem"><i class="bi bi-graph-up-arrow me-2" style="color:#ef4444"></i>Prediksi Kenaikan Harga Pangan</h6>
                <small class="text-muted" style="font-size:0.72rem">Komoditas berpotensi naik dalam 7 hari ke depan</small>
            </div>
            <div class="p-3 pt-2">
                @forelse($rising->take(8) as $f)
                @php
                    if ($f['risk'] === 'tinggi') { $bg='rgba(239,68,68,0.06)'; $bdr='#ef4444'; $badge='background:rgba(239,68,68,0.1);color:#ef4444'; $label='Naik Tinggi'; }
                    else { $bg='rgba(245,158,11,0.06)'; $bdr='#f59e0b'; $badge='background:rgba(245,158,11,0.1);color:#f59e0b'; $label='Waspada'; }
                    $aboveHet = $f['het'] !== null && $f['predicted_price'] > $f['het'];
                @endphp
                <div class="mb-2 p-2" style="border-radius:10px;background:{{ $bg }};border-left:3px solid {{ $bdr }}">
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <div class="min-w-0">
                            <small class="fw-semibold" style="font-size:0.82rem">{{ $f['name'] }}</small>
                            <div class="text-muted" style="font-size:0.7rem">
                                Rp {{ number_format($f['current_price'], 0, ',', '.') }} → <strong style="color:#ef4444">Rp {{ number_format($f['predicted_price'], 0, ',', '.') }}</strong> / {{ $f['unit'] }}
                            </div>
                        </div>
                        <span class="badge-modern flex-shrink-0" style="{{ $badge }};font-size:0.72rem">+{{ number_format($f['predicted_change_pct'], 1, ',', '.') }}%</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted" style="font-size:0.65rem">{{ $label }} · Tren {{ number_format($f['daily_trend_pct'], 2, ',', '.') }}%/hari · {{ $f['data_points'] }} data</small>
                        <small class="fw-semibold" style="font-size:0.65rem;color:#6366f1"><i class="bi bi-patch-check me-1"></i>{{ number_format($f['confidence'], 0, ',', '.') }}%</small>
                    </div>
                    @if($aboveHet)
                    <small style="font-size:0.65rem;color:#ef4444"><i class="bi bi-exclamation-triangle me-1"></i>Prediksi melewati HET (Rp {{ number_format($f['het'], 0, ',', '.') }})</small>
                    @endif
                </div>
                @empty
                <div class="text-center text-muted small py-4">
                    <i class="bi bi-emoji-smile d-block mb-2" style="font-size:1.6rem;color:#10b981"></i>
                    Tidak ada komoditas yang diprediksi naik. Harga pangan relatif stabil 👍
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== GRAFIK PREDIKSI 7 HARI ===== --}}
    <div class="col-lg-7 animate-fade-up delay-2">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3 pb-0">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size:0.95rem"><i class="bi bi-activity me-2" style="color:#6366f1"></i>Prediksi Harga 7 Hari ke Depan</h6>
                        <small class="text-muted" style="font-size:0.72rem">Riwayat 14 hari + proyeksi 7 hari (garis putus-putus)</small>
                    </div>
                    <select id="forecastCommoditySelect" class="form-select form-select-sm" style="width:auto;max-width:220px;font-size:0.8rem">
                        @foreach($forecasts as $f)
                        <option value="{{ $loop->index }}" {{ $loop->first ? 'selected' : '' }}>{{ $f['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="p-3">
                <div class="chart-box" style="position:relative;height:300px">
                    <canvas id="forecastChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== TABEL PROYEKSI LENGKAP ===== --}}
<div class="card border-0 mt-4 animate-fade-up delay-3" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3 pb-0">
        <h6 class="fw-bold" style="font-size:0.95rem"><i class="bi bi-table me-2" style="color:#0ea5e9"></i>Proyeksi Harga 7 Hari ke Depan — Semua Komoditas</h6>
    </div>
    <div class="table-responsive p-3 pt-2">
        <table class="table table-modern mb-0">
            <thead>
                <tr><th>Komoditas</th><th>Harga Saat Ini</th><th>Tren Harian</th><th>Prediksi Hari ke-7</th><th>Perubahan</th><th>Keyakinan</th><th>Status</th></tr>
            </thead>
            <tbody>
            @forelse($forecasts as $f)
                @php
                    $chg = $f['predicted_change_pct'];
                    $chgColor = $chg > 0.5 ? '#ef4444' : ($chg < -0.5 ? '#10b981' : '#0ea5e9');
                    if ($f['risk'] === 'tinggi') { $stBg='rgba(239,68,68,0.1)'; $stC='#ef4444'; $stL='Naik Tinggi'; }
                    elseif ($f['risk'] === 'waspada') { $stBg='rgba(245,158,11,0.1)'; $stC='#f59e0b'; $stL='Waspada Naik'; }
                    elseif ($f['risk'] === 'turun') { $stBg='rgba(16,185,129,0.1)'; $stC='#10b981'; $stL='Berpotensi Turun'; }
                    else { $stBg='rgba(14,165,233,0.1)'; $stC='#0ea5e9'; $stL='Stabil'; }
                @endphp
                <tr>
                    <td class="fw-semibold" style="font-size:0.85rem">{{ $f['name'] }}</td>
                    <td style="font-size:0.82rem">Rp {{ number_format($f['current_price'], 0, ',', '.') }} / {{ $f['unit'] }}</td>
                    <td style="font-size:0.82rem;color:{{ $f['daily_trend_pct'] > 0 ? '#ef4444' : ($f['daily_trend_pct'] < 0 ? '#10b981' : '#64748b') }}">{{ number_format($f['daily_trend_pct'], 2, ',', '.') }}%/hari</td>
                    <td style="font-size:0.82rem" class="fw-semibold">Rp {{ number_format($f['predicted_price'], 0, ',', '.') }}</td>
                    <td><span style="color:{{ $chgColor }};font-weight:600;font-size:0.82rem">{{ $chg > 0 ? '↑ +' : ($chg < 0 ? '↓ ' : '') }}{{ number_format($chg, 1, ',', '.') }}%</span></td>
                    <td style="font-size:0.82rem">{{ number_format($f['confidence'], 0, ',', '.') }}%</td>
                    <td><span class="badge-modern" style="background:{{ $stBg }};color:{{ $stC }}">{{ $stL }}</span></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted small py-4">Belum ada data harga komoditas pangan untuk diprediksi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- AI Insights -->
<div class="card border-0 mt-4 animate-fade-up delay-3" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-lightbulb me-2" style="color:#6366f1"></i>Semua AI Insights</h6>
        @foreach($allInsights as $insight)
        @php
            $sev = $insight->severity;
            if ($sev === 'critical') { $bgS='rgba(239,68,68,0.06)'; $bdrS='#ef4444'; $icS='exclamation-triangle-fill'; $icC='#ef4444'; }
            elseif ($sev === 'warning') { $bgS='rgba(245,158,11,0.06)'; $bdrS='#f59e0b'; $icS='exclamation-circle-fill'; $icC='#f59e0b'; }
            else { $bgS='rgba(99,102,241,0.06)'; $bdrS='#6366f1'; $icS='lightbulb-fill'; $icC='#6366f1'; }
        @endphp
        <div class="d-flex gap-3 mb-2 p-2" style="border-radius:10px;background:{{ $bgS }};border-left:3px solid {{ $bdrS }}">
            <i class="bi bi-{{ $icS }}" style="font-size:1.3rem;color:{{ $icC }};margin-top:2px"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold small">{{ $insight->title }}</div>
                <p class="text-muted small mb-1">{{ $insight->description }}</p>
                <small class="text-muted">Confidence: {{ $insight->confidence_score }}% · {{ $insight->created_at->diffForHumans() }}</small>
            </div>
        </div>
        @endforeach
        {{ $allInsights->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
@php
    // Siapkan data grafik di PHP (jangan pakai @json multiline — regex directive
    // Blade memotong argumen di ")" pertama → parse error, revisi 15 Okt 2026)
    $forecastChartData = $forecasts->map(fn ($f) => [
        'name' => $f['name'],
        'unit' => $f['unit'],
        'history' => $f['history'],
        'forecast' => $f['forecast_points'],
    ])->all();
@endphp
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!window.Chart) return;

    const forecastData = @json($forecastChartData);

    const select = document.getElementById('forecastCommoditySelect');
    const canvas = document.getElementById('forecastChart');
    if (!select || !canvas || !forecastData.length) return;

    let chart = null;

    function fmtDate(d) {
        return d.toISOString().slice(0, 10);
    }

    function renderChart() {
        const f = forecastData[select.value];
        if (!f) return;

        const histDates = f.history.map(h => h.date);
        const lastDate = histDates.length ? new Date(histDates[histDates.length - 1] + 'T00:00:00') : new Date();

        const labels = histDates.slice();
        for (let d = 1; d <= 7; d++) {
            const dt = new Date(lastDate);
            dt.setDate(dt.getDate() + d);
            labels.push(fmtDate(dt));
        }

        const histPrices = f.history.map(h => h.price);
        const lastPrice = histPrices.length ? histPrices[histPrices.length - 1] : null;
        const histData = histPrices.concat(Array(7).fill(null));
        const fcData = Array(Math.max(0, histPrices.length - 1)).fill(null).concat([lastPrice], f.forecast);

        if (chart) chart.destroy();

        chart = new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Historis',
                        data: histData,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99,102,241,0.08)',
                        fill: true, tension: 0.35, pointRadius: 3, borderWidth: 2,
                    },
                    {
                        label: 'Prediksi 7 Hari',
                        data: fcData,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.08)',
                        borderDash: [6, 4],
                        fill: true, tension: 0.35, pointRadius: 3, borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { labels: { usePointStyle: true, boxWidth: 8, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            title: ctx => 'Tanggal: ' + ctx[0].label,
                            label: ctx => ctx.dataset.label + ': Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID') + (f.unit ? ' / ' + f.unit : ''),
                        },
                    },
                },
                scales: {
                    y: {
                        ticks: {
                            color: '#64748b', font: { size: 10 },
                            callback: v => 'Rp ' + Number(v).toLocaleString('id-ID'),
                        },
                        grid: { color: 'rgba(100,116,139,0.1)' },
                    },
                    x: {
                        ticks: { color: '#64748b', font: { size: 10 }, maxTicksLimit: 8, maxRotation: 0 },
                        grid: { display: false },
                    },
                },
            },
        });
    }

    select.addEventListener('change', renderChart);
    renderChart();
});
</script>
@endpush
