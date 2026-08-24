@extends('layouts.admin')
@section('title', 'Laporan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-file-earmark-bar-graph me-2"></i>Export Laporan 📄</h4>
        <p class="text-muted mb-0 small">Download laporan dalam format PDF</p>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-6 animate-fade-up delay-1">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background:rgba(99,102,241,0.1)"><i class="bi bi-bag" style="color:#6366f1"></i></div>
                    <div><h6 class="fw-bold mb-0">Laporan Penjualan</h6><small class="text-muted">Data transaksi per periode</small></div>
                </div>
                <form action="{{ route('admin.reports.sales') }}" method="POST">@csrf
                    <div class="row g-2 mb-3">
                        <div class="col-6"><label class="form-label small">Dari</label><input type="date" name="start_date" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}" required></div>
                        <div class="col-6"><label class="form-label small">Sampai</label><input type="date" name="end_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required></div>
                    </div>
                    <button class="btn btn-primary-modern w-100"><i class="bi bi-download me-2"></i>Download PDF</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 animate-fade-up delay-2">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background:rgba(16,185,129,0.1)"><i class="bi bi-shop" style="color:#10b981"></i></div>
                    <div><h6 class="fw-bold mb-0">Laporan UMKM</h6><small class="text-muted">Data UMKM terverifikasi</small></div>
                </div>
                <form action="{{ route('admin.reports.umkm') }}" method="POST">@csrf
                    <button class="btn w-100" style="background:rgba(16,185,129,0.1);color:#10b981;border:none;border-radius:10px;font-weight:600"><i class="bi bi-download me-2"></i>Download PDF</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 animate-fade-up delay-3">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background:rgba(245,158,11,0.1)"><i class="bi bi-graph-up" style="color:#f59e0b"></i></div>
                    <div><h6 class="fw-bold mb-0">Laporan Komoditas</h6><small class="text-muted">Harga kebutuhan pokok & tren</small></div>
                </div>
                <form action="{{ route('admin.reports.commodity') }}" method="POST">@csrf
                    <button class="btn w-100" style="background:rgba(245,158,11,0.1);color:#f59e0b;border:none;border-radius:10px;font-weight:600"><i class="bi bi-download me-2"></i>Download PDF</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 animate-fade-up delay-4">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background:rgba(14,165,233,0.1)"><i class="bi bi-trophy" style="color:#0ea5e9"></i></div>
                    <div><h6 class="fw-bold mb-0">Produk Terlaris</h6><small class="text-muted">Top 50 produk terlaris</small></div>
                </div>
                <form action="{{ route('admin.reports.top-products') }}" method="POST">@csrf
                    <button class="btn w-100" style="background:rgba(14,165,233,0.1);color:#0ea5e9;border:none;border-radius:10px;font-weight:600"><i class="bi bi-download me-2"></i>Download PDF</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 animate-fade-up delay-4">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow);border-top:3px solid #a855f7">
            <div class="p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background:rgba(168,85,247,0.1)"><i class="bi bi-shield-check" style="color:#a855f7"></i></div>
                    <div><h6 class="fw-bold mb-0">Laporan Keamanan Pangan</h6><small class="text-muted">Hasil Uji Rapid Test &amp; Uji Laboratorium</small></div>
                </div>
                <form action="{{ route('admin.reports.food-safety') }}" method="POST">@csrf
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label small">Dari (opsional)</label><input type="date" name="start_date" class="form-control form-control-sm"></div>
                        <div class="col-6"><label class="form-label small">Sampai (opsional)</label><input type="date" name="end_date" class="form-control form-control-sm"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Jenis Laporan</label>
                        <select name="report_type" class="form-select form-select-sm">
                            <option value="all">Semua (Rapid Test + Uji Lab)</option>
                            <option value="rapid">Hanya Uji Rapid Test</option>
                            <option value="lab">Hanya Uji Laboratorium</option>
                        </select>
                    </div>
                    <button class="btn w-100" style="background:rgba(168,85,247,0.1);color:#a855f7;border:none;border-radius:10px;font-weight:600"><i class="bi bi-file-earmark-pdf me-2"></i>Download Laporan PDF</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection