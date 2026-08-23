@extends('layouts.auth')
@section('title', 'Daftar - Pangan Cerdas (Panca) Maryadat Kota Tual')

@section('content')
<div class="text-center mb-4">
    <span class="d-inline-flex align-items-center gap-2 mb-3">
        <span style="width:40px;height:40px;background:linear-gradient(135deg,#6366f1,#0ea5e9);border-radius:10px;display:inline-flex;align-items:center;justify-content:center"><i class="bi bi-person-plus text-white"></i></span>
    </span>
    <h5 class="fw-bold">Buat Akun Baru ✨</h5>
    <p class="text-muted small mb-0">Bergabung dengan marketplace UMKM Kota Tual</p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <x-input-label for="name" :value="__('Nama Lengkap')" class="small fw-semibold text-muted" />
        <div class="input-icon-wrapper mt-1">
            <i class="bi bi-person icon-left"></i>
            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus placeholder="Nama lengkap" />
        </div>
        <x-input-error :messages="$errors->get('name')" class="mt-1 small text-danger" />
    </div>

    <div class="mb-3">
        <x-input-label for="email" :value="__('Email')" class="small fw-semibold text-muted" />
        <div class="input-icon-wrapper mt-1">
            <i class="bi bi-envelope icon-left"></i>
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required placeholder="nama@email.com" />
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-1 small text-danger" />
    </div>

    <div class="mb-3">
        <x-input-label for="phone" :value="__('No. HP')" class="small fw-semibold text-muted" />
        <div class="input-icon-wrapper mt-1">
            <i class="bi bi-telephone icon-left"></i>
            <input id="phone" class="form-control" type="text" name="phone" value="{{ old('phone') }}" placeholder="081234567890">
        </div>
    </div>

    <div class="mb-3">
        <x-input-label for="password" :value="__('Password')" class="small fw-semibold text-muted" />
        <div class="input-icon-wrapper mt-1">
            <i class="bi bi-lock icon-left"></i>
            <x-text-input id="password" class="form-control" type="password" name="password" required placeholder="Minimal 8 karakter" />
            <span class="icon-right" onclick="togglePassword('password', this)"><i class="bi bi-eye-slash"></i></span>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-1 small text-danger" />
    </div>

    <div class="mb-3">
        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="small fw-semibold text-muted" />
        <div class="input-icon-wrapper mt-1">
            <i class="bi bi-lock-fill icon-left"></i>
            <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required placeholder="Ulangi password" />
            <span class="icon-right" onclick="togglePassword('password_confirmation', this)"><i class="bi bi-eye-slash"></i></span>
        </div>
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 small text-danger" />
    </div>

    <button type="submit" class="btn btn-primary-modern w-100 mb-3" style="padding:0.75rem;font-size:0.95rem">
        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
    </button>

    <div class="text-center">
        <small class="text-muted">Sudah punya akun?</small>
        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none small" style="color:var(--primary)"> Masuk</a>
    </div>
</form>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if(input.type === 'password') {
        input.type = 'text'; icon.classList.replace('bi-eye-slash','bi-eye');
    } else {
        input.type = 'password'; icon.classList.replace('bi-eye','bi-eye-slash');
    }
}
</script>
@endsection
