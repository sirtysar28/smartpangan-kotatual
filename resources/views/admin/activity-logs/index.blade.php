@extends('layouts.admin')
@section('title', 'Aktivitas Log - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-clock-history me-2"></i>Aktivitas Log 📋</h4>
        <p class="text-muted mb-0 small">Riwayat semua aktivitas di sistem Pangan Cerdas (Panca) Maryadat Kota Tual</p>
    </div>
    <form method="POST" action="{{ route('admin.activity-logs.clear') }}" onsubmit="return confirm('Yakin ingin menghapus SEMUA log?')">
        @csrf @method('POST')
        <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:10px"><i class="bi bi-trash3 me-1"></i>Hapus Semua</button>
    </form>
</div>

<!-- Filter -->
<div class="card border-0 p-3 mb-4 animate-fade-up delay-1" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold text-muted">Pengguna</label>
            <input type="text" name="user_search" class="form-control" placeholder="Cari nama user..." value="{{ request('user_search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold text-muted">Module</label>
            <select name="module" class="form-select">
                <option value="">Semua</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>{{ ucfirst($mod) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold text-muted">Dari</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold text-muted">Sampai</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary-modern flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary" style="border-radius:10px"><i class="bi bi-arrow-counterclockwise"></i></a>
        </div>
    </form>
</div>

<!-- Timeline -->
<div class="card border-0 animate-fade-up delay-2" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-4">
        @forelse($logs as $log)
            @php
                $action = $log->action;
                if ($action === 'create') { $actionBg = 'linear-gradient(135deg,#10b981,#34d399)'; $actionIcon = 'plus-lg'; $actionBadgeBg = 'rgba(16,185,129,0.1)'; $actionColor = '#10b981'; }
                elseif ($action === 'update') { $actionBg = 'linear-gradient(135deg,#0ea5e9,#38bdf8)'; $actionIcon = 'pencil'; $actionBadgeBg = 'rgba(14,165,233,0.1)'; $actionColor = '#0ea5e9'; }
                elseif ($action === 'delete') { $actionBg = 'linear-gradient(135deg,#ef4444,#f97316)'; $actionIcon = 'trash'; $actionBadgeBg = 'rgba(239,68,68,0.1)'; $actionColor = '#ef4444'; }
                else { $actionBg = 'linear-gradient(135deg,#6366f1,#818cf8)'; $actionIcon = 'info-lg'; $actionBadgeBg = 'rgba(99,102,241,0.1)'; $actionColor = '#6366f1'; }
            @endphp
            <div class="d-flex gap-3 mb-3 p-3" style="border-radius:12px;background:var(--bg);border:1px solid #e2e8f0">
                <div class="flex-shrink-0">
                    <div class="avatar" style="background:{{ $actionBg }};width:40px;height:40px;font-size:0.85rem">
                        <i class="bi bi-{{ $actionIcon }}"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <div class="fw-semibold small">
                                @if($log->user)
                                    {{ $log->user->name }}
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                                <span class="badge-modern ms-2" style="background:{{ $actionBadgeBg }};color:{{ $actionColor }}">{{ ucfirst($action) }}</span>
                                @if($log->module)
                                    <span class="badge-modern ms-1" style="background:rgba(100,116,139,0.1);color:#64748b">{{ $log->module }}</span>
                                @endif
                            </div>
                            <small class="text-muted" style="font-size:0.8rem">{{ $log->description }}</small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                            <small class="text-muted d-block" style="font-size:0.7rem">{{ $log->ip_address }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-clock-history display-4 text-muted"></i>
                <p class="text-muted mt-2">Belum ada log aktivitas</p>
            </div>
        @endforelse
    </div>
    @if($logs->hasPages())
    <div class="p-3 d-flex justify-content-center">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection