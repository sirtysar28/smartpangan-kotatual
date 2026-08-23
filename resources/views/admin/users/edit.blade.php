@extends('layouts.admin')
@section('title', 'Edit User - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none small" style="color:var(--primary)"><i class="bi bi-arrow-left me-1"></i>Kembali ke Manage User</a>
        <h4 class="fw-bold mb-1 mt-2" style="font-size:1.4rem">Edit User ✏️</h4>
        <p class="text-muted mb-0 small">Mengubah data user: <strong>{{ $user->name }}</strong></p>
    </div>
</div>

<div class="card border-0 p-4 animate-fade-up delay-1" style="border-radius:var(--radius);box-shadow:var(--shadow);max-width:700px">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Nama Lengkap *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                @error('password')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Role *</label>
                <select name="role" class="form-select" required>
                    <option value="super_admin" {{ $user->role==='super_admin'?'selected':'' }}>Super Admin</option>
                    <option value="opd" {{ $user->role==='opd'?'selected':'' }}>OPD</option>
                    <option value="umkm" {{ $user->role==='umkm'?'selected':'' }}>UMKM</option>
                    <option value="buyer" {{ $user->role==='buyer'?'selected':'' }}>Buyer</option>
                </select>
                @error('role')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold text-muted">Alamat</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Status *</label>
                <select name="status" class="form-select" required>
                    <option value="active" {{ $user->status==='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ $user->status==='inactive'?'selected':'' }}>Inactive</option>
                    <option value="banned" {{ $user->status==='banned'?'selected':'' }}>Banned</option>
                </select>
            </div>
            <div class="col-12 mt-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-modern"><i class="bi bi-check-lg me-2"></i>Update User</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" style="border-radius:12px">Batal</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
