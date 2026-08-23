@extends('layouts.umkm')
@section('title', 'Dashboard UMKM - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
@if(!$umkm || !$umkm->isVerified())
<div class="text-center py-5 animate-scale">
    <div style="width:100px;height:100px;background:linear-gradient(135deg,rgba(245,158,11,0.1),rgba(245,158,11,0.05));border-radius:24px;display:flex;align-items:center;justify-content:center;margin:0 auto">
        <i class="bi bi-clock display-4" style="color:#f59e0b"></i>
    </div>
    <h4 class="fw-bold mt-4">Menunggu Verifikasi</h4>
    <p class="text-muted">Pendaftaran UMKM Anda sedang menunggu verifikasi dari Pemerintah Daerah.</p>
    <span class="badge-modern" style="background:rgba(245,158,11,0.1);color:#f59e0b">{{ $umkm ? ucfirst($umkm->status) : 'Belum daftar' }}</span>
</div>
@else

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem">Dashboard UMKM 🏪</h4>
        <p class="text-muted mb-0 small">{{ $umkm->business_name }} — Ringkasan hari ini</p>
    </div>
    <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-check-circle me-1"></i>Terverifikasi</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 animate-fade-up delay-1">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#6366f1,#818cf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.78rem">Produk</small>
                    <div class="stat-value mt-1" style="color:#6366f1">{{ $totalProducts }}</div>
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
                    <small class="text-muted fw-medium" style="font-size:0.78rem">Hari Ini</small>
                    <div class="stat-value mt-1" style="color:#10b981;font-size:1.3rem">Rp {{ number_format($todayRevenue, 0, ', ','.') }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.1)"><i class="bi bi-cash" style="color:#10b981"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-3">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#0ea5e9,#38bdf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.78rem">Bulan Ini</small>
                    <div class="stat-value mt-1" style="color:#0ea5e9;font-size:1.3rem">Rp {{ number_format($monthRevenue, 0, ', ','.') }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(14,165,233,0.1)"><i class="bi bi-calendar-check" style="color:#0ea5e9"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-4">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#f59e0b,#fbbf24);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.78rem">Pesanan</small>
                    <div class="stat-value mt-1" style="color:#f59e0b">{{ $totalOrders }}</div>
                    <small class="text-muted">{{ $pendingOrders }} pending</small>
                </div>
                <div class="stat-icon" style="background:rgba(245,158,11,0.1)"><i class="bi bi-bag" style="color:#f59e0b"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6 animate-fade-up delay-4">
        <div class="card border-0 p-4" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-exclamation-triangle me-2" style="color:#ef4444"></i>Stok Menipis</h6>
                <a href="{{ route('umkm.products.index') }}" class="small text-decoration-none fw-semibold" style="color:var(--primary)">Kelola →</a>
            </div>
            @forelse($lowStock as $p)
            <div class="d-flex justify-content-between align-items-center mb-2 p-2" style="border-radius:10px;background:{{ $p->stock <= 5 ? 'rgba(239,68,68,0.06)' : 'rgba(245,158,11,0.06)' }}">
                <span class="fw-medium small">{{ $p->name }}</span>
                <span class="badge-modern" style="background:{{ $p->stock <= 5 ? 'rgba(239,68,68,0.1);color:#ef4444' : 'rgba(245,158,11,0.1);color:#f59e0b' }}">{{ $p->stock }} sisa</span>
            </div>
            @empty
            <div class="text-center py-3">
                <i class="bi bi-check-circle text-success fs-4"></i>
                <p class="text-muted small mb-0">Semua stok aman</p>
            </div>
            @endforelse
        </div>
    </div>
    <div class="col-lg-6 animate-fade-up delay-5">
        <div class="card border-0 p-4" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <h6 class="fw-bold mb-3"><i class="bi bi-trophy me-2" style="color:#f59e0b"></i>Produk Terlaris</h6>
            @foreach($topProducts as $p)
            <div class="d-flex justify-content-between align-items-center mb-2 p-2" style="border-radius:10px;background:#f8fafc">
                <span class="fw-medium small">{{ $p->name }}</span>
                <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981">{{ $p->total_sold }} terjual</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card border-0 mt-4 animate-fade-up delay-5" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-4 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2" style="color:#0ea5e9"></i>Pesanan Terbaru</h6>
            <a href="{{ route('umkm.orders.index') }}" class="small text-decoration-none fw-semibold" style="color:var(--primary)">Semua →</a>
        </div>
    </div>
    <div class="table-responsive px-2 pb-3">
        <table class="table table-modern mb-0">
            <thead><tr><th>No. Pesanan</th><th>Pembeli</th><th>Total</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
            @foreach($recentOrders as $o)
            <tr>
                <td class="fw-semibold small">{{ $o->order_number }}</td>
                <td class="small">{{ $o->user->name }}</td>
                <td class="fw-semibold small">Rp {{ number_format($o->total_amount, 0, ', ','.') }}</td>
                @php
                    $st = $o->status;
                    if ($st === 'delivered') { $stRgb = '16,185,129'; $stColor = '#10b981'; }
                    elseif ($st === 'pending') { $stRgb = '245,158,11'; $stColor = '#f59e0b'; }
                    elseif ($st === 'cancelled') { $stRgb = '239,68,68'; $stColor = '#ef4444'; }
                    else { $stRgb = '14,165,233'; $stColor = '#0ea5e9'; }
                @endphp
                <td><span class="badge-modern" style="background:rgba({{ $stRgb }},0.1);color:{{ $stColor }}">{{ $o->statusText }}</span></td>
                <td><small class="text-muted">{{ $o->created_at->format('d/m/Y H:i') }}</small></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
