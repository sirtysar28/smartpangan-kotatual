@extends('layouts.admin')
@section('title', 'Kelola UMKM - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-shop me-2"></i>Kelola UMKM 🏪</h4>
        <p class="text-muted mb-0 small">Verifikasi dan kelola UMKM terdaftar</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge-modern" style="background:rgba(99,102,241,0.1);color:#6366f1">Total: {{ $stats['total'] }}</span>
        <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981">Verified: {{ $stats['verified'] }}</span>
        <span class="badge-modern" style="background:rgba(245,158,11,0.1);color:#f59e0b">Pending: {{ $stats['pending'] }}</span>
    </div>
</div>

<div class="card border-0 mb-3 animate-fade-up delay-1" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted">Cari UMKM</label>
                <input type="text" name="search" class="form-control" placeholder="Nama usaha..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="verified" {{ request('status')=='verified'?'selected':'' }}>Verified</option>
                    <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
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
            <thead><tr><th>UMKM</th><th>Pemilik</th><th>Telepon</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($umkms as $umkm)
            <tr>
                <td><a href="{{ route('admin.umkms.show',$umkm) }}" class="text-decoration-none fw-semibold" style="font-size:0.85rem">{{ $umkm->business_name }}</a></td>
                <td><small>{{ $umkm->user->name }}</small></td>
                <td><small>{{ $umkm->phone }}</small></td>
                <td>
                    @php
                        $us = $umkm->status;
                        if ($us === 'verified') { $usBg = 'rgba(16,185,129,0.1)'; $usColor = '#10b981'; }
                        elseif ($us === 'pending') { $usBg = 'rgba(245,158,11,0.1)'; $usColor = '#f59e0b'; }
                        else { $usBg = 'rgba(239,68,68,0.1)'; $usColor = '#ef4444'; }
                    @endphp
                    <span class="badge-modern" style="background:{{ $usBg }};color:{{ $usColor }}">{{ ucfirst($umkm->status) }}</span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        @if($umkm->status==='pending')
                            <form action="{{ route('admin.umkms.verify',$umkm) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm" style="background:rgba(16,185,129,0.1);color:#10b981;border-radius:8px" onclick="return confirm('Verifikasi?')"><i class="bi bi-check-lg"></i></button></form>
                            <button class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $umkm->id }}"><i class="bi bi-x-lg"></i></button>
                        @elseif($umkm->status==='verified')
                            <form action="{{ route('admin.umkms.suspend',$umkm) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm" style="background:rgba(245,158,11,0.1);color:#f59e0b;border-radius:8px" onclick="return confirm('Suspend?')"><i class="bi bi-pause"></i></button></form>
                        @endif
                        <form action="{{ route('admin.umkms.destroy',$umkm) }}" method="POST" class="d-inline" onclick="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px"><i class="bi bi-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            <div class="modal fade" id="rejectModal{{ $umkm->id }}"><div class="modal-dialog modal-sm"><div class="modal-content" style="border-radius:var(--radius)"><form action="{{ route('admin.umkms.reject',$umkm) }}" method="POST">@csrf
                <div class="modal-header"><h5 class="modal-title small">Tolak UMKM</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body"><textarea name="rejection_reason" class="form-control" rows="3" placeholder="Alasan penolakan..." required></textarea></div>
                <div class="modal-footer"><button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-sm" style="background:#ef4444;color:#fff;border-radius:8px">Tolak</button></div>
            </form></div></div></div>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($umkms->hasPages())
    <div class="p-3 d-flex justify-content-center">{{ $umkms->withQueryString()->links() }}</div>
    @endif
</div>
@endsection