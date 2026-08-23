@extends('layouts.admin')
@section('title', 'Manage User - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem">Manage User 👥</h4>
        <p class="text-muted mb-0 small">Kelola semua pengguna sistem Pangan Cerdas (Panca) Maryadat Kota Tual</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary-modern"><i class="bi bi-person-plus me-2"></i>Tambah User</a>
</div>

<!-- Filter -->
<div class="card border-0 p-3 mb-4 animate-fade-up delay-1" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label small fw-semibold text-muted">Cari</label>
            <div class="input-icon-wrapper">
                <i class="bi bi-search icon-left"></i>
                <input type="text" name="search" class="form-control" placeholder="Nama atau email..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold text-muted">Role</label>
            <select name="role" class="form-select">
                <option value="">Semua Role</option>
                <option value="super_admin" {{ request('role')==='super_admin'?'selected':'' }}>Super Admin</option>
                <option value="opd" {{ request('role')==='opd'?'selected':'' }}>OPD</option>
                <option value="umkm" {{ request('role')==='umkm'?'selected':'' }}>UMKM</option>
                <option value="buyer" {{ request('role')==='buyer'?'selected':'' }}>Buyer</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary-modern w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:12px"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card border-0 animate-fade-up delay-2" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $i => $user)
                @php
                    $r = $user->role;
                    if ($r === 'super_admin') { $roleBg = 'linear-gradient(135deg,#ef4444,#f97316)'; $roleBadgeBg = 'rgba(239,68,68,0.1)'; $roleBadgeColor = '#ef4444'; }
                    elseif ($r === 'opd') { $roleBg = 'linear-gradient(135deg,#6366f1,#0ea5e9)'; $roleBadgeBg = 'rgba(99,102,241,0.1)'; $roleBadgeColor = '#6366f1'; }
                    elseif ($r === 'umkm') { $roleBg = 'linear-gradient(135deg,#10b981,#34d399)'; $roleBadgeBg = 'rgba(16,185,129,0.1)'; $roleBadgeColor = '#10b981'; }
                    else { $roleBg = 'linear-gradient(135deg,#64748b,#94a3b8)'; $roleBadgeBg = 'rgba(100,116,139,0.1)'; $roleBadgeColor = '#64748b'; }
                    $s = $user->status;
                    if ($s === 'active') { $statusBg = 'rgba(16,185,129,0.1)'; $statusColor = '#10b981'; }
                    elseif ($s === 'inactive') { $statusBg = 'rgba(245,158,11,0.1)'; $statusColor = '#f59e0b'; }
                    else { $statusBg = 'rgba(239,68,68,0.1)'; $statusColor = '#ef4444'; }
                @endphp
                <tr>
                    <td class="small text-muted">{{ $users->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar" style="background:{{ $roleBg }};width:36px;height:36px;font-size:0.75rem">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-modern" style="background:{{ $roleBadgeBg }};color:{{ $roleBadgeColor }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>
                    <td><small class="text-muted">{{ $user->phone ?: '-' }}</small></td>
                    <td>
                        <span class="badge-modern" style="background:{{ $statusBg }};color:{{ $statusColor }}">
                            <i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>{{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td><small class="text-muted">{{ $user->created_at->format('d/m/Y') }}</small></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:#6366f1;border-radius:8px;width:32px;height:32px;display:flex;align-items:center;justify-content:center" title="Edit"><i class="bi bi-pencil"></i></a>
                            @if($user->id !== auth()->id())
                            <button class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px;width:32px;height:32px;display:flex;align-items:center;justify-content:center" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" title="Hapus"><i class="bi bi-trash"></i></button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="p-3 d-flex justify-content-center">
        {{ $users->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<form id="deleteForm" method="POST" action="">
    @csrf @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
function confirmDelete(id, name) {
    if(confirm('Yakin ingin menghapus user "' + name + '"?')) {
        const form = document.getElementById('deleteForm');
        form.action = "{{ route('admin.users.destroy', 0) }}".replace('/0', '/' + id);
        form.submit();
    }
}
</script>
@endsection
