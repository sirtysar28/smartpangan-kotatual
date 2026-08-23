@extends('layouts.app')
@section('title', 'Ketersediaan Pangan - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<!-- Header -->
<section class="py-4" style="background:linear-gradient(135deg,#0f172a,#064e3b)">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <nav aria-label="breadcrumb" style="font-size:0.72rem">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;margin:0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.5);text-decoration:none">Beranda</a></li>
                        <li class="breadcrumb-item active" style="color:#10b981">Ketersediaan Pangan</li>
                    </ol>
                </nav>
                <h4 class="fw-bold text-white mb-1"><i class="bi bi-boxes me-2"></i>Ketersediaan Pangan</h4>
                <small class="text-white-50">Monitoring stok dan pasokan pangan di Kota Tual, Provinsi Maluku</small>
            </div>
            <div class="d-flex gap-2">
                <span class="badge-modern" style="background:rgba(16,185,129,0.15);color:#10b981"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>{{ $stockStats['total'] }} Data Stok</span>
            </div>
        </div>
    </div>
</section>

<!-- Stats Cards -->
<section class="py-4" style="background:var(--bg-card)">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #10b981">
                    <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#10b981">{{ $stockStats['aman'] }}</div>
                        <small class="text-muted">Stok Aman (≥4 minggu)</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #f59e0b">
                    <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#f59e0b">{{ $stockStats['rawan'] }}</div>
                        <small class="text-muted">Stok Rawan (≥2 minggu)</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #ef4444">
                    <div class="stat-icon" style="background:rgba(239,68,68,0.1);color:#ef4444"><i class="bi bi-x-circle-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#ef4444">{{ $stockStats['kritis'] }}</div>
                        <small class="text-muted">Stok Kritis (<2 minggu)</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #6366f1">
                    <div class="stat-icon" style="background:rgba(99,102,241,0.1);color:#6366f1"><i class="bi bi-database"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:var(--primary)">{{ $stockStats['total'] }}</div>
                        <small class="text-muted">Total Monitoring</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chart -->
@if($stockStats['total'] > 0)
<section class="py-4" style="background:var(--bg)">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-5">
                <div class="card border-0 p-3" style="border-radius:var(--radius);box-shadow:var(--shadow-sm)">
                    <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart-fill me-2" style="color:var(--primary)"></i>Distribusi Status Stok</h6>
                    <div style="max-width:280px;margin:0 auto">
                        <canvas id="stockDoughnutChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card border-0 p-3" style="border-radius:var(--radius);box-shadow:var(--shadow-sm)">
                    <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart-fill me-2" style="color:var(--primary)"></i>Jumlah per Status</h6>
                    <canvas id="stockBarChart" style="max-height:280px"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Data Table -->
<section class="py-4" style="background:var(--bg-card)">
    <div class="container">
        <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden">
            <div class="card-header p-3" style="background:linear-gradient(135deg,rgba(16,185,129,0.06),rgba(14,165,233,0.04));border-bottom:1px solid rgba(16,185,129,0.08)">
                <h6 class="fw-bold mb-0" style="font-size:0.88rem"><i class="bi bi-table me-2" style="color:#10b981"></i>Data Stok Pangan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0" style="font-size:0.82rem">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Komoditas</th>
                                <th>Tanggal Monitoring</th>
                                <th>Jumlah Stok</th>
                                <th>Kebutuhan/Minggu</th>
                                <th>Durasi Stok</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stocks as $i => $stock)
                            @php
                                $sStatus = $stock->stock_status;
                                $sClr = match($sStatus) { 'aman' => '#10b981', 'rawan' => '#f59e0b', 'kritis' => '#ef4444', default => '#64748b' };
                                $sIcon = match($sStatus) { 'aman' => 'check-circle-fill', 'rawan' => 'exclamation-triangle-fill', 'kritis' => 'x-circle-fill', default => 'dash-circle' };
                                $sLabel = match($sStatus) { 'aman' => 'Aman', 'rawan' => 'Rawan', 'kritis' => 'Kritis', default => ucfirst($sStatus ?? '-') };
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $stocks->firstItem() + $i }}</td>
                                <td class="fw-semibold">{{ $stock->commodity?->name ?? '-' }}</td>
                                <td>{{ $stock->monitoring_date?->format('d/m/Y') }}</td>
                                <td>{{ number_format($stock->stock_quantity, 0, ',', '.') }} {{ $stock->stock_unit }}</td>
                                <td>{{ $stock->weekly_need ? number_format($stock->weekly_need, 0, ',', '.') . ' ' . $stock->stock_unit : '-' }}</td>
                                <td>
                                    @if($stock->stock_duration_days)
                                        <span class="fw-semibold">{{ $stock->stock_duration_days }} hari</span>
                                        <br><small class="text-muted">≈ {{ round($stock->stock_duration_days / 7, 1) }} minggu</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><span class="badge" style="background:{{ $sClr }}22;color:{{ $sClr }};font-size:0.7rem"><i class="bi bi-{{ $sIcon }} me-1"></i>{{ $sLabel }}</span></td>
                                <td><small class="text-muted">{{ $stock->notes ?? '-' }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div style="font-size:2rem;opacity:0.3;margin-bottom:0.5rem"><i class="bi bi-inbox"></i></div>
                                    <p class="text-muted mb-0">Belum ada data ketersediaan pangan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($stocks->hasPages())
            <div class="card-footer p-3" style="background:var(--bg-card)">
                {{ $stocks->links() }}
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="py-4" style="background:var(--bg)">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4" style="border-radius:var(--radius);background:rgba(16,185,129,0.04);border:1px solid rgba(16,185,129,0.1)">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="width:36px;height:36px;border-radius:10px;background:#10b98122;color:#10b981;display:flex;align-items:center;justify-content:center"><i class="bi bi-check-circle-fill"></i></span>
                        <h6 class="fw-bold mb-0" style="color:#10b981">Stok Aman</h6>
                    </div>
                    <p class="text-muted small mb-0">Persediaan pangan mencukupi kebutuhan minimal 4 minggu ke depan. Tidak diperlukan tindakan khusus.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4" style="border-radius:var(--radius);background:rgba(245,158,11,0.04);border:1px solid rgba(245,158,11,0.1)">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="width:36px;height:36px;border-radius:10px;background:#f59e0b22;color:#f59e0b;display:flex;align-items:center;justify-content:center"><i class="bi bi-exclamation-triangle-fill"></i></span>
                        <h6 class="fw-bold mb-0" style="color:#f59e0b">Stok Rawan</h6>
                    </div>
                    <p class="text-muted small mb-0">Persediaan pangan hanya mencukupi 2-4 minggu. Perlu perhatian dan pengawasan intensif.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4" style="border-radius:var(--radius);background:rgba(239,68,68,0.04);border:1px solid rgba(239,68,68,0.1)">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="width:36px;height:36px;border-radius:10px;background:#ef444422;color:#ef4444;display:flex;align-items:center;justify-content:center"><i class="bi bi-x-circle-fill"></i></span>
                        <h6 class="fw-bold mb-0" style="color:#ef4444">Stok Kritis</h6>
                    </div>
                    <p class="text-muted small mb-0">Persediaan pangan kurang dari 2 minggu. Diperlukan tindakan darurat seperti impor atau distribusi cadangan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
@if($stockStats['total'] > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
(function() {
    // Doughnut Chart
    const doughnutCtx = document.getElementById('stockDoughnutChart');
    if (doughnutCtx) {
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    data: @json($chartData),
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0,
                    spacing: 3,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyleWidth: 8, font: { size: 12, family: 'Plus Jakarta Sans' } } }
                }
            }
        });
    }

    // Bar Chart
    const barCtx = document.getElementById('stockBarChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Jumlah Komoditas',
                    data: @json($chartData),
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 50,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans' } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans' } } }
                },
                plugins: { legend: { display: false } }
            }
        });
    }
})();
</script>
@endif
@endsection
