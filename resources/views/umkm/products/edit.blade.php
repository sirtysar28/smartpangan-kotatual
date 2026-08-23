@extends('layouts.umkm')
@section('title', 'Edit Produk - UMKM Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-pencil me-2"></i>Edit Produk</h5>
<div class="card border-0 shadow-sm rounded-4 p-4">
<form action="{{ route('umkm.products.update',$product) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-8"><label class="form-label">Nama *</label><input type="text" name="name" class="form-control" value="{{ $product->name }}" required></div>
        <div class="col-md-4"><label class="form-label">Kategori *</label><select name="category_id" class="form-select" required>@foreach($categories as $c)<option value="{{ $c->id }}" {{ $product->category_id==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
        <div class="col-12"><label class="form-label">Deskripsi *</label><textarea name="description" class="form-control" rows="4" required>{{ $product->description }}</textarea></div>
        <div class="col-md-4"><label class="form-label">Harga (Rp) *</label><input type="number" name="price" class="form-control" value="{{ $product->price }}" required></div>
        <div class="col-md-4"><label class="form-label">Stok *</label><input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required></div>
        <div class="col-md-4"><label class="form-label">Berat (gram) *</label><input type="number" name="weight" class="form-control" value="{{ $product->weight }}" required></div>
        <div class="col-md-6"><label class="form-label">Ganti Foto</label><input type="file" name="image" class="form-control" accept="image/*"></div>
        <div class="col-md-6">@if($product->image)<label class="form-label">Foto Saat Ini</label><br><img src="{{ Storage::url($product->image) }}" width="100" class="rounded">@endif</div>
    </div>
    <button class="btn btn-primary btn-lg rounded-pill mt-4"><i class="bi bi-save me-2"></i>Update</button>
    <a href="{{ route('umkm.products.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill mt-4 ms-2">Batal</a>
</form>
</div>
@endsection
