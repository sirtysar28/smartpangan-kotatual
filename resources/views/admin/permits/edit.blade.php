@extends('layouts.admin')
@section('title', 'Edit Perizinan - Admin')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold"><i class="bi bi-pencil-square me-2" style="color:var(--primary)"></i>Edit Perizinan: {{ $permit->permit_number }}</h5>
    <a href="{{ route('admin.permits.index') }}" class="btn btn-sm" style="background:rgba(99,102,241,0.08);color:var(--primary);border-radius:8px"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm" style="border-radius:var(--radius);max-width:800px">
    <div class="card-body p-4">
        <form action="{{ route('admin.permits.update', $permit) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-building me-2" style="color:var(--primary)"></i>Informasi Usaha</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Nomor Perizinan</label>
                    <input type="text" class="form-control" value="{{ $permit->permit_number }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="permit_status" class="form-select">
                        <option value="pending" {{ $permit->permit_status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ $permit->permit_status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ $permit->permit_status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="expired" {{ $permit->permit_status == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Jenis Perizinan *</label>
                    <select name="permit_type" class="form-select" required>
                        <option value="pduk" {{ $permit->permit_type == 'pduk' ? 'selected' : '' }}>PDUK</option>
                        <option value="bpom" {{ $permit->permit_type == 'bpom' ? 'selected' : '' }}>BPOM</option>
                        <option value="halal" {{ $permit->permit_type == 'halal' ? 'selected' : '' }}>Halal</option>
                        <option value="sp_irt" {{ $permit->permit_type == 'sp_irt' ? 'selected' : '' }}>SP-IRT</option>
                        <option value="lainnya" {{ $permit->permit_type == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nama Usaha *</label>
                    <input type="text" name="business_name" class="form-control" required value="{{ $permit->business_name }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Kategori Usaha</label>
                    <input type="text" name="business_category" class="form-control" value="{{ $permit->business_category }}">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Alamat Usaha</label>
                    <textarea name="business_address" class="form-control" rows="2">{{ $permit->business_address }}</textarea>
                </div>
            </div>

            <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-person me-2" style="color:var(--primary)"></i>Informasi Pemohon</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nama Pemohon *</label>
                    <input type="text" name="applicant_name" class="form-control" required value="{{ $permit->applicant_name }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">No. Telepon</label>
                    <input type="text" name="applicant_phone" class="form-control" value="{{ $permit->applicant_phone }}">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Produk Pangan</label>
                    <textarea name="food_products" class="form-control" rows="2">{{ $permit->food_products }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Tanggal Terbit</label>
                    <input type="date" name="issue_date" class="form-control" value="{{ $permit->issue_date ? $permit->issue_date->format('Y-m-d') : '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Tanggal Kadaluarsa</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ $permit->expiry_date ? $permit->expiry_date->format('Y-m-d') : '' }}">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Upload Dokumen Baru</label>
                    <input type="file" name="document_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                    @if($permit->document_file_url)
                        <div class="mt-2"><a href="{{ $permit->document_file_url }}" target="_blank" class="btn btn-sm" style="background:rgba(99,102,241,0.08);color:var(--primary);border-radius:8px"><i class="bi bi-eye me-1"></i>Lihat Dokumen</a></div>
                    @endif
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ $permit->notes }}</textarea>
                </div>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-modern"><i class="bi bi-save me-2"></i>Update</button>
                <a href="{{ route('admin.permits.index') }}" class="btn btn-outline-secondary" style="border-radius:10px">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
