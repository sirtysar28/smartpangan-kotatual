@extends('layouts.admin')
@section('title', 'Keamanan Pangan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem">🛡️ Keamanan Pangan</h4>
        <p class="text-muted mb-0 small">Input hasil Uji Rapid Test & Uji Laboratorium pangan di Kota Tual</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.food-safety.rapid.create') }}" class="btn" style="background:rgba(168,85,247,0.1);color:#a855f7;border-radius:10px;font-weight:600"><i class="bi bi-lightning-charge me-2"></i>Uji Rapid Test</a>
        <a href="{{ route('admin.food-safety.lab.create') }}" class="btn" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:10px;font-weight:600"><i class="bi bi-eyedropper me-2"></i>Uji Lab</a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 animate-fade-up delay-1">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#a855f7,#c084fc);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Total Uji Rapid Test</small>
                    <div class="stat-value mt-1" style="color:#a855f7">{{ $summary['total_rapid'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(168,85,247,0.1)"><i class="bi bi-lightning-charge" style="color:#a855f7"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-2">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#ef4444,#f87171);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Rapid Positif 🔴</small>
                    <div class="stat-value mt-1" style="color:#ef4444">{{ $summary['rapid_positif'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(239,68,68,0.1)"><i class="bi bi-x-circle" style="color:#ef4444"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-3">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#0ea5e9,#38bdf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Total Uji Lab</small>
                    <div class="stat-value mt-1" style="color:#0ea5e9">{{ $summary['total_lab'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(14,165,233,0.1)"><i class="bi bi-eyedropper" style="color:#0ea5e9"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-4">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#f59e0b,#fbbf24);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Uji Lab TMS ⚠️</small>
                    <div class="stat-value mt-1" style="color:#f59e0b">{{ $summary['lab_tms'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(245,158,11,0.1)"><i class="bi bi-exclamation-triangle" style="color:#f59e0b"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- TABS -->
<ul class="nav nav-tabs mb-4 animate-fade-up" id="foodSafetyTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="rapid-tab" data-bs-toggle="tab" data-bs-target="#rapidPanel" type="button" role="tab" style="font-weight:600;font-size:0.85rem">
            <i class="bi bi-lightning-charge me-1"></i>Uji Rapid Test
            <span class="badge ms-1" style="background:rgba(168,85,247,0.12);color:#a855f7;font-size:0.62rem">{{ $summary['total_rapid'] }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="lab-tab" data-bs-toggle="tab" data-bs-target="#labPanel" type="button" role="tab" style="font-weight:600;font-size:0.85rem">
            <i class="bi bi-eyedropper me-1"></i>Uji Laboratorium
            <span class="badge ms-1" style="background:rgba(239,68,68,0.12);color:#ef4444;font-size:0.62rem">{{ $summary['total_lab'] }}</span>
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB: Uji Rapid Test -->
    <div class="tab-pane fade show active" id="rapidPanel" role="tabpanel">
        <div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-lightning-charge me-2" style="color:#a855f7"></i>Rekap Uji Rapid Test</h6>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <span class="badge" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.68rem">Negatif: {{ $summary['rapid_negatif'] }}</span>
                        <span class="badge" style="background:rgba(239,68,68,0.1);color:#ef4444;font-size:0.68rem">Positif: {{ $summary['rapid_positif'] }}</span>
                        <form class="d-flex gap-2" method="GET">
                            <input type="hidden" name="tab" value="rapid">
                            <input type="text" name="rapid_search" class="form-control form-control-sm" placeholder="Cari komoditas/lokasi..." value="{{ request('rapid_search') }}" style="min-width:150px">
                            <button type="submit" class="btn btn-sm btn-primary-modern" style="border-radius:8px"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern" style="font-size:0.75rem">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Lokasi Sampel</th>
                                <th>Komoditas</th>
                                <th>Jenis Komoditas</th>
                                <th>Jenis Rapid Test</th>
                                <th>Hasil Uji</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rapidTests as $r)
                            <tr style="border-left:3px solid {{ $r->result_color }}">
                                <td>{{ $r->test_date->format('d/m/Y') }}</td>
                                <td>{{ $r->sample_location }}</td>
                                <td class="fw-semibold">{{ $r->commodity_name }}</td>
                                <td>{{ $r->commodity_type ?? '-' }}</td>
                                <td>
                                    <span class="badge" style="background:rgba(168,85,247,0.1);color:#a855f7;font-size:0.62rem;border-radius:6px"><i class="bi bi-lightning-charge me-1"></i>{{ $r->type_label }}</span>
                                </td>
                                <td>
                                    <span class="badge" style="background:{{ $r->result_color }}15;color:{{ $r->result_color }};font-size:0.65rem;border-radius:6px;font-weight:600">
                                        {{ $r->result_label }}
                                    </span>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($r->notes, 40) ?: '-' }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.food-safety.rapid.edit', $r) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:var(--primary);border-radius:6px;font-size:0.65rem"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.food-safety.rapid.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data uji rapid test ini?')">@csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:6px;font-size:0.65rem"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data uji rapid test</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">{{ $rapidTests->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>

    <!-- TAB: Uji Laboratorium -->
    <div class="tab-pane fade" id="labPanel" role="tabpanel">
        <div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-eyedropper me-2" style="color:#ef4444"></i>Rekap Uji Laboratorium</h6>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <span class="badge" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.68rem">MS: {{ $summary['total_lab'] - $summary['lab_tms'] }}</span>
                        <span class="badge" style="background:rgba(239,68,68,0.1);color:#ef4444;font-size:0.68rem">TMS: {{ $summary['lab_tms'] }}</span>
                        <form class="d-flex gap-2" method="GET">
                            <input type="hidden" name="tab" value="lab">
                            <input type="text" name="lab_search" class="form-control form-control-sm" placeholder="Cari komoditas/lab..." value="{{ request('lab_search') }}" style="min-width:150px">
                            <button type="submit" class="btn btn-sm btn-primary-modern" style="border-radius:8px"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern" style="font-size:0.75rem">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Lokasi Sampel</th>
                                <th>Komoditas</th>
                                <th>Jenis PSAT</th>
                                <th>Lab Uji</th>
                                <th>Parameter</th>
                                <th>Sertifikat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($labTests as $l)
                            <tr style="border-left:3px solid {{ $l->overall_status_color }}">
                                <td>{{ $l->test_date->format('d/m/Y') }}</td>
                                <td>{{ $l->sample_location }}</td>
                                <td class="fw-semibold">{{ $l->commodity_name }}</td>
                                <td>{{ $l->psat_type_label }}</td>
                                <td>{{ $l->lab_name }}</td>
                                <td><span class="badge" style="background:rgba(99,102,241,0.1);color:var(--primary);font-size:0.62rem;border-radius:6px">{{ $l->parameters_count }} parameter</span></td>
                                <td>
                                    @if($l->certificate_file)
                                        <a href="{{ $l->certificate_file_url }}" target="_blank" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:6px;font-size:0.65rem"><i class="bi bi-file-earmark-pdf me-1"></i>CoA</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background:{{ $l->overall_status_color }}15;color:{{ $l->overall_status_color }};font-size:0.65rem;border-radius:6px;font-weight:600">
                                        {{ $l->overall_status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.food-safety.lab.edit', $l) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:var(--primary);border-radius:6px;font-size:0.65rem"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.food-safety.lab.destroy', $l) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data uji lab ini?')">@csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:6px;font-size:0.65rem"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada data uji laboratorium</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">{{ $labTests->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>

</div>

<script>
    // Aktifkan tab berdasarkan query ?tab= (rapid|lab)
    document.addEventListener('DOMContentLoaded', function() {
        var tab = new URLSearchParams(window.location.search).get('tab');
        var map = { rapid: 'rapid-tab', lab: 'lab-tab' };
        if (tab && map[tab]) {
            var el = document.getElementById(map[tab]);
            if (el) { var t = new bootstrap.Tab(el); t.show(); }
        }
    });
</script>

@endsection
