@extends('layouts.app')
@section('title', 'Daftar UMKM - Pangan Cerdas (Panca) Maryadat Kota Tual')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow rounded-4 p-4">
                <div class="text-center mb-4">
                    <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--primary),#818cf8);display:inline-flex;align-items:center;justify-content:center;margin-bottom:0.75rem">
                        <i class="bi bi-shop" style="font-size:1.5rem;color:#fff"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Daftar UMKM</h4>
                    <p class="text-muted small mb-0">Gabung sebagai pelaku UMKM Kota Tual &amp; jual produkmu!</p>
                </div>

                <form action="{{ route('umkm.register.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- ===== SECTION: Data Akun (hanya untuk guest) ===== --}}
                    @guest
                    <div class="alert d-flex align-items-start gap-2 p-3 mb-4" style="border-radius:12px;background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.12)">
                        <i class="bi bi-person-circle" style="color:var(--primary);font-size:1.1rem;margin-top:2px"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:0.85rem;color:var(--primary)">Buat Akun</div>
                            <small class="text-muted">Lengkapi data akun Anda terlebih dahulu</small>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3" style="font-size:0.9rem">
                        <span style="width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;margin-right:6px;background:rgba(99,102,241,0.1);color:var(--primary)"><i class="bi bi-person"></i></span>
                        Data Akun
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama pemilik usaha" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@contoh.com" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 karakter" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>
                    @else
                    <div class="alert d-flex align-items-center gap-2 p-3 mb-4" style="border-radius:12px;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.12)">
                        <i class="bi bi-check-circle-fill" style="color:#10b981;font-size:1.1rem"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:0.85rem;color:#10b981">Sudah Login</div>
                            <small class="text-muted">Sebagai <strong>{{ Auth::user()->name }}</strong> — silakan lengkapi data usaha Anda</small>
                        </div>
                    </div>
                    @endguest

                    {{-- ===== SECTION: Data Usaha ===== --}}
                    <h6 class="fw-bold mb-3" style="font-size:0.9rem">
                        <span style="width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;margin-right:6px;background:rgba(99,102,241,0.1);color:var(--primary)"><i class="bi bi-building"></i></span>
                        Data Usaha
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Nama Usaha <span class="text-danger">*</span></label>
                            <input type="text" name="business_name" class="form-control @error('business_name') is-invalid @enderror" value="{{ old('business_name') }}" placeholder="Contoh: Ikan Asap Tual Jaya" required>
                            @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NIB</label>
                            <input type="text" name="nib" class="form-control @error('nib') is-invalid @enderror" value="{{ old('nib') }}" placeholder="Nomor Induk">
                            @error('nib')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi Usaha</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Ceritakan tentang usaha Anda...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Usaha <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" placeholder="Jl. Merdeka No. 1, Kota Tual" required>{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp') }}" placeholder="08xxxxxxxxxx">
                            @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ===== SECTION: Dokumen ===== --}}
                    <h6 class="fw-bold mb-3" style="font-size:0.9rem">
                        <span style="width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;margin-right:6px;background:rgba(99,102,241,0.1);color:var(--primary)"><i class="bi bi-file-earmark-image"></i></span>
                        Dokumen
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Foto KTP <span class="text-danger">*</span></label>
                            <input type="file" name="ktp_image" class="form-control @error('ktp_image') is-invalid @enderror" accept="image/*" required>
                            <small class="text-muted" style="font-size:0.75rem">Foto jelas, max 2MB</small>
                            @error('ktp_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Usaha <span class="text-danger">*</span></label>
                            <input type="file" name="business_image" class="form-control @error('business_image') is-invalid @enderror" accept="image/*" required>
                            <small class="text-muted" style="font-size:0.75rem">Foto depan/usaha, max 2MB</small>
                            @error('business_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-semibold" style="background:linear-gradient(135deg,var(--primary),#818cf8);border:none;padding:0.75rem">
                        <i class="bi bi-send me-2"></i>Daftar Sekarang
                    </button>
                    <p class="text-center text-muted small mt-3 mb-0">
                        Data Anda akan diverifikasi oleh Pemerintah Daerah Kota Tual.
                    </p>
                    @guest
                    <p class="text-center text-muted small mt-2 mb-0">
                        Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold" style="color:var(--primary);text-decoration:none">Login di sini</a>
                    </p>
                    @endguest
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
