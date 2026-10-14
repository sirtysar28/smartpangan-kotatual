@extends('layouts.admin')
@section('title', 'Master Parameter Uji - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem"><i class="bi bi-list-check me-2" style="color:var(--primary)"></i>Master Parameter Uji</h4>
        <p class="text-muted mb-0 small">Data master parameter uji laboratorium yang terhubung ke form Uji Laboratorium (Keamanan Pangan)</p>
    </div>
    <span class="badge-modern" style="background:rgba(99,102,241,0.1);color:#6366f1"><i class="bi bi-database me-1"></i>{{ $parameters->count() }} Parameter</span>
</div>

<div class="alert alert-info border-0 animate-fade-up" style="border-radius:var(--radius);font-size:0.8rem">
    <i class="bi bi-info-circle me-2"></i>
    Prioritaskan <strong>±8 parameter utama</strong> yang benar-benar dipakai. Parameter yang tidak dipakai bisa dinonaktifkan (bukan dihapus) agar riwayat uji lab lama tetap terbaca.
    Parameter pada form uji lab bersifat <strong>fleksibel</strong> — bisa dipilih dari data master, diisi manual, atau dikosongkan (mis. uji boraks tanpa parameter).
</div>

<div class="row g-4">
    {{-- Form tambah --}}
    <div class="col-12 col-xl-4 animate-fade-up">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2" style="color:var(--primary)"></i>Tambah Parameter Master</h6>
                <form action="{{ route('admin.master-parameters.store') }}" method="POST">@csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Parameter *</label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="cth: Residu Pestisida" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kelompok Uji <em class="text-muted fw-normal">(opsional)</em></label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">— Tanpa Kelompok —</option>
                            @foreach(\App\Models\LabTestParameter::CATEGORIES as $key => $cat)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $cat['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-semibold">Satuan</label>
                            <input type="text" name="unit" class="form-control form-control-sm" placeholder="ppm / ppb" value="{{ old('unit') }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-semibold">Baku Mutu</label>
                            <input type="text" name="standard" class="form-control form-control-sm" placeholder="SNI..." value="{{ old('standard') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Metode Uji</label>
                        <input type="text" name="method" class="form-control form-control-sm" placeholder="cth: HPLC, AAS" value="{{ old('method') }}">
                    </div>
                    <button type="submit" class="btn btn-primary-modern btn-sm rounded-pill px-4"><i class="bi bi-save me-1"></i>Simpan</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel daftar --}}
    <div class="col-12 col-xl-8 animate-fade-up delay-1">
        <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0" style="font-size:0.8rem">
                        <thead>
                            <tr>
                                <th style="width:36px">#</th>
                                <th>Nama Parameter</th>
                                <th>Kelompok Uji</th>
                                <th>Satuan</th>
                                <th>Baku Mutu</th>
                                <th>Status</th>
                                <th style="width:130px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parameters as $i => $m)
                            <tr>
                                <td class="text-muted">{{ $i+1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $m->name }}</div>
                                    @if($m->method)<small class="text-muted"><i class="bi bi-flask me-1"></i>{{ $m->method }}</small>@endif
                                </td>
                                <td>
                                    @if($m->category)
                                        <span class="badge" style="background:{{ $m->category_color }}18;color:{{ $m->category_color }};font-size:0.65rem">{{ $m->category_label }}</span>
                                    @else
                                        <small class="text-muted">—</small>
                                    @endif
                                </td>
                                <td><small>{{ $m->unit ?? '-' }}</small></td>
                                <td><small>{{ $m->standard ?? '-' }}</small></td>
                                <td>
                                    <span class="badge" style="background:{{ $m->is_active ? 'rgba(16,185,129,0.12)' : 'rgba(100,116,139,0.12)' }};color:{{ $m->is_active ? '#10b981' : '#64748b' }};font-size:0.65rem">
                                        <i class="bi bi-{{ $m->is_active ? 'check-circle-fill' : 'dash-circle' }} me-1"></i>{{ $m->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:var(--primary);border-radius:6px;font-size:0.7rem" title="Edit" data-bs-toggle="modal" data-bs-target="#editParam{{ $m->id }}"><i class="bi bi-pencil"></i></button>
                                        <form action="{{ route('admin.master-parameters.toggle', $m) }}" method="POST">@csrf
                                            <button type="submit" class="btn btn-sm" style="background:rgba(245,158,11,0.1);color:#f59e0b;border-radius:6px;font-size:0.7rem" title="{{ $m->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"><i class="bi bi-{{ $m->is_active ? 'pause' : 'play' }}-fill"></i></button>
                                        </form>
                                        <form action="{{ route('admin.master-parameters.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus parameter master ini?')">@csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:6px;font-size:0.7rem" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Edit --}}
                            <div class="modal fade" id="editParam{{ $m->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="border-radius:16px;border:none">
                                        <div class="modal-header border-0 pb-0">
                                            <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2" style="color:var(--primary)"></i>Edit Parameter Master</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.master-parameters.update', $m) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Nama Parameter *</label>
                                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ $m->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Kelompok Uji</label>
                                                    <select name="category" class="form-select form-select-sm">
                                                        <option value="">— Tanpa Kelompok —</option>
                                                        @foreach(\App\Models\LabTestParameter::CATEGORIES as $key => $cat)
                                                        <option value="{{ $key }}" {{ $m->category == $key ? 'selected' : '' }}>{{ $cat['label'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6 mb-3">
                                                        <label class="form-label small fw-semibold">Satuan</label>
                                                        <input type="text" name="unit" class="form-control form-control-sm" value="{{ $m->unit }}">
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label class="form-label small fw-semibold">Baku Mutu</label>
                                                        <input type="text" name="standard" class="form-control form-control-sm" value="{{ $m->standard }}">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Metode Uji</label>
                                                    <input type="text" name="method" class="form-control form-control-sm" value="{{ $m->method }}">
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active{{ $m->id }}" {{ $m->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="active{{ $m->id }}">Parameter aktif (tampil di form uji lab)</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-sm btn-primary-modern rounded-pill px-4"><i class="bi bi-save me-1"></i>Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div style="font-size:2rem;opacity:0.3;margin-bottom:0.5rem"><i class="bi bi-list-check"></i></div>
                                    <p class="text-muted mb-0">Belum ada data master parameter. Tambahkan di form sebelah kiri.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <p class="text-muted mt-2 mb-0" style="font-size:0.72rem"><i class="bi bi-clock-history me-1"></i>Data master final akan menyusul dari pihak Ketahanan Pangan (daftar parameter + mapping jenis komoditas).</p>
    </div>
</div>
@endsection
