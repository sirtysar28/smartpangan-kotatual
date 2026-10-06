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

    {{-- ===== CAPTCHA 5 KARAKTER (di bawah password) ===== --}}
    @php
        $captchaCode = session('login_captcha_code', strtoupper(substr(str_shuffle('ABCDEFGHJKMNPQRSTUVWXYZ23456789'), 0, 5)));
        $captchaChars = str_split($captchaCode);
        $captchaStyles = [
            ['r' => -8, 'y' => 2, 's' => 1.15], ['r' => 6, 'y' => -2, 's' => 0.95],
            ['r' => -5, 'y' => 3, 's' => 1.1], ['r' => 9, 'y' => -1, 's' => 1.05],
            ['r' => -7, 'y' => 1, 's' => 1.12],
        ];
    @endphp
    <div class="mb-3">
        <x-input-label for="captcha" :value="__('Verifikasi Keamanan')" class="small fw-semibold text-muted" />
        <div class="d-flex align-items-stretch gap-2 mt-1">
            <div class="d-flex align-items-center justify-content-center flex-shrink-0 user-select-none"
                 id="captchaQuestion"
                 style="background:repeating-linear-gradient(45deg,#eef2ff,#eef2ff 6px,#e0e7ff 6px,#e0e7ff 12px);border:1.5px dashed #c7d2fe;border-radius:10px;padding:0.45rem 0.9rem;letter-spacing:2px;font-size:1.05rem;font-weight:800;color:#4338ca;font-style:italic;overflow:hidden">
                @foreach($captchaChars as $i => $ch)
                    <span style="display:inline-block;transform:rotate({{ $captchaStyles[$i]['r'] }}deg) translateY({{ $captchaStyles[$i]['y'] }}px) scale({{ $captchaStyles[$i]['s'] }})">{{ $ch }}</span>
                @endforeach
            </div>
            <input id="captcha" type="text" name="captcha" class="form-control flex-grow-1"
                   value="{{ old('captcha') }}" required autocomplete="off"
                   maxlength="5" placeholder="5 karakter" style="max-width:130px;text-transform:uppercase;font-weight:700;letter-spacing:3px" />
        </div>
        <x-input-error :messages="$errors->get('captcha')" class="mt-1 small text-danger" />
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
