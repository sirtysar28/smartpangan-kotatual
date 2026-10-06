@extends('layouts.admin')
@section('title', 'Tambah Komoditas - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2"></i>Tambah Komoditas Pangan</h5>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:800px">
<form action="{{ route('admin.commodities.store') }}" method="POST" enctype="multipart/form-data">@csrf
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Nama Komoditas *</label><input type="text" name="name" class="form-control" required value="{{ old('name') }}"></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Satuan *</label><input type="text" name="unit" class="form-control" placeholder="kg, liter, butir, dll" required value="{{ old('unit') }}"></div>
        </div>
    </div>

    {{-- HET / HAP / HPP --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag me-1" style="color:#ef4444"></i>HET (Harga Eceran Tertinggi)</label>
                <div class="input-icon-wrapper">
                    <span class="input-icon-left">Rp</span>
                    <input type="text" name="het" class="form-control price-input" placeholder="Opsional, cth. 75.000" inputmode="numeric" autocomplete="off" value="{{ old('het') }}">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag me-1" style="color:#0ea5e9"></i>HAP (Harga Acuan Pemerintah)</label>
                <div class="input-icon-wrapper">
                    <span class="input-icon-left">Rp</span>
                    <input type="text" name="hap" class="form-control price-input" placeholder="Opsional, cth. 70.000" inputmode="numeric" autocomplete="off" value="{{ old('hap') }}">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag me-1" style="color:#f59e0b"></i>HPP (Harga Pokok Penjualan)</label>
                <div class="input-icon-wrapper">
                    <span class="input-icon-left">Rp</span>
                    <input type="text" name="hpp" class="form-control price-input" placeholder="Opsional, cth. 65.000" inputmode="numeric" autocomplete="off" value="{{ old('hpp') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3"><label class="form-label">Kategori *</label><select name="category" class="form-select">
        <option value="pangan" {{ old('category') == 'pangan' ? 'selected' : '' }}>Pangan</option>
        <option value="sandang" {{ old('category') == 'sandang' ? 'selected' : '' }}>Sandang</option>
        <option value="perumahan" {{ old('category') == 'perumahan' ? 'selected' : '' }}>Perumahan</option>
        <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
    </select></div>
    <div class="mb-3"><label class="form-label">Harga Awal (Hari Ini) *</label><input type="text" name="price" class="form-control price-input" placeholder="cth. 60.983" inputmode="numeric" autocomplete="off" required value="{{ old('price') }}"></div>
    <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea></div>
    <div class="mb-3"><label class="form-label">Gambar Komoditas</label><input type="file" name="image" class="form-control" accept="image/*"><small class="text-muted">JPG, PNG, WEBP (max 2MB)</small></div>

    <hr class="my-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-patch-check-fill me-2" style="color:var(--primary)"></i>Informasi Sertifikasi</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Jenis Sertifikasi</label><input type="text" name="sertifikasi_type" class="form-control" placeholder="Organik, Halal, BPOM, dll" value="{{ old('sertifikasi_type') }}"></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Lembaga Penerbit</label><input type="text" name="sertifikasi_issuer" class="form-control" placeholder="LSO, MUI, BPOM, dll" value="{{ old('sertifikasi_issuer') }}"></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Nomor Sertifikat</label><input type="text" name="sertifikasi_number" class="form-control" placeholder="No. Sertifikat" value="{{ old('sertifikasi_number') }}"></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Tanggal Terbit</label><input type="date" name="sertifikasi_date" class="form-control" value="{{ old('sertifikasi_date') }}"></div>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">Keterangan Tambahan Sertifikasi</label><textarea name="sertifikasi_note" class="form-control" rows="2" placeholder="Informasi tambahan...">{{ old('sertifikasi_note') }}</textarea></div>

    <hr class="my-3">
    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>Simpan</button>
    <a href="{{ route('admin.commodities.index') }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>
</div>
@endsection
