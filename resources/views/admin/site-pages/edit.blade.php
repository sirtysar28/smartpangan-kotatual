@extends('layouts.admin')

@section('title', 'Edit Halaman - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-pencil me-2" style="color:var(--primary)"></i>Edit Halaman: {{ $sitePage->title }}</h4>
        <p class="text-muted mb-0 small">URL: <a href="{{ route('site.page', $sitePage->slug) }}" target="_blank" style="color:var(--primary)">{{ url('/halaman/') }}/{{ $sitePage->slug }}</a></p>
    </div>
    <a href="{{ route('admin.site-pages.index') }}" class="btn btn-sm" style="background:var(--bg);border-radius:10px">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="card border-0 animate-fade-up delay-1" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.site-pages.update', $sitePage) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row g-4">
                <div class="col-md-8">
                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Judul Halaman <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $sitePage->title) }}" required>
                        @error('title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--bg);font-size:0.8rem;border-radius:var(--radius-xs) 0 0 var(--radius-xs)">{{ url('/halaman/') }}/</span>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $sitePage->slug) }}">
                        </div>
                        <small class="text-muted">Kosongkan untuk auto-generate dari judul</small>
                        @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Konten</label>
                        <textarea name="content" class="form-control" rows="16" placeholder="Tulis konten halaman di sini...">{{ old('content', $sitePage->content) }}</textarea>
                        @error('content')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Image -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Gambar Header</label>
                        @if($sitePage->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($sitePage->image) }}" alt="" style="width:100%;max-height:200px;object-fit:cover;border-radius:var(--radius-xs)">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                        @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Icon -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Icon Bootstrap</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', $sitePage->icon) }}" placeholder="bi-building">
                        <small class="text-muted">
                            <a href="https://icons.getbootstrap.com/" target="_blank" style="color:var(--primary)">Lihat daftar icon</a>
                        </small>
                        @error('icon')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Color -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Warna Tema</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="color" name="color" value="{{ old('color', $sitePage->color ?? '#6366f1') }}" class="form-control form-control-color" style="width:50px;height:38px;padding:2px;border:none" id="colorPicker">
                            <input type="text" name="color" value="{{ old('color', $sitePage->color ?? '#6366f1') }}" class="form-control" style="flex:1" id="colorText">
                        </div>
                    </div>

                    <!-- Sort Order -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem">Urutan Tampil</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $sitePage->sort_order) }}" min="0">
                        <small class="text-muted">Angka kecil = tampil lebih dulu</small>
                        @error('sort_order')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ $sitePage->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isActive" style="font-size:0.85rem">Aktif (tampil di website)</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid #e2e8f0">
                <button type="submit" class="btn btn-primary-modern">
                    <i class="bi bi-check-lg me-1"></i>Update Halaman
                </button>
                <a href="{{ route('admin.site-pages.index') }}" class="btn" style="background:var(--bg);border-radius:12px">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('colorPicker')?.addEventListener('input', function() {
    document.getElementById('colorText').value = this.value;
});
document.getElementById('colorText')?.addEventListener('input', function() {
    if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
        document.getElementById('colorPicker').value = this.value;
    }
});
</script>
@endpush
