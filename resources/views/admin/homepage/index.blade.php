@extends('layouts.admin')
@section('title', 'Tampilan Beranda - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem"><i class="bi bi-window-desktop me-2" style="color:var(--primary)"></i>Tampilan Beranda</h4>
        <p class="text-muted mb-0 small">Kelola konten visual beranda: struktur organisasi &amp; galeri foto kegiatan</p>
    </div>
    <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-primary-modern rounded-pill"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat Beranda</a>
</div>

<div class="row g-4">
    {{-- Struktur Organisasi --}}
    <div class="col-12 col-xl-5 animate-fade-up">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-diagram-3 me-2" style="color:var(--primary)"></i>Struktur Organisasi</h6>
                <p class="text-muted small">Tampil di beranda sebagai bagian informasi umum. Disarankan gambar landscape.</p>

                @if($strukturImage)
                <div class="mb-3" style="border-radius:12px;overflow:hidden;border:1px solid rgba(0,0,0,0.06)">
                    <img src="{{ \Storage::url($strukturImage) }}" alt="Struktur Organisasi" style="width:100%;max-height:320px;object-fit:contain;background:#f8fafc">
                </div>
                <div class="d-flex gap-2">
                    <label class="btn btn-sm rounded-pill px-3" style="background:rgba(99,102,241,0.1);color:var(--primary);cursor:pointer">
                        <i class="bi bi-arrow-repeat me-1"></i>Ganti Gambar
                        <input type="file" name="struktur_organisasi" form="strukturForm" class="d-none" accept="image/*" onchange="this.closest('label').querySelector('i').className='bi bi-check2 me-1'">
                    </label>
                    <form action="{{ route('admin.homepage.struktur.destroy') }}" method="POST" onsubmit="return confirm('Hapus gambar struktur organisasi?')">@csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm rounded-pill px-3" style="background:rgba(239,68,68,0.1);color:#ef4444"><i class="bi bi-trash me-1"></i>Hapus</button>
                    </form>
                </div>
                @else
                <div class="text-center py-5 mb-2" style="border:2px dashed rgba(99,102,241,0.25);border-radius:12px">
                    <i class="bi bi-image" style="font-size:2rem;color:rgba(99,102,241,0.4)"></i>
                    <p class="text-muted small mt-2 mb-3">Belum ada gambar struktur organisasi</p>
                </div>
                @endif

                <form action="{{ route('admin.homepage.struktur.update') }}" method="POST" enctype="multipart/form-data" id="strukturForm" class="mt-3">@csrf
                    <label class="form-label small fw-semibold">{{ $strukturImage ? 'Unggah Gambar Baru' : 'Unggah Gambar' }} (JPG/PNG/WEBP, maks 4MB)</label>
                    <input type="file" name="struktur_organisasi" class="form-control form-control-sm" accept="image/*" @unless($strukturImage) required @endunless>
                    <button type="submit" class="btn btn-primary-modern btn-sm rounded-pill px-4 mt-3"><i class="bi bi-upload me-1"></i>Simpan Struktur Organisasi</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Galeri Kegiatan --}}
    <div class="col-12 col-xl-7 animate-fade-up delay-1">
        <div class="card border-0 mb-4" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-images me-2" style="color:var(--primary)"></i>Tambah Foto Kegiatan</h6>
                <form action="{{ route('admin.homepage.gallery.store') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="row g-2">
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Judul Kegiatan *</label>
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="cth: Pantau Harga Pasar Tual" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tanggal Kegiatan</label>
                            <input type="date" name="activity_date" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Urutan</label>
                            <input type="number" name="sort_order" class="form-control form-control-sm" placeholder="0" min="0">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Foto * (JPG/PNG/WEBP, maks 4MB)</label>
                            <input type="file" name="image" class="form-control form-control-sm" accept="image/*" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary-modern btn-sm rounded-pill w-100"><i class="bi bi-plus-lg me-1"></i>Tambah Foto</button>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Keterangan</label>
                            <input type="text" name="description" class="form-control form-control-sm" placeholder="Keterangan singkat kegiatan (opsional)">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap me-2" style="color:var(--primary)"></i>Galeri Kegiatan</h6>
                    <span class="badge-modern" style="background:rgba(99,102,241,0.1);color:#6366f1">{{ $galleries->count() }} foto</span>
                </div>
                @if($galleries->count() > 0)
                <div class="row g-3">
                    @foreach($galleries as $g)
                    <div class="col-6 col-md-4">
                        <div class="position-relative" style="border-radius:12px;overflow:hidden;border:1px solid rgba(0,0,0,0.06);{{ $g->is_active ? '' : 'opacity:0.5' }}">
                            <img src="{{ $g->image_url }}" alt="{{ $g->title }}" style="width:100%;height:140px;object-fit:cover">
                            <div class="p-2" style="background:var(--bg-card)">
                                <div class="fw-semibold text-truncate" style="font-size:0.75rem">{{ $g->title }}</div>
                                <small class="text-muted" style="font-size:0.65rem">{{ $g->activity_date?->format('d/m/Y') ?? '-' }}</small>
                            </div>
                            <div class="position-absolute d-flex gap-1" style="top:8px;right:8px">
                                <form action="{{ route('admin.homepage.gallery.toggle', $g) }}" method="POST">@csrf
                                    <button type="submit" class="btn btn-sm" style="background:rgba(255,255,255,0.9);border-radius:6px;font-size:0.65rem;padding:0.15rem 0.4rem" title="{{ $g->is_active ? 'Sembunyikan' : 'Tampilkan' }}">
                                        <i class="bi bi-{{ $g->is_active ? 'eye' : 'eye-slash' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.homepage.gallery.destroy', $g) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.9);color:#fff;border-radius:6px;font-size:0.65rem;padding:0.15rem 0.4rem" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-images" style="font-size:2rem;opacity:0.3"></i>
                    <p class="text-muted small mt-2 mb-0">Belum ada foto kegiatan. Tambahkan lewat form di atas.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
