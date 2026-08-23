@extends('layouts.admin')
@section('title', 'Kategori - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-grid me-2"></i>Kategori Produk 🏷️</h4>
        <p class="text-muted mb-0 small">Kelola kategori produk marketplace</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary-modern"><i class="bi bi-plus-lg me-2"></i>Tambah</a>
</div>

<div class="card border-0 animate-fade-up delay-1" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead><tr><th>Nama</th><th>Slug</th><th>Produk</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($categories as $c)
            <tr>
                <td><i class="bi {{ $c->icon ?? 'bi-tag' }} me-2" style="color:var(--primary)"></i><span class="fw-semibold">{{ $c->name }}</span></td>
                <td><code style="font-size:0.8rem;background:var(--bg);padding:2px 6px;border-radius:4px">{{ $c->slug }}</code></td>
                <td><span class="badge-modern" style="background:rgba(100,116,139,0.1);color:#64748b">{{ $c->products_count }}</span></td>
                <td><span class="badge-modern" style="background:{{ $c->is_active?'rgba(16,185,129,0.1);color:#10b981':'rgba(100,116,139,0.1);color:#64748b' }}">{{ $c->is_active?'Aktif':'Nonaktif' }}</span></td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.categories.edit',$c) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:#6366f1;border-radius:8px"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.categories.destroy',$c) }}" method="POST" class="d-inline" onclick="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px"><i class="bi bi-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="p-3 d-flex justify-content-center">{{ $categories->withQueryString()->links() }}</div>
    @endif
</div>
@endsection