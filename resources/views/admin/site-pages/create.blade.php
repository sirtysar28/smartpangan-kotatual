@extends('layouts.admin')

@section('title', 'Buat Halaman Baru - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-plus-circle me-2" style="color:var(--primary)"></i>Tambah Halaman Baru</h4>
        <p class="text-muted mb-0 small">Buat halaman baru untuk website</p>
    </div>
    <a href="{{ route('admin.site-pages.index') }}" class="btn btn-sm" style="background:var(--bg);border-radius:10px">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="card border-0 animate-fade-up delay-1" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.site-pages.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-md-8">
                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Judul Halaman <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Contoh: Visi & Misi Dinas Ketahanan Pangan" required>
                        @error('title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--bg);font-size:0.8rem;border-radius:var(--radius-xs) 0 0 var(--radius-xs)">{{ url('/halaman/') }}/</span>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="visi-misi (auto dari judul jika dikosongkan)">
                        </div>
                        <small class="text-muted">Kosongkan untuk auto-generate dari judul</small>
                        @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Konten</label>
                        <textarea name="content" class="form-control" rows="12" placeholder="Tulis konten halaman di sini...">{{ old('content') }}</textarea>
                        @error('content')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Image -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Gambar Header</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">JPG, PNG, WebP (max 2MB)</small>
                        @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Icon -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Icon Bootstrap</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', 'bi-info-circle') }}" placeholder="bi-building">
                        <small class="text-muted">
                            <a href="https://icons.getbootstrap.com/" target="_blank" style="color:var(--primary)">Lihat daftar icon</a>
                        </small>
                        @error('icon')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Color -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Warna Tema</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="color" name="color" value="{{ old('color', '#6366f1') }}" class="form-control form-control-color" style="width:50px;height:38px;padding:2px;border:none">
                            <input type="text" name="color" value="{{ old('color', '#6366f1') }}" class="form-control" style="flex:1">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" checked>
                            <label class="form-check-label fw-semibold" for="isActive" style="font-size:0.85rem">Aktif (tampil di website)</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid #e2e8f0">
                <button type="submit" class="btn btn-primary-modern">
                    <i class="bi bi-check-lg me-1"></i>Simpan Halaman
                </button>
                <a href="{{ route('admin.site-pages.index') }}" class="btn" style="background:var(--bg);border-radius:12px">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
