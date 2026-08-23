@extends('layouts.admin')
@section('title', 'FAQ Perizinan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-patch-question me-2" style="color:#8b5cf6"></i>FAQ Perizinan</h4>
        <p class="text-muted mb-0 small">Kelola konten FAQ NIB & PSAT-PDUK yang tampil di halaman Perizinan</p>
    </div>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary-modern"><i class="bi bi-plus-lg me-2"></i>Tambah FAQ</a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3 animate-fade-up delay-1">
        <div class="stat-card card p-3">
            <div class="d-flex align-items-start justify-content-between">
                <div><small class="text-muted fw-medium" style="font-size:0.75rem">Total FAQ</small><div class="stat-value mt-1" style="color:#6366f1">{{ $stats['total'] }}</div></div>
                <div class="stat-icon" style="background:rgba(99,102,241,0.1)"><i class="bi bi-patch-question" style="color:#6366f1"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 animate-fade-up delay-2">
        <div class="stat-card card p-3">
            <div class="d-flex align-items-start justify-content-between">
                <div><small class="text-muted fw-medium" style="font-size:0.75rem">FAQ NIB</small><div class="stat-value mt-1" style="color:#8b5cf6">{{ $stats['nib'] }}</div></div>
                <div class="stat-icon" style="background:rgba(139,92,246,0.1)"><i class="bi bi-card-checklist" style="color:#8b5cf6"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 animate-fade-up delay-3">
        <div class="stat-card card p-3">
            <div class="d-flex align-items-start justify-content-between">
                <div><small class="text-muted fw-medium" style="font-size:0.75rem">FAQ PSAT-PDUK</small><div class="stat-value mt-1" style="color:#0ea5e9">{{ $stats['psat'] }}</div></div>
                <div class="stat-icon" style="background:rgba(14,165,233,0.1)"><i class="bi bi-leaf" style="color:#0ea5e9"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 animate-fade-up delay-4">
        <div class="stat-card card p-3">
            <div class="d-flex align-items-start justify-content-between">
                <div><small class="text-muted fw-medium" style="font-size:0.75rem">Aktif</small><div class="stat-value mt-1" style="color:#10b981">{{ $stats['active'] }}</div></div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.1)"><i class="bi bi-check-circle" style="color:#10b981"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card border-0 mb-3 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow-sm)">
    <div class="p-3">
        <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
            <div class="input-group" style="max-width:280px">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari pertanyaan..." value="{{ request('search') }}">
            </div>
            <select name="category" class="form-select" style="max-width:180px" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="nib" @selected(request('category')==='nib')>NIB</option>
                <option value="psat_pduk" @selected(request('category')==='psat_pduk')>PSAT-PDUK</option>
            </select>
            <button class="btn btn-primary-modern" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
            <a href="{{ route('frontend.permits') }}" target="_blank" class="btn ms-auto" style="background:rgba(14,165,233,0.1);color:#0ea5e9"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat di Frontend</a>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card border-0 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="table-responsive">
        <table class="table table-modern mb-0" style="font-size:0.82rem">
            <thead>
                <tr><th style="width:50px">#</th><th>Kategori</th><th>Pertanyaan</th><th>Jawaban</th><th>Status</th><th style="width:120px">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($faqs as $i => $f)
                <tr>
                    <td class="text-muted">{{ $faqs->firstItem() + $i }}</td>
                    <td>
                        <span class="badge" style="background:{{ $f->category_color }}22;color:{{ $f->category_color }};font-size:0.65rem;border-radius:6px;font-weight:600">
                            <i class="bi bi-{{ $f->category_icon }} me-1"></i>{{ $f->category_label }}
                        </span>
                    </td>
                    <td class="fw-semibold">{{ Str::limit($f->question, 70) }}</td>
                    <td><small class="text-muted">{{ Str::limit($f->answer, 80) }}</small>@if($f->items) <span class="badge-modern ms-1" style="background:rgba(100,116,139,0.1);color:#64748b;font-size:0.6rem"><i class="bi bi-list-ul me-1"></i>{{ count($f->items) }} poin</span>@endif</td>
                    <td>
                        @if($f->is_active)
                            <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                        @else
                            <span class="badge-modern" style="background:rgba(100,116,139,0.1);color:#64748b"><i class="bi bi-pause-circle me-1"></i>Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.faqs.edit', $f) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:#6366f1;border-radius:8px" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.faqs.destroy', $f) }}" method="POST" class="d-inline" onclick="return confirm('Hapus FAQ ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-5">
                    <i class="bi bi-patch-question" style="font-size:2rem;opacity:0.3"></i>
                    <p class="text-muted mt-2 mb-0">Belum ada FAQ. <a href="{{ route('admin.faqs.create') }}">Tambah FAQ pertama</a></p>
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($faqs->hasPages())
    <div class="p-3 d-flex justify-content-center">{{ $faqs->links() }}</div>
    @endif
</div>

@endsection
