@extends('layouts.umkm')
@section('title', 'Tambah Produk - UMKM Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2"></i>Tambah Produk</h5>
<div class="card border-0 shadow-sm rounded-4 p-4">
<form action="{{ route('umkm.products.store') }}" method="POST" enctype="multipart/form-data">@csrf
    <div class="row g-3">
        <div class="col-md-8"><label class="form-label">Nama Produk *</label><input type="text" name="name" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">Kategori *</label><select name="category_id" class="form-select" required><option value="">Pilih</option>@foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
        <div class="col-12"><label class="form-label">Deskripsi *</label><textarea name="description" class="form-control" rows="4" required></textarea></div>
        <div class="col-md-4"><label class="form-label">Harga (Rp) *</label><input type="number" name="price" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">Harga Diskon</label><input type="number" name="discount_price" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">Stok *</label><input type="number" name="stock" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">Berat (gram) *</label><input type="number" name="weight" class="form-control" value="100" required></div>
        <div class="col-md-4"><label class="form-label">Satuan</label><input type="text" name="unit" class="form-control" value="pcs"></div>
        <div class="col-md-4"><label class="form-label">Foto Produk *</label><input type="file" name="image" class="form-control" accept="image/*" required></div>
    </div>
    <button class="btn btn-primary btn-lg rounded-pill mt-4"><i class="bi bi-save me-2"></i>Simpan Produk</button>
    <a href="{{ route('umkm.products.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill mt-4 ms-2">Batal</a>
</form>
</div>
@endsection
