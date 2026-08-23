@extends('layouts.admin')
@section('title', 'Kegiatan Keamanan Pangan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-clipboard-plus me-2"></i>Input Kegiatan Keamanan Pangan</h5>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:800px">
<form action="{{ route('admin.food-safety.activity.store') }}" method="POST" enctype="multipart/form-data">@csrf
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Judul Kegiatan *</label><input type="text" name="title" class="form-control" required placeholder="Judul kegiatan" value="{{ old('title') }}"></div>
        </div>
        <div class="col-md-3">
            <div class="mb-3"><label class="form-label">Tanggal *</label><input type="date" name="activity_date" class="form-control" required value="{{ old('activity_date', today()->format('Y-m-d')) }}"></div>
        </div>
        <div class="col-md-3">
            <div class="mb-3"><label class="form-label">Jenis Kegiatan *</label>
                <select name="activity_type" class="form-select" required>
                    <option value="pengawasan" {{ old('activity_type') === 'pengawasan' ? 'selected' : '' }}>Pengawasan</option>
                    <option value="sosialisasi" {{ old('activity_type') === 'sosialisasi' ? 'selected' : '' }}>Sosialisasi</option>
                    <option value="pelatihan" {{ old('activity_type') === 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                    <option value="pengambilan_sampel" {{ old('activity_type') === 'pengambilan_sampel' ? 'selected' : '' }}>Pengambilan Sampel</option>
                    <option value="penyuluhan" {{ old('activity_type') === 'penyuluhan' ? 'selected' : '' }}>Penyuluhan</option>
                    <option value="sidak" {{ old('activity_type') === 'sidak' ? 'selected' : '' }}>SIDAK</option>
                    <option value="rapat_koordinasi" {{ old('activity_type') === 'rapat_koordinasi' ? 'selected' : '' }}>Rapat Koordinasi</option>
                    <option value="lainnya" {{ old('activity_type') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">Lokasi</label><input type="text" name="location" class="form-control" placeholder="Lokasi kegiatan" value="{{ old('location') }}"></div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Jumlah Peserta</label><input type="number" name="participants_count" class="form-control" min="0" value="{{ old('participants_count', 0) }}"></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Terbit</option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">Deskripsi Kegiatan</label><textarea name="description" class="form-control" rows="3" placeholder="Deskripsi kegiatan...">{{ old('description') }}</textarea></div>

    <hr class="my-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-camera me-2" style="color:var(--primary)"></i>Upload Bukti Kegiatan</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Foto Bukti 1 *</label><input type="file" name="evidence_photo" class="form-control" accept="image/*" required><small class="text-muted">Wajib diupload</small></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Foto Bukti 2</label><input type="file" name="evidence_photo_2" class="form-control" accept="image/*"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Foto Bukti 3</label><input type="file" name="evidence_photo_3" class="form-control" accept="image/*"></div>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">File Dokumen Pendukung</label><input type="file" name="document_file" class="form-control" accept=".pdf,.doc,.docx"><small class="text-muted">PDF, DOC, DOCX (max 10MB)</small></div>

    <hr class="my-3">
    <div class="mb-3"><label class="form-label">Hasil / Tindak Lanjut</label><textarea name="outcome" class="form-control" rows="2" placeholder="Hasil kegiatan / tindak lanjut...">{{ old('outcome') }}</textarea></div>

    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>Simpan Kegiatan</button>
    <a href="{{ route('admin.food-safety.index') }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>
</div>
@endsection
