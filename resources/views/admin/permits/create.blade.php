@extends('layouts.admin')
@section('title', 'Tambah Perizinan Pangan - Admin')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold"><i class="bi bi-plus-circle me-2" style="color:var(--primary)"></i>Tambah Perizinan Pangan</h5>
    <a href="{{ route('admin.permits.index') }}" class="btn btn-sm" style="background:rgba(99,102,241,0.08);color:var(--primary);border-radius:8px"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm" style="border-radius:var(--radius);max-width:800px">
    <div class="card-body p-4">
        <form action="{{ route('admin.permits.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-building me-2" style="color:var(--primary)"></i>Informasi Usaha</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nama Usaha *</label>
                    <input type="text" name="business_name" class="form-control" required value="{{ old('business_name') }}" placeholder="Nama usaha pangan">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Kategori Usaha</label>
                    <input type="text" name="business_category" class="form-control" value="{{ old('business_category') }}" placeholder="Contoh: Olahan Ikan, Restoran, dll">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Alamat Usaha</label>
                    <textarea name="business_address" class="form-control" rows="2" placeholder="Alamat lengkap usaha">{{ old('business_address') }}</textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-person me-2" style="color:var(--primary)"></i>Informasi Pemohon</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nama Pemohon *</label>
                    <input type="text" name="applicant_name" class="form-control" required value="{{ old('applicant_name') }}" placeholder="Nama lengkap pemohon">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">No. Telepon</label>
                    <input type="text" name="applicant_phone" class="form-control" value="{{ old('applicant_phone') }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-file-earmark-text me-2" style="color:var(--primary)"></i>Detail Perizinan</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Jenis Perizinan *</label>
                    <select name="permit_type" class="form-select" required>
                        <option value="">Pilih Jenis</option>
                        <option value="pduk" {{ old('permit_type') == 'pduk' ? 'selected' : '' }}>PDUK (Perizinan Usaha Pangan)</option>
                        <option value="bpom" {{ old('permit_type') == 'bpom' ? 'selected' : '' }}>BPOM (Badan POM)</option>
                        <option value="halal" {{ old('permit_type') == 'halal' ? 'selected' : '' }}>Halal (BPJPH)</option>
                        <option value="sp_irt" {{ old('permit_type') == 'sp_irt' ? 'selected' : '' }}>SP-IRT (Izin Rumah Tangga)</option>
                        <option value="lainnya" {{ old('permit_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">UMKM Terdaftar</label>
                    <select name="umkm_id" class="form-select">
                        <option value="">-- Tidak terkait UMKM --</option>
                        @foreach($umkms as $u)
                            <option value="{{ $u->id }}" {{ old('umkm_id') == $u->id ? 'selected' : '' }}>{{ $u->business_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Produk Pangan</label>
                    <textarea name="food_products" class="form-control" rows="2" placeholder="Daftar produk pangan yang diizinkan">{{ old('food_products') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Tanggal Terbit</label>
                    <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Tanggal Kadaluarsa</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
                </div>
            </div>

            <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-paperclip me-2" style="color:var(--primary)"></i>Dokumen</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Upload Dokumen</label>
                    <input type="file" name="document_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">PDF, JPG, PNG — Maks 5MB</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-semibold">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan">{{ old('notes') }}</textarea>
                </div>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-modern"><i class="bi bi-save me-2"></i>Simpan</button>
                <a href="{{ route('admin.permits.index') }}" class="btn btn-outline-secondary" style="border-radius:10px">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
