@extends('layouts.app')
@section('title', 'Profil Saya - Pangan Cerdas (Panca) Maryadat Kota Tual')

@section('content')
<div class="container py-4" style="max-width:600px">
    <div class="d-flex align-items-center gap-3 mb-4">
        <span class="nav-avatar" style="width:48px;height:48px;font-size:1.1rem;border-radius:12px">{{ strtoupper(substr($user->name,0,1)) }}</span>
        <div>
            <h5 class="fw-bold mb-0" style="font-size:1.1rem">Profil Saya</h5>
            <small class="text-muted">Kelola informasi akun Anda</small>
        </div>
    </div>

    <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow)">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:0.85rem">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:0.85rem">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:0.85rem">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:0.85rem">Alamat</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:0.85rem">Peran</label>
                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                    <small class="text-muted" style="font-size:0.7rem">Peran hanya bisa diubah oleh Super Admin</small>
                </div>

                <hr style="border-color:#e2e8f0">

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:0.85rem">Password Baru <small class="text-muted fw-normal">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" autocomplete="new-password">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:0.85rem">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-primary-modern w-100">
                    <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
