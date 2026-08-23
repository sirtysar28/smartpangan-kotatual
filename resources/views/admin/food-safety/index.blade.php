@extends('layouts.admin')
@section('title', 'Keamanan Pangan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem">🛡️ Keamanan Pangan</h4>
        <p class="text-muted mb-0 small">Pemeriksaan pangan segar, pengawasan kemasan, dan kegiatan keamanan pangan di Kota Tual</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.food-safety.check.create') }}" class="btn btn-primary-modern"><i class="bi bi-plus-lg me-2"></i>Pemeriksaan Baru</a>
        <a href="{{ route('admin.food-safety.activity.create') }}" class="btn" style="background:rgba(245,158,11,0.1);color:#f59e0b;border-radius:10px;font-weight:600"><i class="bi bi-clipboard-plus me-2"></i>Kegiatan Baru</a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 animate-fade-up delay-1">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#6366f1,#818cf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Total Pemeriksaan</small>
                    <div class="stat-value mt-1" style="color:#6366f1">{{ $summary['total_checks'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(99,102,241,0.1)"><i class="bi bi-clipboard-check" style="color:#6366f1"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-2">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#10b981,#34d399);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Layak ✅</small>
                    <div class="stat-value mt-1" style="color:#10b981">{{ $summary['layak'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.1)"><i class="bi bi-check-circle" style="color:#10b981"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-3">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#ef4444,#f87171);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Tidak Layak 🔴</small>
                    <div class="stat-value mt-1" style="color:#ef4444">{{ $summary['tidak_layak'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(239,68,68,0.1)"><i class="bi bi-x-circle" style="color:#ef4444"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 animate-fade-up delay-4">
        <div class="stat-card card p-3">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#0ea5e9,#38bdf8);border-radius:3px 3px 0 0"></div>
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <small class="text-muted fw-medium" style="font-size:0.75rem">Kegiatan</small>
                    <div class="stat-value mt-1" style="color:#0ea5e9">{{ $summary['total_activities'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(14,165,233,0.1)"><i class="bi bi-activity" style="color:#0ea5e9"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- TABS: Pemeriksaan & Kegiatan -->
<ul class="nav nav-tabs mb-4 animate-fade-up" id="foodSafetyTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="check-tab" data-bs-toggle="tab" data-bs-target="#checkPanel" type="button" role="tab" style="font-weight:600;font-size:0.85rem">
            <i class="bi bi-clipboard-check me-1"></i>Data Pemeriksaan
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activityPanel" type="button" role="tab" style="font-weight:600;font-size:0.85rem">
            <i class="bi bi-people me-1"></i>Kegiatan Keamanan Pangan
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB 1: Data Pemeriksaan Keamanan Pangan -->
    <div class="tab-pane fade show active" id="checkPanel" role="tabpanel">
        <div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-table me-2" style="color:var(--primary)"></i>Rekap Pemeriksaan Keamanan Pangan</h6>
                    <form class="d-flex gap-2" method="GET">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari..." value="{{ request('search') }}" style="min-width:150px">
                        <select name="food_source_type" class="form-select form-select-sm" style="min-width:130px">
                            <option value="">Semua Jenis</option>
                            <option value="tumbuhan" {{ request('food_source_type') === 'tumbuhan' ? 'selected' : '' }}>Tumbuhan</option>
                            <option value="ikan" {{ request('food_source_type') === 'ikan' ? 'selected' : '' }}>Ikan</option>
                            <option value="hewan" {{ request('food_source_type') === 'hewan' ? 'selected' : '' }}>Hewan</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary-modern" style="border-radius:8px"><i class="bi bi-search"></i></button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern" style="font-size:0.75rem">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis/Asal</th>
                                <th>Nama Pangan</th>
                                <th>Distributor</th>
                                <th>Pengecer</th>
                                <th>Kondisi</th>
                                <th>Kemasan</th>
                                <th>BP POM</th>
                                <th>Halal</th>
                                <th>PDUK</th>
                                <th>Hasil</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($safeties as $s)
                            <tr style="border-left:3px solid {{ $s->inspection_result_color }}">
                                <td>{{ $s->check_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge" style="background:{{ $s->source_color }}15;color:{{ $s->source_color }};font-size:0.6rem;border-radius:6px">
                                        <i class="bi bi-{{ $s->food_source_type === 'tumbuhan' ? 'flower1' : ($s->food_source_type === 'ikan' ? 'fish' : 'emoji-heart-eyes') }} me-1"></i>{{ $s->source_type_label }}
                                    </span>
                                </td>
                                <td class="fw-semibold">{{ $s->food_name }}</td>
                                <td>{{ $s->distributor_name ?? '-' }}</td>
                                <td>{{ $s->retailer_name ?? '-' }}</td>
                                <td>{{ $s->food_condition_label }}</td>
                                <td>
                                    {{ $s->packaging_type ? $s->packaging_type : '-' }}
                                    @if($s->packaging_netto)
                                        <br><small class="text-muted">{{ $s->packaging_netto }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($s->bpom_number)
                                        <span class="badge" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.6rem;border-radius:6px"><i class="bi bi-check-circle me-1"></i>{{ $s->bpom_number }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->is_halal)
                                        <span class="badge" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.6rem;border-radius:6px">Halal</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->is_pduk)
                                        <span class="badge" style="background:rgba(14,165,233,0.1);color:#0ea5e9;font-size:0.6rem;border-radius:6px">PDUK</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background:{{ $s->inspection_result_color }}15;color:{{ $s->inspection_result_color }};font-size:0.65rem;border-radius:6px;font-weight:600">
                                        {{ $s->inspection_result_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.food-safety.check.edit', $s) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:var(--primary);border-radius:6px;font-size:0.65rem"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.food-safety.check.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:6px;font-size:0.65rem"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="12" class="text-center py-4 text-muted">Belum ada data pemeriksaan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">{{ $safeties->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>

    <!-- TAB 2: Kegiatan Keamanan Pangan -->
    <div class="tab-pane fade" id="activityPanel" role="tabpanel">
        <div class="row g-3 mb-4">
            @forelse($activities as $act)
            <div class="col-md-6 col-lg-4 animate-fade-up">
                <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden">
                    @if($act->evidence_photo_url)
                    <div style="height:180px;overflow:hidden">
                        <img src="{{ $act->evidence_photo_url }}" alt="{{ $act->title }}" style="width:100%;height:100%;object-fit:cover">
                    </div>
                    @endif
                    <div class="p-3">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <span class="badge" style="background:rgba(99,102,241,0.1);color:var(--primary);font-size:0.6rem;border-radius:6px">
                                    <i class="bi bi-{{ $act->activity_type_icon }} me-1"></i>{{ $act->activity_type_label }}
                                </span>
                                <h6 class="fw-bold mt-1 mb-0" style="font-size:0.9rem">{{ $act->title }}</h6>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mb-2 small">
                            <span class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $act->activity_date->format('d/m/Y') }}</span>
                            @if($act->location)
                            <span class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $act->location }}</span>
                            @endif
                        </div>
                        @if($act->participants_count > 0)
                            <span class="badge-modern" style="background:rgba(14,165,233,0.1);color:#0ea5e9;font-size:0.6rem"><i class="bi bi-people me-1"></i>{{ $act->participants_count }} peserta</span>
                        @endif
                        @if($act->description)
                            <p class="text-muted mt-2 mb-2" style="font-size:0.78rem">{{ \Illuminate\Support\Str::limit($act->description, 100) }}</p>
                        @endif
                        @if($act->outcome)
                            <div class="p-2 mt-2" style="border-radius:8px;background:rgba(16,185,129,0.04);border:1px solid rgba(16,185,129,0.1)">
                                <small class="text-muted" style="font-size:0.7rem"><strong>Hasil:</strong> {{ \Illuminate\Support\Str::limit($act->outcome, 80) }}</small>
                            </div>
                        @endif
                        <div class="d-flex gap-1 mt-3">
                            <a href="{{ route('admin.food-safety.activity.edit', $act) }}" class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:var(--primary);border-radius:6px;font-size:0.72rem"><i class="bi bi-pencil me-1"></i>Edit</a>
                            <form action="{{ route('admin.food-safety.activity.destroy', $act) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kegiatan?')">@csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:6px;font-size:0.72rem"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-clipboard display-4 text-muted"></i>
                <p class="text-muted mt-2">Belum ada kegiatan keamanan pangan</p>
            </div>
            @endforelse
        </div>
        <div class="d-flex justify-content-center">{{ $activities->appends(['food_source_type' => request('food_source_type'), 'search' => request('search')])->links() }}</div>
    </div>
</div>

@endsection
