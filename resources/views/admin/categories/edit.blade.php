@extends('layouts.admin')
@section('title', 'Edit Kategori - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-pencil me-2"></i>Edit Kategori</h5>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:500px">
<form action="{{ route('admin.categories.update',$category) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    <div class="mb-3"><label class="form-label">Nama *</label><input type="text" name="name" class="form-control" value="{{ $category->name }}" required></div>
    <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea></div>
    <div class="mb-3"><label class="form-label">Gambar</label><input type="file" name="image" class="form-control" accept="image/*"></div>
    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" {{ $category->is_active?'checked':'' }}><label class="form-check-label" for="active">Aktif</label></div>
    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>Update</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>
</div>
@endsection
