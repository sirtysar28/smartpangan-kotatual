@extends('layouts.admin')

@section('title', 'Kelola Halaman - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-file-earmark-text me-2" style="color:var(--primary)"></i>Kelola Halaman</h4>
        <p class="text-muted mb-0 small">Kelola konten halaman profil dan informasi yang tampil di website</p>
    </div>
    <a href="{{ route('admin.site-pages.create') }}" class="btn btn-primary-modern">
        <i class="bi bi-plus-lg me-1"></i>Tambah Halaman
    </a>
</div>

<div class="card border-0 animate-fade-up delay-1" style="border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th>Halaman</th>
                        <th>Slug</th>
                        <th style="width:80px" class="text-center">Status</th>
                        <th style="width:80px" class="text-center">Urutan</th>
                        <th style="width:160px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($pages as $page)
                    <tr>
                        <td class="text-muted">{{ $no++ }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($page->image)
                                    <img src="{{ Storage::url($page->image) }}" alt="" style="width:36px;height:36px;border-radius:8px;object-fit:cover">
                                @else
                                    <span style="width:36px;height:36px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;background:rgba(99,102,241,0.1);color:var(--primary);font-size:0.9rem;flex-shrink:0">
                                        <i class="bi bi-{{ $page->icon ?? 'file-earmark-text' }}"></i>
                                    </span>
                                @endif
                                <div>
                                    <div class="fw-semibold" style="font-size:0.88rem">{{ $page->title }}</div>
                                    <small class="text-muted" style="font-size:0.7rem">{{ Str::limit($page->content, 60) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <code style="font-size:0.72rem;background:var(--bg);padding:2px 8px;border-radius:6px">{{ $page->slug }}</code>
                        </td>
                        <td class="text-center">
                            @if($page->is_active)
                                <span class="badge" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.72rem;border-radius:20px">
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge" style="background:rgba(239,68,68,0.1);color:#ef4444;font-size:0.72rem;border-radius:20px">
                                    <i class="bi bi-x-circle me-1"></i>Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="fw-bold" style="font-size:0.85rem">{{ $page->sort_order }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('site.page', $page->slug) }}" target="_blank" class="btn btn-sm" style="background:rgba(16,185,129,0.1);color:#10b981;border-radius:8px;font-size:0.75rem" title="Lihat di Website">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.site-pages.edit', $page) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:var(--primary);border-radius:8px;font-size:0.75rem" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.site-pages.destroy', $page) }}" onsubmit="return confirm('Hapus halaman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px;font-size:0.75rem" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-file-earmark-text display-4 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">Belum ada halaman</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
