@extends('layouts.admin')
@section('title', 'Panel Harga Pangan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem">📊 Panel Harga Pangan — EWS</h4>
        <p class="text-muted mb-0 small">Monitoring harga, HET/HAP/HPP, pergerakan MTM & YOY sebagai Early Warning System</p>
    </div>
    <a href="{{ route('admin.commodities.create') }}" class="btn btn-primary-modern"><i class="bi bi-plus-lg me-2"></i>Tambah Komoditas</a>
</div>

<!-- Legend -->
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <span class="fw-bold small"><i class="bi bi-info-circle me-1"></i>Status Pangan EWS:</span>
            <span class="d-flex align-items-center gap-1" style="font-size:0.8rem;color:#10b981"><span style="width:10px;height:10px;border-radius:50%;background:#10b981;display:inline-block"></span> Aman (tidak naik / di bawah HET)</span>
            <span class="d-flex align-items-center gap-1" style="font-size:0.8rem;color:#f59e0b"><span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;display:inline-block"></span> Rawan (&lt;5% / di atas HET)</span>
            <span class="d-flex align-items-center gap-1" style="font-size:0.8rem;color:#f97316"><span style="width:10px;height:10px;border-radius:50%;background:#f97316;display:inline-block"></span> Lonjakan (&gt;6% / &gt;5% di atas HET)</span>
            <span class="d-flex align-items-center gap-1" style="font-size:0.8rem;color:#ef4444"><span style="width:10px;height:10px;border-radius:50%;background:#ef4444;display:inline-block"></span> Kritis (&gt;10% / &gt;10% di atas HET)</span>
        </div>
        <div class="mt-2 small text-muted"><i class="bi bi-shield-exclamation me-1"></i>Status otomatis naik ke <b>Rawan/Lonjakan/Kritis</b> bila harga hari ini <b>melebihi batas HET</b> (Harga Eceran Tertinggi).</div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 animate-fade-up delay-1">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#6366f1,#818cf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Total Komoditas</small>
                    <div class="stat-value mt-1" style="color:#6366f1">{{ $statusSummary['total'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(99,102,241,0.1)"><i class="bi bi-tag" style="color:#6366f1"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-2">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#10b981,#34d399);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Harga Aman</small>
                    <div class="stat-value mt-1" style="color:#10b981">{{ $statusSummary['aman'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.1)"><i class="bi bi-shield-check" style="color:#10b981"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-3">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#f59e0b,#fbbf24);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Rawan / Lonjakan</small>
                    <div class="stat-value mt-1" style="color:#f59e0b">{{ $statusSummary['rawan'] + $statusSummary['lonjakan'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(245,158,11,0.1)"><i class="bi bi-exclamation-triangle" style="color:#f59e0b"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-4">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#ef4444,#f87171);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Kritis</small>
                    <div class="stat-value mt-1" style="color:#ef4444">{{ $statusSummary['kritis'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(239,68,68,0.1)"><i class="bi bi-shield-fill-x" style="color:#ef4444"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- TABLE: Panel Harga Pangan Lengkap -->
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h6 class="fw-bold mb-0" style="font-size:1rem"><i class="bi bi-tag me-2" style="color:var(--primary)"></i>Data Harga Komoditas Pangan</h6>
            <small class="text-muted"><i class="bi bi-clock me-1"></i>Update: {{ now()->format('d/m/Y') }}</small>
        </div>
        <div class="table-responsive">
            <table class="table table-modern" style="font-size:0.78rem">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align:middle">Komoditas</th>
                        <th rowspan="2" style="vertical-align:middle">HET</th>
                        <th rowspan="2" style="vertical-align:middle">HAP</th>
                        <th rowspan="2" style="vertical-align:middle">HPP</th>
                        <th rowspan="2" style="vertical-align:middle">Harga Hari Ini</th>
                        <th rowspan="2" style="vertical-align:middle">Bulan Lalu</th>
                        <th rowspan="2" style="vertical-align:middle">Tahun Lalu</th>
                        <th colspan="2" class="text-center" style="border-bottom:2px solid var(--primary)">MTM (Month to Month)</th>
                        <th colspan="2" class="text-center" style="border-bottom:2px solid #f59e0b">YOY (Year to Year)</th>
                        <th rowspan="2" style="vertical-align:middle">Status Pangan</th>
                        <th rowspan="2" style="vertical-align:middle;min-width:120px">Update Harga</th>
                        <th rowspan="2" style="vertical-align:middle">Aksi</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="font-size:0.7rem">% / Arah</th>
                        <th class="text-center" style="font-size:0.7rem">% / Arah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commodities as $c)
                    @php
                        $pc = $c->price_comparison ?? [];
                        $todayPrice = $pc['today_price'] ?? ($c->latestPrice?->price ?? null);
                        $lastMonth = $pc['last_month_price'] ?? null;
                        $lastYear = $pc['last_year_price'] ?? null;
                        $mtmPct = $pc['mtm_percent'] ?? null;
                        $yoyPct = $pc['yoy_percent'] ?? null;
                        $mtmDir = $pc['mtm_direction'] ?? 'stable';
                        $yoyDir = $pc['yoy_direction'] ?? 'stable';
                        $fs = $pc['food_status'] ?? ['code'=>'aman','label'=>'Aman','color'=>'#10b981','icon'=>'shield-check'];

                        $mtmIcon = $mtmDir === 'up' ? 'arrow-up' : ($mtmDir === 'down' ? 'arrow-down' : 'dash');
                        $mtmColor = $mtmDir === 'up' ? '#ef4444' : ($mtmDir === 'down' ? '#10b981' : '#64748b');
                        $yoyIcon = $yoyDir === 'up' ? 'arrow-up' : ($yoyDir === 'down' ? 'arrow-down' : 'dash');
                        $yoyColor = $yoyDir === 'up' ? '#ef4444' : ($yoyDir === 'down' ? '#10b981' : '#64748b');
                    @endphp
                    <tr style="border-left:3px solid {{ $fs['color'] }}">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($c->image_url)
                                    <img src="{{ $c->image_url }}" alt="{{ $c->name }}" style="width:28px;height:28px;border-radius:6px;object-fit:cover">
                                @else
                                    <div style="width:28px;height:28px;border-radius:6px;background:rgba(99,102,241,0.1);display:flex;align-items:center;justify-content:center;font-size:0.7rem"><i class="bi bi-basket" style="color:var(--primary)"></i></div>
                                @endif
                                <div>
                                    <div class="fw-semibold" style="font-size:0.8rem">{{ $c->name }}</div>
                                    <small class="text-muted" style="font-size:0.62rem">per {{ $c->unit }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $c->het ? 'Rp '.number_format($c->het, 0, ',', '.') : '-' }}</td>
                        <td>{{ $c->hap ? 'Rp '.number_format($c->hap, 0, ',', '.') : '-' }}</td>
                        <td>{{ $c->hpp ? 'Rp '.number_format($c->hpp, 0, ',', '.') : '-' }}</td>
                        <td class="fw-bold" style="color:{{ $mtmDir === 'up' ? '#ef4444' : ($mtmDir === 'down' ? '#10b981' : 'var(--text-main)') }}">
                            {{ $todayPrice ? 'Rp '.number_format($todayPrice, 0, ',', '.') : '-' }}
                        </td>
                        <td>{{ $lastMonth ? 'Rp '.number_format($lastMonth, 0, ',', '.') : '-' }}</td>
                        <td>{{ $lastYear ? 'Rp '.number_format($lastYear, 0, ',', '.') : '-' }}</td>
                        <td class="text-center">
                            @if($mtmPct !== null)
                                <span style="color:{{ $mtmColor }};font-weight:600">
                                    <i class="bi bi-{{ $mtmIcon }}{{ $mtmDir !== 'stable' ? '-short' : '' }}"></i>
                                    {{ $mtmPct > 0 ? '+' : '' }}{{ number_format($mtmPct, 1) }}%
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($yoyPct !== null)
                                <span style="color:{{ $yoyColor }};font-weight:600">
                                    <i class="bi bi-{{ $yoyIcon }}{{ $yoyDir !== 'stable' ? '-short' : '' }}"></i>
                                    {{ $yoyPct > 0 ? '+' : '' }}{{ number_format($yoyPct, 1) }}%
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background:{{ $fs['color'] }}20;color:{{ $fs['color'] }};font-size:0.65rem;border-radius:6px;font-weight:700">
                                <i class="bi bi-{{ $fs['icon'] }} me-1"></i>{{ $fs['label'] }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.commodities.update-price', $c) }}" method="POST" class="d-flex gap-1 flex-wrap">@csrf
                                <input type="text" name="price" class="form-control form-control-sm price-input" placeholder="60.983" inputmode="numeric" autocomplete="off" style="font-size:0.72rem;border-radius:6px;min-width:80px" required>
                                <input type="date" name="price_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" style="font-size:0.68rem;border-radius:6px;min-width:110px" required title="Tanggal update harga">
                                <button type="submit" class="btn btn-sm" style="background:rgba(16,185,129,0.1);color:#10b981;border-radius:6px;flex-shrink:0;font-size:0.7rem" title="Update"><i class="bi bi-arrow-up"></i></button>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.commodities.edit', $c) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:var(--primary);border-radius:6px;font-size:0.7rem"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.commodities.destroy', $c) }}" method="POST" onsubmit="return confirm('Hapus komoditas {{ $c->name }}?')">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:6px;font-size:0.7rem"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="14" class="text-center py-4 text-muted">Belum ada data komoditas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- GRAFIK PERGERAKAN HARGA (Poin 5) -->
<div class="row g-4 mb-4">
    <div class="col-lg-8 animate-fade-up">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">📈 Grafik Pergerakan Harga Komoditas</h6>
                    <small class="text-muted">Tren harga 14 hari terakhir (per komoditas)</small>
                </div>
            </div>
            <div style="position:relative;height:340px">
                <canvas id="priceTrendChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 animate-fade-up">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">🎯 Distribusi Status Harga</h6>
                    <small class="text-muted">Aman / Rawan / Lonjakan / Kritis</small>
                </div>
            </div>
            <div style="position:relative;max-width:250px;margin:0 auto">
                <canvas id="priceStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center">
    {{ $commodities->withQueryString()->links() }}
</div>
@endsection

@push('scripts')
<script>
const RED='#ef4444',GREEN='#10b981',BLUE='#0ea5e9',PURPLE='#6366f1',ORANGE='#f59e0b';
const PALETTE=['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6'];

// Price Trend Line Chart
(function(){
    const ctx = document.getElementById('priceTrendChart');
    if(!ctx) return;
    const data = @json($priceTrendData);
    if(!data || data.length===0) return;

    const labels = data[0].dates;
    const datasets = data.map((d,i)=>({
        label: d.name,
        data: d.prices,
        borderColor: PALETTE[i % PALETTE.length],
        backgroundColor: PALETTE[i % PALETTE.length] + '20',
        tension: 0.35,
        borderWidth: 2,
        pointRadius: 2,
        pointHoverRadius: 5,
        fill: false,
    }));

    new Chart(ctx, {
        type:'line',
        data:{ labels: labels, datasets: datasets },
        options:{
            responsive:true, maintainAspectRatio:false,
            interaction:{ mode:'index', intersect:false },
            plugins:{
                legend:{position:'bottom',labels:{padding:10,usePointStyle:true,font:{size:10},boxWidth:8}},
                tooltip:{callbacks:{label:(c)=>` ${c.dataset.label}: Rp ${c.parsed.y.toLocaleString('id-ID')}`}}
            },
            scales:{
                y:{beginAtZero:false,grid:{color:'rgba(0,0,0,0.05)'},ticks:{font:{size:10},callback:(v)=>'Rp '+v.toLocaleString('id-ID')}},
                x:{grid:{display:false},ticks:{font:{size:9},maxRotation:45}}
            }
        }
    });
})();

// Price Status Doughnut
(function(){
    const ctx = document.getElementById('priceStatusChart');
    if(!ctx) return;
    new Chart(ctx,{
        type:'doughnut',
        data:{
            labels:['Aman','Rawan','Lonjakan','Kritis'],
            datasets:[{data:[{{ $statusSummary['aman'] }},{{ $statusSummary['rawan'] }},{{ $statusSummary['lonjakan'] }},{{ $statusSummary['kritis'] }}], backgroundColor:[GREEN,ORANGE,'#f97316',RED], borderWidth:0, spacing:3, borderRadius:5}]
        },
        options:{responsive:true, maintainAspectRatio:true, cutout:'62%', plugins:{legend:{position:'bottom',labels:{padding:10,usePointStyle:true,font:{size:11}}}}}
    });
})();
</script>
@endpush
