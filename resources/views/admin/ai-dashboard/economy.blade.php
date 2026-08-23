@extends('layouts.admin')
@section('title', 'Dashboard Ekonomi - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-cash-stack me-2"></i>Dashboard Pertumbuhan Ekonomi 💰</h4>
        <p class="text-muted mb-0 small">Analisis pertumbuhan ekonomi UMKM Kota Tual</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 animate-fade-up delay-1">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#10b981,#34d399);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(16,185,129,0.1)"><i class="bi bi-cash" style="color:#10b981"></i></div>
                <div><small class="text-muted">Transaksi Hari Ini</small><div class="fw-bold fs-5">Rp {{ number_format($todayTx,  0,  ', ','.') }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-2">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#6366f1,#818cf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(99,102,241,0.1)"><i class="bi bi-calendar" style="color:#6366f1"></i></div>
                <div><small class="text-muted">Bulan Berjalan</small><div class="fw-bold fs-5">Rp {{ number_format($monthTx,  0,  ', ','.') }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-3">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#f59e0b,#fbbf24);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(245,158,11,0.1)"><i class="bi bi-shop" style="color:#f59e0b"></i></div>
                <div><small class="text-muted">UMKM Aktif</small><div class="fw-bold fs-5">{{ $totalActiveUmkm }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-4">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#0ea5e9,#38bdf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(14,165,233,0.1)"><i class="bi bi-people" style="color:#0ea5e9"></i></div>
                <div><small class="text-muted">UMKM Transaksi</small><div class="fw-bold fs-5">{{ $umkmTransacting }}</div></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-bar-chart me-2" style="color:var(--primary)"></i>Top Kategori Penjualan</h6>
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead><tr><th>#</th><th>Kategori</th><th>Total Terjual</th></tr></thead>
                <tbody>
                @foreach($topCategories as $i=>$c)
                <tr>
                    <td><span class="badge-modern" style="background:{{ $i < 3 ? 'linear-gradient(135deg,#f59e0b,#f97316);color:#fff' : 'rgba(100,116,139,0.1);color:#64748b' }}">{{ $i+1 }}</span></td>
                    <td class="fw-semibold">{{ $c->name }}</td>
                    <td><span class="badge-modern" style="background:rgba(99,102,241,0.1);color:#6366f1">{{ $c->total_sold }}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection