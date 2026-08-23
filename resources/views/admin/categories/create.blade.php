@extends('layouts.admin')
@section('title', 'Tambah Kategori - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2"></i>Tambah Kategori</h5>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:500px">
<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">@csrf
    <div class="mb-3"><label class="form-label">Nama Kategori *</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Icon (Bootstrap Icons class)</label><input type="text" name="icon" class="form-control" placeholder="bi-tag"></div>
    <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="3"></textarea></div>
    <div class="mb-3"><label class="form-label">Gambar</label><input type="file" name="image" class="form-control" accept="image/*"></div>
    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>Simpan</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>
</div>
@endsection
