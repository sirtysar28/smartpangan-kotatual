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
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Kecamatan</label>
                    <select name="kecamatan" id="permitKecamatan" class="form-select">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach(array_keys(\App\Support\TualWilayah::kecamatan()) as $kec)
                            <option value="{{ $kec }}" {{ old('kecamatan', $permit->kecamatan) == $kec ? 'selected' : '' }}>Kec. {{ $kec }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Desa / Kelurahan</label>
                    <select name="desa" id="permitDesa" class="form-select">
                        <option value="">-- Pilih Kecamatan dahulu --</option>
                    </select>
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

            <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-card-checklist me-2" style="color:var(--primary)"></i>NIB &amp; Detail Perizinan</h6>
            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nomor Izin Berusaha (NIB)</label>
                    <input type="text" name="nib" class="form-control" value="{{ old('nib', $permit->nib) }}" placeholder="Contoh: 8272010012345">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">UMKM Terdaftar</label>
                    <select name="umkm_id" class="form-select">
                        <option value="">-- Tidak terkait UMKM --</option>
                        @foreach($umkms as $u)
                            <option value="{{ $u->id }}" {{ old('umkm_id', $permit->umkm_id) == $u->id ? 'selected' : '' }}>{{ $u->business_name }}</option>
                        @endforeach
                    </select>
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

            <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-paperclip me-2" style="color:var(--primary)"></i>Dokumen</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold"><i class="bi bi-card-checklist me-1"></i>Upload Dokumen NIB</label>
                    <input type="file" name="nib_document" id="nibDocumentFile" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <label for="nibDocumentFile" class="btn btn-sm m-0" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;border-radius:10px;font-weight:600;padding:0.45rem 1rem">
                            <i class="bi bi-upload me-1"></i>Upload Dokumen NIB
                        </label>
                        <small class="text-muted" id="nibFileName">PDF, JPG, PNG — Maks 5MB</small>
                    </div>
                    @if($permit->nib_document_url)
                        <div class="mt-2"><a href="{{ $permit->nib_document_url }}" target="_blank" class="btn btn-sm" style="background:rgba(16,185,129,0.08);color:#10b981;border-radius:8px"><i class="bi bi-eye me-1"></i>Lihat Dokumen NIB</a></div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold"><i class="bi bi-file-earmark-text me-1"></i>Upload Dokumen Perizinan</label>
                    <input type="file" name="document_file" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Dropdown Desa dependen Kecamatan (data wilayah Kota Tual)
        const KEC_DESA = @json(\App\Support\TualWilayah::kecamatan());
        const kec = document.getElementById('permitKecamatan');
        const desa = document.getElementById('permitDesa');
        const selectedDesa = {{ json_encode(old('desa', $permit->desa)) }};

        function renderDesa() {
            if (!kec || !desa) return;
            const list = KEC_DESA[kec.value] || [];
            desa.innerHTML = '<option value="">-- Pilih Desa / Kelurahan --</option>';
            list.forEach(function (d) {
                const opt = document.createElement('option');
                opt.value = d;
                opt.textContent = d;
                if (d === selectedDesa) opt.selected = true;
                desa.appendChild(opt);
            });
        }
        if (kec) {
            renderDesa();
            kec.addEventListener('change', renderDesa);
        }

        // Nama file terpilih pada tombol Upload Dokumen NIB
        const nibFile = document.getElementById('nibDocumentFile');
        const nibName = document.getElementById('nibFileName');
        if (nibFile && nibName) {
            nibFile.addEventListener('change', function () {
                nibName.textContent = this.files.length ? this.files[0].name : 'PDF, JPG, PNG — Maks 5MB';
                nibName.classList.add('fw-semibold');
            });
        }
    });
</script>
@endpush
