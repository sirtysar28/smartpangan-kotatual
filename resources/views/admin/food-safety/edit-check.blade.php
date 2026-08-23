@extends('layouts.admin')
@section('title', 'Edit Pemeriksaan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2"></i>Edit Pemeriksaan: {{ $foodSafety->food_name }}</h5>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:900px">
<form action="{{ route('admin.food-safety.check.update', $foodSafety) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Tanggal Pemeriksaan *</label><input type="date" name="check_date" class="form-control" required value="{{ $foodSafety->check_date->format('Y-m-d') }}"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Jenis Pangan (Asal) *</label>
                <select name="food_source_type" class="form-select" required>
                    <option value="tumbuhan" {{ $foodSafety->food_source_type === 'tumbuhan' ? 'selected' : '' }}>🌿 Pangan Asal Tumbuhan</option>
                    <option value="ikan" {{ $foodSafety->food_source_type === 'ikan' ? 'selected' : '' }}>🐟 Pangan Asal Ikan</option>
                    <option value="hewan" {{ $foodSafety->food_source_type === 'hewan' ? 'selected' : '' }}>🐄 Pangan Asal Hewan</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Kondisi Pangan *</label>
                <select name="food_condition" class="form-select" required>
                    <option value="segar" {{ $foodSafety->food_condition === 'segar' ? 'selected' : '' }}>Pangan Segar</option>
                    <option value="segar_olahan_awal" {{ $foodSafety->food_condition === 'segar_olahan_awal' ? 'selected' : '' }}>Pangan Segar Olahan Awal</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Nama Pangan *</label><input type="text" name="food_name" class="form-control" required value="{{ $foodSafety->food_name }}"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Komoditas</label>
                <select name="commodity_id" class="form-select">
                    <option value="">-- Tidak terikat --</option>
                    @foreach($commodities as $id => $name)
                    <option value="{{ $id }}" {{ $foodSafety->commodity_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Jenis Pemeriksaan *</label>
                <select name="inspection_type" class="form-select" required>
                    <option value="rutin" {{ $foodSafety->inspection_type === 'rutin' ? 'selected' : '' }}>Rutin</option>
                    <option value="insidentil" {{ $foodSafety->inspection_type === 'insidentil' ? 'selected' : '' }}>Insidentil</option>
                    <option value="follow_up" {{ $foodSafety->inspection_type === 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                </select>
            </div>
        </div>
    </div>
    <hr class="my-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-people me-2" style="color:var(--primary)"></i>Distributor & Pengecer</h6>
    <div class="row g-3">
        <div class="col-md-4"><div class="mb-3"><label class="form-label">Nama Distributor</label><input type="text" name="distributor_name" class="form-control" value="{{ $foodSafety->distributor_name }}"></div></div>
        <div class="col-md-4"><div class="mb-3"><label class="form-label">Nama Pengecer</label><input type="text" name="retailer_name" class="form-control" value="{{ $foodSafety->retailer_name }}"></div></div>
        <div class="col-md-4"><div class="mb-3"><label class="form-label">Alamat Pengecer</label><input type="text" name="retailer_address" class="form-control" value="{{ $foodSafety->retailer_address }}"></div></div>
    </div>
    <hr class="my-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-box me-2" style="color:var(--primary)"></i>Informasi Kemasan</h6>
    <div class="row g-3">
        <div class="col-md-3"><div class="mb-3"><label class="form-label">Jenis Kemasan</label><input type="text" name="packaging_type" class="form-control" value="{{ $foodSafety->packaging_type }}"></div></div>
        <div class="col-md-3"><div class="mb-3"><label class="form-label">Netto</label><input type="text" name="packaging_netto" class="form-control" value="{{ $foodSafety->packaging_netto }}"></div></div>
        <div class="col-md-3"><div class="mb-3"><label class="form-label">Kadaluarsa</label><input type="date" name="expiry_date" class="form-control" value="{{ $foodSafety->expiry_date ? $foodSafety->expiry_date->format('Y-m-d') : '' }}"></div></div>
        <div class="col-md-3"><div class="mb-3"><label class="form-label">No. BP POM / MD</label><input type="text" name="bpom_number" class="form-control" value="{{ $foodSafety->bpom_number }}"></div></div>
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <div class="mb-3"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_halal" id="is_halal" value="1" {{ $foodSafety->is_halal ? 'checked' : '' }}><label class="form-check-label" for="is_halal">Halal</label></div></div>
        </div>
        <div class="col-md-3"><div class="mb-3"><label class="form-label">No. Halal</label><input type="text" name="halal_number" class="form-control" value="{{ $foodSafety->halal_number }}"></div></div>
        <div class="col-md-3">
            <div class="mb-3"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_pduk" id="is_pduk" value="1" {{ $foodSafety->is_pduk ? 'checked' : '' }}><label class="form-check-label" for="is_pduk">PDUK</label></div></div>
        </div>
        <div class="col-md-3"><div class="mb-3"><label class="form-label">No. PDUK</label><input type="text" name="pduk_number" class="form-control" value="{{ $foodSafety->pduk_number }}"></div></div>
    </div>
    <hr class="my-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-clipboard-check me-2" style="color:var(--primary)"></i>Hasil & Rekomendasi</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Hasil Pemeriksaan *</label>
                <select name="inspection_result" class="form-select" required>
                    <option value="layak" {{ $foodSafety->inspection_result === 'layak' ? 'selected' : '' }}>✅ Layak</option>
                    <option value="tidak_layak" {{ $foodSafety->inspection_result === 'tidak_layak' ? 'selected' : '' }}>❌ Tidak Layak</option>
                    <option value="perlu_tindak_lanjut" {{ $foodSafety->inspection_result === 'perlu_tindak_lanjut' ? 'selected' : '' }}>⚠️ Perlu Tindak Lanjut</option>
                </select>
            </div>
        </div>
        <div class="col-md-4"><div class="mb-3"><label class="form-label">Nama Pemeriksa</label><input type="text" name="inspector_name" class="form-control" value="{{ $foodSafety->inspector_name }}"></div></div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Foto Rekomendasi</label><input type="file" name="recommendation_photo" class="form-control" accept="image/*">
                @if($foodSafety->recommendation_photo)<small class="text-muted">Sudah ada foto</small>@endif
            </div>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">Rekomendasi</label><textarea name="recommendation" class="form-control" rows="3">{{ $foodSafety->recommendation }}</textarea></div>
    <div class="mb-3"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="2">{{ $foodSafety->notes }}</textarea></div>
    <hr class="my-3">
    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>Update</button>
    <a href="{{ route('admin.food-safety.index') }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>
</div>
@endsection
