@extends('layouts.admin')
@section('title', 'Backup & Restore - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem">Backup & Restore Database 💾</h4>
        <p class="text-muted mb-0 small">Kelola backup dan restore database Pangan Cerdas (Panca) Maryadat Kota Tual</p>
    </div>
</div>

<!-- Action Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6 animate-fade-up delay-1">
        <div class="card border-0 p-4 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="avatar" style="background:linear-gradient(135deg,#10b981,#34d399);width:48px;height:48px;font-size:1.2rem">
                    <i class="bi bi-download"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Buat Backup</h6>
                    <small class="text-muted">Download snapshot database saat ini</small>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.backup.create') }}">
                @csrf
                <button type="submit" class="btn btn-primary-modern w-100" onclick="this.innerHTML='<i class=\'bi bi-hourglass-split me-2\'></i>Membuat backup...';this.disabled=true">
                    <i class="bi bi-database-add me-2"></i>Backup Sekarang
                </button>
            </form>
        </div>
    </div>
    <div class="col-md-6 animate-fade-up delay-2">
        <div class="card border-0 p-4 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="avatar" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);width:48px;height:48px;font-size:1.2rem">
                    <i class="bi bi-upload"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Restore Database</h6>
                    <small class="text-muted">Upload file backup (.sql) untuk restore</small>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.backup.restore') }}" enctype="multipart/form-data" onsubmit="return confirm('⚠️ PERHATIAN: Restore akan menimpa data yang ada. Lanjutkan?')">
                @csrf
                <input type="file" name="backup_file" accept=".sql,.txt" class="form-control mb-2" required>
                <button type="submit" class="btn w-100" style="background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;border:none;border-radius:12px;font-weight:600">
                    <i class="bi bi-cloud-upload me-2"></i>Restore Database
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Backup History -->
<div class="card border-0 animate-fade-up delay-3" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-4 pb-2">
        <h6 class="fw-bold"><i class="bi bi-clock-history me-2" style="color:var(--primary)"></i>Riwayat Backup</h6>
    </div>
    <div class="table-responsive px-3 pb-3">
        @forelse($backups as $backup)
        <table class="table table-modern mb-2">
            <thead>
                <tr>
                    <th colspan="4" style="background:transparent"><small class="text-muted">File: {{ $backup['name'] }}</small></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="small"><i class="bi bi-file-earmark-code me-1"></i>{{ $backup['name'] }}</td>
                    <td class="small"><i class="bi bi-hdd me-1"></i>{{ $backup['size'] }}</td>
                    <td class="small"><i class="bi bi-calendar3 me-1"></i>{{ $backup['date'] }}</td>
                    <td>
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.backup.download', $backup['name']) }}" class="btn btn-sm" style="background:rgba(16,185,129,0.1);color:#10b981;border-radius:8px" title="Download"><i class="bi bi-download"></i></a>
                            <form method="POST" action="{{ route('admin.backup.destroy', $backup['name']) }}" onsubmit="return confirm('Hapus backup ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-database-slash display-4 text-muted"></i>
            <p class="text-muted mt-2">Belum ada backup</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
