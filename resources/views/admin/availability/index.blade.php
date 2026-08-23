@extends('layouts.admin')
@section('title', 'Ketersediaan Pangan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem">📦 Ketersediaan & Stok Pangan</h4>
        <p class="text-muted mb-0 small">Monitoring stok, estimasi ketahanan stok, dan kebutuhan per minggu di Kota Tual</p>
    </div>
    <a href="{{ route('admin.availability.create') }}" class="btn btn-primary-modern"><i class="bi bi-plus-lg me-2"></i>Input Stok</a>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 animate-fade-up delay-1">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#6366f1,#818cf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Total Komoditas</small>
                    <div class="stat-value mt-1" style="color:#6366f1">{{ $summary['total'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(99,102,241,0.1)"><i class="bi bi-box-seam" style="color:#6366f1"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-2">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#10b981,#34d399);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Stok Aman ✅</small>
                    <div class="stat-value mt-1" style="color:#10b981">{{ $summary['aman'] }}</div>
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
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Stok Rawan ⚠️</small>
                    <div class="stat-value mt-1" style="color:#f59e0b">{{ $summary['rawan'] }}</div>
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
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Stok Kritis 🔴</small>
                    <div class="stat-value mt-1" style="color:#ef4444">{{ $summary['kritis'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(239,68,68,0.1)"><i class="bi bi-shield-fill-x" style="color:#ef4444"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- TABEL KETERSEDIAAN PANGAN -->
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-table me-2" style="color:var(--primary)"></i>Tabel Ketersediaan Pangan</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-modern" style="font-size:0.78rem">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Komoditas</th>
                        <th>Jumlah Stok</th>
                        <th>Bertahan</th>
                        <th>Kebutuhan/Minggu</th>
                        <th>Status Pangan</th>
                        <th>Keterangan Stok</th>
                        <th>Tgl Pantauan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commodities as $i => $c)
                    @php
                        $ls = $c->latestStock;
                        $fs = $c->price_comparison['food_status'] ?? ['code'=>'aman','label'=>'Aman','color'=>'#10b981','icon'=>'shield-check'];
                    @endphp
                    <tr style="border-left:3px solid {{ $ls ? $ls->status_color : '#e2e8f0' }}">
                        <td>{{ $i+1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($c->image_url)
                                    <img src="{{ $c->image_url }}" alt="" style="width:26px;height:26px;border-radius:6px;object-fit:cover">
                                @endif
                                <span class="fw-semibold">{{ $c->name }}</span>
                            </div>
                        </td>
                        <td>{{ $ls ? number_format($ls->stock_quantity, 0, ',', '.') . ' ' . $ls->stock_unit : '-' }}</td>
                        <td>{{ $ls ? $ls->stock_duration_days . ' hari' : '-' }}</td>
                        <td>{{ $ls ? number_format($ls->weekly_need, 0, ',', '.') . ' ' . $ls->stock_unit : '-' }}</td>
                        <td>
                            <span class="badge" style="background:{{ $fs['color'] }}20;color:{{ $fs['color'] }};font-size:0.65rem;border-radius:6px;font-weight:700">
                                <i class="bi bi-{{ $fs['icon'] }} me-1"></i>{{ $fs['label'] }}
                            </span>
                        </td>
                        <td>
                            @if($ls)
                                <span class="badge" style="background:{{ $ls->status_color }}20;color:{{ $ls->status_color }};font-size:0.65rem;border-radius:6px;font-weight:600">
                                    {{ $ls->status_label }}
                                </span>
                            @else
                                <span class="text-muted">Belum ada data</span>
                            @endif
                        </td>
                        <td>{{ $ls ? $ls->monitoring_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($ls)
                                <a href="{{ route('admin.availability.edit', $ls) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:var(--primary);border-radius:6px;font-size:0.7rem"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.availability.destroy', $ls) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data stok?')">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:6px;font-size:0.7rem"><i class="bi bi-trash"></i></button>
                                </form>
                            @else
                                <a href="{{ route('admin.availability.create') }}" class="btn btn-sm" style="background:rgba(16,185,129,0.1);color:#10b981;border-radius:6px;font-size:0.7rem"><i class="bi bi-plus"></i></a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada data stok</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- GRAFIK KETERSEDIAAN -->
<div class="row g-4 mb-4">
    <div class="col-lg-8 animate-fade-up">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">📊 Grafik Stok vs Kebutuhan Mingguan</h6>
                    <small class="text-muted">Perbandingan stok tersedia vs kebutuhan per minggu</small>
                </div>
            </div>
            <div style="position:relative;height:320px">
                <canvas id="stockChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 animate-fade-up">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">🎯 Distribusi Status Stok</h6>
                    <small class="text-muted">Aman / Rawan / Kritis</small>
                </div>
            </div>
            <div style="position:relative;max-width:250px;margin:0 auto">
                <canvas id="stockStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const RED='#ef4444',GREEN='#10b981',BLUE='#0ea5e9',PURPLE='#6366f1',ORANGE='#f59e0b';

// Stock vs Need Chart
(function(){
    const ctx = document.getElementById('stockChart');
    if(!ctx) return;
    const data = @json($chartData);
    if(!data || data.length===0) return;

    new Chart(ctx, {
        type:'bar',
        data:{
            labels: data.map(d=>d.name),
            datasets:[
                {label:'Stok Tersedia', data:data.map(d=>d.stock), backgroundColor:'rgba(99,102,241,0.7)', borderRadius:6, barPercentage:0.6},
                {label:'Kebutuhan/Minggu', data:data.map(d=>d.weekly_need), backgroundColor:'rgba(245,158,11,0.7)', borderRadius:6, barPercentage:0.6},
            ]
        },
        options:{
            responsive:true, maintainAspectRatio:false,
            plugins:{legend:{position:'bottom',labels:{padding:12,usePointStyle:true,font:{size:11}}}},
            scales:{
                y:{beginAtZero:true,grid:{color:'rgba(0,0,0,0.05)'},ticks:{font:{size:10}}},
                x:{grid:{display:false},ticks:{font:{size:9},maxRotation:45}}
            }
        }
    });
})();

// Stock Status Doughnut
(function(){
    const ctx = document.getElementById('stockStatusChart');
    if(!ctx) return;
    new Chart(ctx,{
        type:'doughnut',
        data:{
            labels:['Aman ✅','Rawan ⚠️','Kritis 🔴'],
            datasets:[{data:[{{ $summary['aman'] }},{{ $summary['rawan'] }},{{ $summary['kritis'] }}], backgroundColor:[GREEN,ORANGE,RED], borderWidth:0, spacing:3, borderRadius:5}]
        },
        options:{responsive:true, maintainAspectRatio:true, cutout:'68%', plugins:{legend:{position:'bottom',labels:{padding:12,usePointStyle:true,font:{size:11}}}}}
    });
})();
</script>
@endsection
