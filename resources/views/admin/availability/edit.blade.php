@extends('layouts.admin')
@section('title', 'Edit Stok - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2"></i>Edit Stok: {{ $stock->commodity->name }}</h5>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:700px">
<form action="{{ route('admin.availability.update', $stock) }}" method="POST">@csrf @method('PUT')
    <div class="mb-3"><label class="form-label">Komoditas</label><input type="text" class="form-control" value="{{ $stock->commodity->name }}" disabled></div>
    <div class="mb-3"><label class="form-label">Tanggal Pantauan *</label><input type="date" name="monitoring_date" class="form-control" value="{{ $stock->monitoring_date->format('Y-m-d') }}" required></div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Jumlah Stok *</label>
                <div class="input-group">
                    <input type="text" name="stock_quantity" class="form-control" inputmode="decimal" autocomplete="off" required value="{{ $stock->stock_quantity }}">
                    <input type="text" name="stock_unit" class="form-control" style="max-width:80px" required value="{{ $stock->stock_unit }}">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Kebutuhan Per Minggu *</label>
                <input type="text" name="weekly_need" class="form-control" inputmode="decimal" autocomplete="off" required value="{{ $stock->weekly_need }}">
            </div>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">Status Stok *</label><select name="stock_status" class="form-select" required>
        <option value="aman" {{ $stock->stock_status === 'aman' ? 'selected' : '' }}>Aman</option>
        <option value="rawan" {{ $stock->stock_status === 'rawan' ? 'selected' : '' }}>Rawan</option>
        <option value="kritis" {{ $stock->stock_status === 'kritis' ? 'selected' : '' }}>Kritis</option>
    </select></div>
    <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="notes" class="form-control" rows="2">{{ $stock->notes }}</textarea></div>
    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>Update</button>
    <a href="{{ route('admin.availability.index') }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>
</div>
@endsection
