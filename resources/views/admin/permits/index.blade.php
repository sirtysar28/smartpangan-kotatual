@extends('layouts.admin')
@section('title', 'Laporan Perizinan Pangan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem"><i class="bi bi-file-earmark-text me-2" style="color:var(--primary)"></i>Laporan Perizinan Pangan</h4>
        <p class="text-muted mb-0 small">Daftar/Rekap data perizinan pangan (PDUK, BPOM, Halal, SP-IRT) — pemilik usaha, alamat &amp; dokumen perizinan</p>
        <p class="mb-0 mt-1" style="font-size:0.72rem;color:#0ea5e9"><i class="bi bi-info-circle me-1"></i>Modul ini bersifat <strong>laporan data</strong>, bukan penerbitan izin — perizinan tetap dikelola melalui sistem satu pintu (revisi 14 Okt 2026)</p>
    </div>
    <a href="{{ route('admin.permits.create') }}" class="btn btn-primary-modern"><i class="bi bi-plus-lg me-2"></i>Tambah Data Perizinan</a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4 animate-fade-up">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left:4px solid #6366f1">
            <div class="stat-icon" style="background:rgba(99,102,241,0.1);color:#6366f1"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <div class="fw-bold" style="font-size:1.4rem">{{ $stats['total'] }}</div>
                <small class="text-muted">Total</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left:4px solid #f59e0b">
            <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="fw-bold" style="font-size:1.4rem;color:#f59e0b">{{ $stats['pending'] }}</div>
                <small class="text-muted">Menunggu</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left:4px solid #10b981">
            <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-patch-check-fill"></i></div>
            <div>
                <div class="fw-bold" style="font-size:1.4rem;color:#10b981">{{ $stats['approved'] }}</div>
                <small class="text-muted">Disetujui</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left:4px solid #ef4444">
            <div class="stat-icon" style="background:rgba(239,68,68,0.1);color:#ef4444"><i class="bi bi-x-circle-fill"></i></div>
            <div>
                <div class="fw-bold" style="font-size:1.4rem;color:#ef4444">{{ $stats['rejected'] }}</div>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <form method="GET" action="{{ route('admin.permits.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Cari Nama Usaha / NIB</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama usaha / NIB / no. perizinan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Jenis Perizinan</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="pduk" {{ request('type') == 'pduk' ? 'selected' : '' }}>PDUK</option>
                    <option value="bpom" {{ request('type') == 'bpom' ? 'selected' : '' }}>BPOM</option>
                    <option value="halal" {{ request('type') == 'halal' ? 'selected' : '' }}>Halal</option>
                    <option value="sp_irt" {{ request('type') == 'sp_irt' ? 'selected' : '' }}>SP-IRT</option>
                    <option value="lainnya" {{ request('type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Kecamatan</label>
                <select name="kecamatan" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(array_keys(\App\Support\TualWilayah::kecamatan()) as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>Kec. {{ $kec }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary-modern" style="flex:1"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.permits.index') }}" class="btn btn-sm" style="background:rgba(99,102,241,0.08);color:var(--primary);border-radius:8px"><i class="bi bi-x-lg"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card border-0 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0" style="font-size:0.8rem">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Nama Usaha</th>
                        <th>Nama Pelaku Usaha</th>
                        <th>Nomor Izin Berusaha (NIB)</th>
                        <th>Jenis Perizinan</th>
                        <th>Tanggal Terbit</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th style="width:130px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permits as $i => $p)
                    @php
                        $st = $p->permit_status;
                        if ($st === 'approved' && $p->expiry_date && $p->expiry_date->lt(\Illuminate\Support\Carbon::today())) $st = 'expired';
                        $pClr = match($st) { 'pending' => '#f59e0b', 'approved' => '#10b981', 'rejected' => '#ef4444', 'expired' => '#64748b', default => '#64748b' };
                        $pIcon = match($st) { 'pending' => 'hourglass-split', 'approved' => 'patch-check-fill', 'rejected' => 'x-circle-fill', 'expired' => 'clock-history', default => 'dash-circle' };
                        $pLabel = match($st) { 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'expired' => 'Kadaluarsa', default => ucfirst($st ?? '-') };
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $permits->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold">{{ $p->business_name }}</div>
                            @if($p->permit_number)
                                <small class="text-muted" style="font-size:0.65rem"><i class="bi bi-tag me-1"></i>{{ $p->permit_number }}</small>
                            @endif
                            @if($p->kecamatan || $p->desa)
                                <br><small class="text-muted" style="font-size:0.65rem"><i class="bi bi-geo-alt me-1"></i>{{ trim(($p->kecamatan ? 'Kec. '.$p->kecamatan : '').($p->desa ? ', '.$p->desa : ''), ', ') }}</small>
                            @endif
                        </td>
                        <td>
                            <small class="fw-semibold">{{ $p->applicant_name }}</small>
                            @if($p->applicant_phone)
                                <br><small class="text-muted" style="font-size:0.65rem">{{ $p->applicant_phone }}</small>
                            @endif
                        </td>
                        <td>
                            @if($p->nib)
                                <span class="badge" style="background:rgba(99,102,241,0.08);color:var(--primary);font-size:0.68rem;font-family:monospace">{{ $p->nib }}</span>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                            @if($p->nib_document_url)
                                <br><a href="{{ $p->nib_document_url }}" target="_blank" class="small" style="color:var(--primary);text-decoration:none;font-size:0.65rem"><i class="bi bi-file-earmark-arrow-up me-1"></i>Dok. NIB</a>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background:rgba(99,102,241,0.08);color:var(--primary);font-size:0.65rem">{{ $p->permit_type_label }}</span>
                            <br><span class="badge" style="background:{{ $pClr }}18;color:{{ $pClr }};font-size:0.62rem;border-radius:8px;font-weight:600"><i class="bi bi-{{ $pIcon }} me-1"></i>{{ $pLabel }}</span>
                        </td>
                        <td><small>{{ $p->issue_date ? $p->issue_date->format('d/m/Y') : '-' }}</small></td>
                        <td>
                            @if($p->expiry_date)
                                <small class="{{ $st === 'expired' ? 'text-danger fw-bold' : '' }}">{{ $p->expiry_date->format('d/m/Y') }}</small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.permits.edit', $p) }}" class="btn btn-sm btn-primary-modern" style="border-radius:6px;font-size:0.7rem;padding:0.25rem 0.6rem" title="Edit"><i class="bi bi-pencil me-1"></i>Edit</a>
                                <form action="{{ route('admin.permits.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus data perizinan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.08);color:#ef4444;border-radius:6px;font-size:0.7rem" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div style="font-size:2rem;opacity:0.3;margin-bottom:0.5rem"><i class="bi bi-file-earmark-text"></i></div>
                            <p class="text-muted mb-0">Belum ada data perizinan.</p>
                            <a href="{{ route('admin.permits.create') }}" class="btn btn-sm btn-primary-modern mt-2"><i class="bi bi-plus-lg me-1"></i>Tambah Data Perizinan</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($permits->hasPages())
    <div class="card-footer p-3" style="background:var(--bg-card)">
        {{ $permits->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
