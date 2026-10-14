@extends('layouts.admin')
@section('title', 'Input Stok - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-box-seam me-2"></i>Input Data Stok Pangan</h5>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:700px">
<form action="{{ route('admin.availability.store') }}" method="POST">@csrf
    <div class="mb-3"><label class="form-label">Komoditas *</label><select name="commodity_id" class="form-select" required>
        <option value="">-- Pilih Komoditas --</option>
        @foreach($commodities as $id => $name)
        <option value="{{ $id }}" {{ old('commodity_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
        @endforeach
    </select></div>
    <div class="mb-3"><label class="form-label">Tanggal Pantauan *</label><input type="date" name="monitoring_date" class="form-control" value="{{ old('monitoring_date', today()->format('Y-m-d')) }}" required></div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Jumlah Stok *</label>
                <div class="input-group">
                    <input type="text" name="stock_quantity" class="form-control" inputmode="decimal" autocomplete="off" required placeholder="cth. 1.250,5" value="{{ old('stock_quantity') }}">
                    <input type="text" name="stock_unit" class="form-control" style="max-width:80px" required placeholder="kg" value="{{ old('stock_unit', 'kg') }}">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Kebutuhan Per Minggu *</label>
                <div class="input-group">
                    <input type="text" name="weekly_need" class="form-control" inputmode="decimal" autocomplete="off" required placeholder="cth. 150" value="{{ old('weekly_need') }}">
                    <span class="input-group-text" style="font-size:0.78rem">otomatis satuan</span>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)...">{{ old('notes') }}</textarea></div>
    <small class="text-muted d-block mb-3"><i class="bi bi-info-circle me-1"></i>Estimasi ketahanan stok & status akan dihitung otomatis berdasarkan stok dan kebutuhan mingguan.</small>
    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>Simpan</button>
    <a href="{{ route('admin.availability.index') }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>
</div>
@endsection
