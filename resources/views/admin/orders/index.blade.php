@extends('layouts.admin')
@section('title', 'Pesanan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-bag me-2"></i>Semua Pesanan 📦</h4>
        <p class="text-muted mb-0 small">Kelola semua pesanan marketplace</p>
    </div>
</div>

<div class="card border-0 mb-3 animate-fade-up delay-1" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="No. pesanan, pembeli..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option><option value="paid">Paid</option><option value="processing">Processing</option>
                    <option value="shipped">Shipped</option><option value="delivered">Delivered</option><option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button class="btn btn-primary-modern"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 animate-fade-up delay-2" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead><tr><th>No. Pesanan</th><th>Pembeli</th><th>UMKM</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($orders as $o)
            @php
                $st = $o->status;
                if ($st === 'delivered') { $stBg = 'rgba(16,185,129,0.1)'; $stColor = '#10b981'; }
                elseif ($st === 'cancelled') { $stBg = 'rgba(239,68,68,0.1)'; $stColor = '#ef4444'; }
                elseif ($st === 'pending') { $stBg = 'rgba(245,158,11,0.1)'; $stColor = '#f59e0b'; }
                else { $stBg = 'rgba(14,165,233,0.1)'; $stColor = '#0ea5e9'; }
            @endphp
            <tr>
                <td class="fw-semibold" style="font-size:0.85rem">{{ $o->order_number }}</td>
                <td><small>{{ $o->user->name }}</small></td>
                <td><small>{{ $o->umkm->business_name }}</small></td>
                <td class="fw-bold" style="font-size:0.85rem">Rp {{ number_format($o->total_amount,  0,  ', ','.') }}</td>
                <td><span class="badge-modern" style="background:{{ $stBg }};color:{{ $stColor }}">{{ $o->statusText }}</span></td>
                <td><small class="text-muted">{{ $o->created_at->format('d/m/Y') }}</small></td>
                <td><a href="{{ route('admin.orders.show',$o) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:#6366f1;border-radius:8px;font-size:0.75rem">Detail</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="p-3 d-flex justify-content-center">{{ $orders->withQueryString()->links() }}</div>
    @endif
</div>
@endsection