@extends('layouts.admin')
@section('title', 'Edit Komoditas - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2"></i>Edit Komoditas: {{ $commodity->name }}</h5>

{{-- === HARGA TERKINI & UPDATE HARGA (Poin 2 & 4) === --}}
@php
    $latest = $commodity->latestPrice;
    $pc = $commodity->getPriceComparison();
    $fs = $pc['food_status'] ?? ['code'=>'aman','label'=>'Aman','color'=>'#10b981','icon'=>'shield-check'];
    $hetExceeded = $pc['het_exceeded'] ?? false;
    $hetOver = $pc['het_over_percent'] ?? null;
    $riwayat = $commodity->prices()->latest('price_date')->take(6)->get();
@endphp
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-cash-coin me-2" style="color:var(--primary)"></i>Harga Terkini & Update Harga</h6>
            <span class="badge" style="background:{{ $fs['color'] }}20;color:{{ $fs['color'] }};font-size:0.7rem;border-radius:6px;font-weight:700">
                <i class="bi bi-{{ $fs['icon'] }} me-1"></i>{{ $fs['label'] }}
            </span>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="p-2 rounded-3" style="background:rgba(99,102,241,0.06)">
                    <small class="text-muted d-block" style="font-size:0.7rem">Harga Terkini</small>
                    <div class="fw-bold" style="color:var(--primary);font-size:1.05rem">
                        {{ $latest ? 'Rp '.number_format($latest->price, 0, ',', '.') : '-' }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-2 rounded-3" style="background:rgba(16,185,129,0.06)">
                    <small class="text-muted d-block" style="font-size:0.7rem">Tanggal Update Terakhir</small>
                    <div class="fw-semibold" style="font-size:0.9rem">
                        {{ $latest ? $latest->price_date->format('d/m/Y') : '-' }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-2 rounded-3" style="background:rgba(245,158,11,0.06)">
                    <small class="text-muted d-block" style="font-size:0.7rem">HET (Batas Atas)</small>
                    <div class="fw-semibold" style="font-size:0.9rem">{{ $commodity->het ? 'Rp '.number_format($commodity->het, 0, ',', '.') : '-' }}</div>
                    @if($hetExceeded)
                        <small style="color:#ef4444;font-size:0.68rem"><i class="bi bi-exclamation-triangle-fill me-1"></i>Lewati HET +{{ $hetOver }}%</small>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-2 rounded-3" style="background:rgba(14,165,233,0.06)">
                    <small class="text-muted d-block" style="font-size:0.7rem">Perubahan</small>
                    <div class="fw-semibold" style="font-size:0.9rem;color:{{ ($latest && $latest->change_percent > 0) ? '#ef4444' : (($latest && $latest->change_percent < 0) ? '#10b981' : 'var(--text-main)') }}">
                        @if($latest && $latest->change_percent !== null)
                            {{ $latest->change_percent >= 0 ? '+' : '' }}{{ number_format($latest->change_percent, 1) }}%
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Form update harga dengan tanggal --}}
        <form action="{{ route('admin.commodities.update-price', $commodity) }}" method="POST" class="row g-2 align-items-end">@csrf
            <div class="col-md-4">
                <label class="form-label small fw-bold mb-1">Harga Baru (Rp)</label>
                <input type="number" name="price" class="form-control" placeholder="Masukkan harga terbaru" step="100" min="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold mb-1">Tanggal Update Harga</label>
                <input type="date" name="price_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn w-100" style="background:linear-gradient(135deg,#10b981,#34d399);color:#fff;border-radius:8px;font-weight:600">
                    <i class="bi bi-arrow-up-circle me-1"></i>Update Harga
                </button>
            </div>
        </form>

        {{-- Riwayat harga terakhir --}}
        @if($riwayat->count())
        <div class="mt-3">
            <small class="text-muted fw-bold d-block mb-2"><i class="bi bi-clock-history me-1"></i>Riwayat Harga Terakhir</small>
            <div class="d-flex flex-wrap gap-2">
                @foreach($riwayat as $r)
                    <span class="badge" style="background:rgba(99,102,241,0.08);color:var(--text-main);font-size:0.7rem;border-radius:6px;padding:0.4rem 0.6rem;font-weight:600">
                        {{ $r->price_date->format('d/m/Y') }}: Rp {{ number_format($r->price, 0, ',', '.') }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:800px">
<form action="{{ route('admin.commodities.update', $commodity) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Nama Komoditas *</label><input type="text" name="name" class="form-control" required value="{{ $commodity->name }}"></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Satuan *</label><input type="text" name="unit" class="form-control" required value="{{ $commodity->unit }}"></div>
        </div>
    </div>

    {{-- HET / HAP / HPP --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag me-1" style="color:#ef4444"></i>HET (Harga Eceran Tertinggi)</label>
                <div class="input-icon-wrapper">
                    <span class="input-icon-left">Rp</span>
                    <input type="number" name="het" class="form-control" step="100" value="{{ $commodity->het }}">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag me-1" style="color:#0ea5e9"></i>HAP (Harga Acuan Pemerintah)</label>
                <div class="input-icon-wrapper">
                    <span class="input-icon-left">Rp</span>
                    <input type="number" name="hap" class="form-control" step="100" value="{{ $commodity->hap }}">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag me-1" style="color:#f59e0b"></i>HPP (Harga Pokok Penjualan)</label>
                <div class="input-icon-wrapper">
                    <span class="input-icon-left">Rp</span>
                    <input type="number" name="hpp" class="form-control" step="100" value="{{ $commodity->hpp }}">
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3"><label class="form-label">Kategori *</label><select name="category" class="form-select">
        <option value="pangan" {{ $commodity->category == 'pangan' ? 'selected' : '' }}>Pangan</option>
        <option value="sandang" {{ $commodity->category == 'sandang' ? 'selected' : '' }}>Sandang</option>
        <option value="perumahan" {{ $commodity->category == 'perumahan' ? 'selected' : '' }}>Perumahan</option>
        <option value="lainnya" {{ $commodity->category == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
    </select></div>
    <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="2">{{ $commodity->description }}</textarea></div>
    <div class="mb-3">
        <label class="form-label">Gambar Komoditas</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
        @if($commodity->image_url)
        <div class="mt-2">
            <img src="{{ $commodity->image_url }}" alt="{{ $commodity->name }}" style="width:80px;height:80px;border-radius:12px;object-fit:cover;border:1px solid #e2e8f0">
        </div>
        @endif
    </div>
    <div class="mb-3 form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $commodity->is_active ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Komoditas Aktif</label>
    </div>

    <hr class="my-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-patch-check-fill me-2" style="color:var(--primary)"></i>Informasi Sertifikasi</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Jenis Sertifikasi</label><input type="text" name="sertifikasi_type" class="form-control" value="{{ $commodity->sertifikasi_type }}"></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Lembaga Penerbit</label><input type="text" name="sertifikasi_issuer" class="form-control" value="{{ $commodity->sertifikasi_issuer }}"></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Nomor Sertifikat</label><input type="text" name="sertifikasi_number" class="form-control" value="{{ $commodity->sertifikasi_number }}"></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Tanggal Terbit</label><input type="date" name="sertifikasi_date" class="form-control" value="{{ $commodity->sertifikasi_date ? $commodity->sertifikasi_date->format('Y-m-d') : '' }}"></div>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">Keterangan Tambahan Sertifikasi</label><textarea name="sertifikasi_note" class="form-control" rows="2">{{ $commodity->sertifikasi_note }}</textarea></div>

    <hr class="my-3">
    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>Update</button>
    <a href="{{ route('admin.commodities.index') }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>
</div>
@endsection
