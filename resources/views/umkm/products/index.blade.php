@extends('layouts.umkm')
@section('title', 'Produk Saya - UMKM Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold"><i class="bi bi-box me-2"></i>Produk Saya</h5>
    <a href="{{ route('umkm.products.create') }}" class="btn btn-primary rounded-pill"><i class="bi bi-plus-lg me-1"></i>Tambah Produk</a>
</div>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light"><tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Terjual</th><th>Aksi</th></tr></thead>
        <tbody>
        @foreach($products as $p)
        <tr>
            <td><img src="{{ $p->image ? Storage::url($p->image) : 'https://placehold.co/50x50/eee/999' }}" width="50" height="50" class="rounded"></td>
            <td class="fw-bold">{{ $p->name }}</td>
            <td>{{ $p->category->name }}</td>
            <td>Rp {{ number_format($p->price, 0, ', ','.') }}</td>
            <td><span class="badge bg-{{ $p->stock>20?'success':($p->stock>0?'warning':'danger') }}">{{ $p->stock }}</span></td>
            <td>{{ $p->total_sold }}</td>
            <td>
                <a href="{{ route('umkm.products.edit',$p) }}" class="btn btn-sm btn-outline-primary rounded-pill"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('umkm.products.destroy',$p) }}" method="POST" class="d-inline" onclick="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $products->withQueryString()->links() }}
@endsection
