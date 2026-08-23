@extends('layouts.auth')
@section('title', 'Masuk - Pangan Cerdas (Panca) Maryadat Kota Tual')

@section('content')
<div class="text-center mb-4">
    <span class="d-inline-flex align-items-center gap-2 mb-3">
        <img src="{{ asset('logo.png') }}" alt="Pangan Cerdas (Panca) Maryadat Kota Tual" style="width:100px;height:100px;border-radius:10px">
    </span>
    <h5 class="fw-bold">Silahkan Login</h5>
    <p class="text-muted small mb-0">Masuk ke akun Pangan Cerdas (Panca) Maryadat Kota Tual Anda</p>
</div>

<x-auth-session-status class="mb-4" :status="session('status')" />
<form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf

    <div class="mb-3">
        <x-input-label for="email" :value="__('Email')" class="small fw-semibold text-muted" />
        <div class="input-icon-wrapper mt-1">
            <i class="bi bi-envelope icon-left"></i>
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-1 small text-danger" />
    </div>

    <div class="mb-3">
        <x-input-label for="password" :value="__('Password')" class="small fw-semibold text-muted" />
        <div class="input-icon-wrapper mt-1">
            <i class="bi bi-lock icon-left"></i>
            <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" />
            <span class="icon-right" onclick="togglePassword('password', this)" title="Lihat password">
                <i class="bi bi-eye-slash"></i>
            </span>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-1 small text-danger" />
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <label class="form-check-label d-flex align-items-center gap-2 mb-0">
            <input type="checkbox" class="form-check-input" name="remember" id="remember">
            <small class="text-muted">Ingat saya</small>
        </label>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="small text-decoration-none fw-semibold" style="color:var(--primary)">Lupa password?</a>
        @endif
    </div>

    <button type="submit" class="btn btn-primary-modern w-100 py-2-5 mb-3" style="padding:0.75rem;font-size:0.95rem">
        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
    </button>

    <div class="text-center mb-3">
        <small class="text-muted">Belum punya akun?</small>
        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none small" style="color:var(--primary)"> Daftar Sekarang</a>
    </div>
</form>

<div class="mt-3 p-3" style="background:#f8fafc;border-radius:12px;border:1px solid #f1f5f9">
    <small class="fw-bold d-block mb-2" style="color:var(--gray)">🔑 Demo Akun:</small>
    <div class="d-flex flex-column gap-1">
        <small style="color:var(--gray-light)"><span class="fw-semibold text-dark">Admin:</span> admin@tualmart.id / admin123</small>
        <small style="color:var(--gray-light)"><span class="fw-semibold text-dark">UMKM:</span> ikanasap@gmail.com / umkm123</small>
        <small style="color:var(--gray-light)"><span class="fw-semibold text-dark">Buyer:</span> buyer@gmail.com / buyer123</small>
    </div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if(input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    }
}
</script>
@endsection
