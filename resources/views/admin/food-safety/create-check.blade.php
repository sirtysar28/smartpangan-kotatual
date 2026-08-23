@extends('layouts.admin')
@section('title', 'Pemeriksaan Keamanan Pangan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-clipboard-plus me-2"></i>Input Pemeriksaan Keamanan Pangan</h5>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:900px">
<form action="{{ route('admin.food-safety.check.store') }}" method="POST" enctype="multipart/form-data">@csrf
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Tanggal Pemeriksaan *</label><input type="date" name="check_date" class="form-control" required value="{{ old('check_date', today()->format('Y-m-d')) }}"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Jenis Pangan (Asal) *</label>
                <select name="food_source_type" class="form-select" required>
                    <option value="tumbuhan" {{ old('food_source_type') === 'tumbuhan' ? 'selected' : '' }}>🌿 Pangan Asal Tumbuhan</option>
                    <option value="ikan" {{ old('food_source_type') === 'ikan' ? 'selected' : '' }}>🐟 Pangan Asal Ikan</option>
                    <option value="hewan" {{ old('food_source_type') === 'hewan' ? 'selected' : '' }}>🐄 Pangan Asal Hewan</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Kondisi Pangan *</label>
                <select name="food_condition" class="form-select" required>
                    <option value="segar" {{ old('food_condition') === 'segar' ? 'selected' : '' }}>Pangan Segar</option>
                    <option value="segar_olahan_awal" {{ old('food_condition') === 'segar_olahan_awal' ? 'selected' : '' }}>Pangan Segar Olahan Awal (Tempe, Tahu, dll)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Nama Pangan *</label><input type="text" name="food_name" class="form-control" required placeholder="Contoh: Beras Premium" value="{{ old('food_name') }}"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Komoditas (opsional)</label>
                <select name="commodity_id" class="form-select">
                    <option value="">-- Tidak terikat --</option>
                    @foreach($commodities as $id => $name)
                    <option value="{{ $id }}" {{ old('commodity_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Jenis Pemeriksaan *</label>
                <select name="inspection_type" class="form-select" required>
                    <option value="rutin" {{ old('inspection_type') === 'rutin' ? 'selected' : '' }}>Rutin</option>
                    <option value="insidentil" {{ old('inspection_type') === 'insidentil' ? 'selected' : '' }}>Insidentil</option>
                    <option value="follow_up" {{ old('inspection_type') === 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                </select>
            </div>
        </div>
    </div>

    <hr class="my-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-people me-2" style="color:var(--primary)"></i>Distributor & Pengecer</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Nama Distributor</label><input type="text" name="distributor_name" class="form-control" placeholder="Nama distributor" value="{{ old('distributor_name') }}"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Nama Pengecer</label><input type="text" name="retailer_name" class="form-control" placeholder="Nama pengecer/toko" value="{{ old('retailer_name') }}"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Alamat Pengecer</label><input type="text" name="retailer_address" class="form-control" placeholder="Alamat" value="{{ old('retailer_address') }}"></div>
        </div>
    </div>

    <hr class="my-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-box me-2" style="color:var(--primary)"></i>Informasi Kemasan</h6>
    <div class="row g-3">
        <div class="col-md-3">
            <div class="mb-3"><label class="form-label">Jenis Kemasan</label><input type="text" name="packaging_type" class="form-control" placeholder="Plastik, Kardus, dll" value="{{ old('packaging_type') }}"></div>
        </div>
        <div class="col-md-3">
            <div class="mb-3"><label class="form-label">Netto</label><input type="text" name="packaging_netto" class="form-control" placeholder="500g, 1L, dll" value="{{ old('packaging_netto') }}"></div>
        </div>
        <div class="col-md-3">
            <div class="mb-3"><label class="form-label">Kadaluarsa</label><input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}"></div>
        </div>
        <div class="col-md-3">
            <div class="mb-3"><label class="form-label">No. BP POM / MD</label><input type="text" name="bpom_number" class="form-control" placeholder="MD xxxxx" value="{{ old('bpom_number') }}"></div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_halal" id="is_halal" value="1" {{ old('is_halal') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_halal">Sertifikasi Halal</label>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3"><label class="form-label">No. Halal</label><input type="text" name="halal_number" class="form-control" value="{{ old('halal_number') }}"></div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_pduk" id="is_pduk" value="1" {{ old('is_pduk') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_pduk">PDUK</label>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3"><label class="form-label">No. PDUK</label><input type="text" name="pduk_number" class="form-control" value="{{ old('pduk_number') }}"></div>
        </div>
    </div>

    <hr class="my-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-clipboard-check me-2" style="color:var(--primary)"></i>Hasil & Rekomendasi</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Hasil Pemeriksaan *</label>
                <select name="inspection_result" class="form-select" required>
                    <option value="layak" {{ old('inspection_result') === 'layak' ? 'selected' : '' }}>✅ Layak</option>
                    <option value="tidak_layak" {{ old('inspection_result') === 'tidak_layak' ? 'selected' : '' }}>❌ Tidak Layak</option>
                    <option value="perlu_tindak_lanjut" {{ old('inspection_result') === 'perlu_tindak_lanjut' ? 'selected' : '' }}>⚠️ Perlu Tindak Lanjut</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Nama Pemeriksa</label><input type="text" name="inspector_name" class="form-control" value="{{ old('inspector_name') }}"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Foto Rekomendasi</label><input type="file" name="recommendation_photo" class="form-control" accept="image/*"><small class="text-muted">Upload bukti foto rekomendasi</small></div>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">Rekomendasi</label><textarea name="recommendation" class="form-control" rows="3" placeholder="Tulis rekomendasi pemeriksaan...">{{ old('recommendation') }}</textarea></div>
    <div class="mb-3"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea></div>

    <hr class="my-3">
    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>Simpan Pemeriksaan</button>
    <a href="{{ route('admin.food-safety.index') }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>
</div>
@endsection
