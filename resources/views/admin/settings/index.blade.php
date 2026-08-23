@extends('layouts.admin')
@section('title', 'Pengaturan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.2rem"><i class="bi bi-gear me-2" style="color:var(--primary)"></i>Pengaturan Sistem</h4>
        <p class="text-muted mb-0 small">Kelola konfigurasi aplikasi dan API key</p>
    </div>
</div>

@if(session('success'))
    <div class="alert-modern success alert-dismissible fade show mb-3">
        <div class="alert-icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" id="settingsForm">
    @csrf @method('PUT')

    @foreach($groups as $group => $settings)
    <div class="card border-0 mb-4" style="border-radius:var(--radius);box-shadow:var(--shadow)">
        <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2" style="border-radius:var(--radius) var(--radius) 0 0 !important;padding:0.85rem 1.25rem">
            <span style="width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.9rem;background:{{ $group === 'ai' ? 'rgba(99,102,241,0.1);color:#6366f1' : 'rgba(16,185,129,0.1);color:#10b981' }}">
                <i class="bi bi-{{ $group === 'ai' ? 'robot' : 'sliders' }}"></i>
            </span>
            <h6 class="fw-bold mb-0" style="font-size:0.95rem">{{ ucfirst($group === 'ai' ? 'AI & Chatbot' : ($group === 'general' ? 'Umum' : ucfirst($group))) }}</h6>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                @foreach($settings as $setting)
                <div class="col-md-6">
                    <label for="setting_{{ $setting->key }}" class="form-label fw-semibold" style="font-size:0.85rem">
                        {{ $setting->label }}
                        @if($setting->type === 'password')
                            <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.65rem">
                                <i class="bi bi-check-circle me-1"></i>{{ $setting->value ? 'Sudah diatur' : 'Belum diatur' }}
                            </span>
                            <small class="text-muted fw-normal d-block mt-1">Kosongkan jika tidak ingin mengubah</small>
                        @endif
                    </label>
                    @if($setting->type === 'textarea')
                        <textarea
                            id="setting_{{ $setting->key }}"
                            name="settings[{{ $setting->key }}]"
                            class="form-control"
                            rows="4"
                        >{{ $setting->value }}</textarea>
                    @elseif($setting->type === 'number')
                        <input
                            type="number"
                            id="setting_{{ $setting->key }}"
                            name="settings[{{ $setting->key }}]"
                            class="form-control"
                            value="{{ $setting->value }}"
                            step="any"
                        >
                    @elseif($setting->type === 'password')
                        <div class="input-icon-wrapper">
                            <input
                                type="password"
                                id="setting_{{ $setting->key }}"
                                name="settings[{{ $setting->key }}]"
                                class="form-control"
                                value=""
                                placeholder="Masukkan API key baru..."
                                autocomplete="new-password"
                            >
                            <button type="button" class="icon-right" onclick="togglePasswordVisibility('setting_{{ $setting->key }}')" title="Tampilkan/Sembunyikan">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    @else
                        <input
                            type="text"
                            id="setting_{{ $setting->key }}"
                            name="settings[{{ $setting->key }}]"
                            class="form-control"
                            value="{{ $setting->value }}"
                        >
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light" style="border-radius:10px;padding:0.5rem 1.5rem;font-weight:600">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <button type="submit" class="btn btn-primary-modern" id="btnSaveSettings">
            <i class="bi bi-check-lg me-1"></i>Simpan Pengaturan
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.closest('.input-icon-wrapper').querySelector('.icon-right i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

document.getElementById('btnSaveSettings').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menyimpan...';
});
</script>
@endpush
