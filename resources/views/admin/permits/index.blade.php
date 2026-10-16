@extends('layouts.admin')
@section('title', 'Laporan Perizinan Pangan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.4rem"><i class="bi bi-file-earmark-text me-2" style="color:var(--primary)"></i>Laporan Perizinan Pangan</h4>
        <p class="text-muted mb-0 small">Daftar/Rekap data perizinan pangan (PDUK, BPOM, Halal, SP-IRT) — pemilik usaha, alamat &amp; dokumen perizinan</p>
        <p class="mb-0 mt-1" style="font-size:0.72rem;color:#0ea5e9"><i class="bi bi-info-circle me-1"></i>Modul ini bersifat <strong>laporan data</strong>, bukan penerbitan izin — perizinan tetap dikelola melalui sistem satu pintu (revisi 14 Okt 2026)</p>
    </div>
    <a href="{{ route('admin.permits.create') }}" class="btn btn-primary-modern"><i class="bi bi-plus-lg me-2"></i>Tambah Data Perizinan</a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4 animate-fade-up">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left:4px solid #6366f1">
            <div class="stat-icon" style="background:rgba(99,102,241,0.1);color:#6366f1"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <div class="fw-bold" style="font-size:1.4rem">{{ $stats['total'] }}</div>
                <small class="text-muted">Total</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left:4px solid #f59e0b">
            <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="fw-bold" style="font-size:1.4rem;color:#f59e0b">{{ $stats['pending'] }}</div>
                <small class="text-muted">Menunggu</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left:4px solid #10b981">
            <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-patch-check-fill"></i></div>
            <div>
                <div class="fw-bold" style="font-size:1.4rem;color:#10b981">{{ $stats['approved'] }}</div>
                <small class="text-muted">Disetujui</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left:4px solid #ef4444">
            <div class="stat-icon" style="background:rgba(239,68,68,0.1);color:#ef4444"><i class="bi bi-x-circle-fill"></i></div>
            <div>
                <div class="fw-bold" style="font-size:1.4rem;color:#ef4444">{{ $stats['rejected'] }}</div>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
</div>

<!-- ===== GRAFIK INTERAKTIF PERIZINAN (revisi 15 Okt 2026) ===== -->
@php $chartTotal = array_sum($chartStatus); @endphp
<div class="row g-4 mb-4">
    <div class="col-lg-4 animate-fade-up delay-1">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3 pb-0">
                <h6 class="fw-bold mb-0" style="font-size:0.95rem"><i class="bi bi-pie-chart me-2" style="color:#6366f1"></i>Status Perizinan</h6>
                <small class="text-muted" style="font-size:0.72rem">Klik legend untuk menyaring · hover untuk detail</small>
            </div>
            <div class="p-3">
                @if($chartTotal > 0)
                <div style="height:250px"><canvas id="permitStatusChart"></canvas></div>
                @else
                <div class="text-center text-muted small py-5"><i class="bi bi-pie-chart d-block mb-2" style="font-size:1.8rem;opacity:0.3"></i>Belum ada data untuk grafik status.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-8 animate-fade-up delay-2">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3 pb-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-0" style="font-size:0.95rem"><i class="bi bi-graph-up me-2" style="color:#10b981"></i>Tren Data Perizinan — 6 Bulan Terakhir</h6>
                    <small class="text-muted" style="font-size:0.72rem">Jumlah data masuk per bulan (berdasar tanggal terbit izin)</small>
                </div>
                <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.7rem">{{ array_sum(array_column($chartTrend, 'total')) }} data / 6 bln</span>
            </div>
            <div class="p-3">
                @if(array_sum(array_column($chartTrend, 'total')) > 0)
                <div style="height:250px"><canvas id="permitTrendChart"></canvas></div>
                @else
                <div class="text-center text-muted small py-5"><i class="bi bi-graph-up d-block mb-2" style="font-size:1.8rem;opacity:0.3"></i>Belum ada data tren.</div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-lg-7 animate-fade-up delay-3">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3 pb-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-0" style="font-size:0.95rem"><i class="bi bi-bar-chart-steps me-2" style="color:#8b5cf6"></i>Sebaran Pelaku Usaha per Kecamatan</h6>
                    <small class="text-muted" style="font-size:0.72rem">Sebaran wilayah data perizinan di Kota Tual</small>
                </div>
                <span class="badge-modern" style="background:rgba(139,92,246,0.1);color:#8b5cf6;font-size:0.7rem">{{ $chartKecamatan->sum('total') }} terdata</span>
            </div>
            <div class="p-3">
                @if($chartKecamatan->sum('total') > 0)
                <div style="height:260px"><canvas id="permitKecamatanChart"></canvas></div>
                @else
                <div class="text-center text-muted small py-5"><i class="bi bi-bar-chart-steps d-block mb-2" style="font-size:1.8rem;opacity:0.3"></i>Belum ada data kecamatan.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-5 animate-fade-up delay-4">
        <div class="card border-0 h-100" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3 pb-0">
                <h6 class="fw-bold mb-0" style="font-size:0.95rem"><i class="bi bi-diagram-3 me-2" style="color:#f59e0b"></i>Jenis Perizinan</h6>
                <small class="text-muted" style="font-size:0.72rem">PDUK · BPOM · Halal · SP-IRT · Lainnya</small>
            </div>
            <div class="p-3">
                @if($chartTypes->sum('total') > 0)
                <div style="height:260px"><canvas id="permitTypeChart"></canvas></div>
                @else
                <div class="text-center text-muted small py-5"><i class="bi bi-diagram-3 d-block mb-2" style="font-size:1.8rem;opacity:0.3"></i>Belum ada data jenis perizinan.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 mb-4 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <form method="GET" action="{{ route('admin.permits.index') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Cari Nama Usaha / NIB</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama usaha / NIB / no. perizinan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Jenis Perizinan</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="pduk" {{ request('type') == 'pduk' ? 'selected' : '' }}>PDUK</option>
                    <option value="bpom" {{ request('type') == 'bpom' ? 'selected' : '' }}>BPOM</option>
                    <option value="halal" {{ request('type') == 'halal' ? 'selected' : '' }}>Halal</option>
                    <option value="sp_irt" {{ request('type') == 'sp_irt' ? 'selected' : '' }}>SP-IRT</option>
                    <option value="lainnya" {{ request('type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Kecamatan</label>
                <select name="kecamatan" id="filterKecamatanAdmin" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(array_keys(\App\Support\TualWilayah::kecamatan()) as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>Kec. {{ $kec }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Desa / Kelurahan</label>
                <select name="desa" id="filterDesaAdmin" class="form-select form-select-sm">
                    <option value="">Semua</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary-modern" style="flex:1"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.permits.index') }}" class="btn btn-sm" style="background:rgba(99,102,241,0.08);color:var(--primary);border-radius:8px"><i class="bi bi-x-lg"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card border-0 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0" style="font-size:0.8rem">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Nama Usaha</th>
                        <th>Nama Pelaku Usaha</th>
                        <th>Nomor Izin Berusaha (NIB)</th>
                        <th>Jenis Perizinan</th>
                        <th>Tanggal Terbit</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th style="width:210px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permits as $i => $p)
                    @php
                        $st = $p->permit_status;
                        if ($st === 'approved' && $p->expiry_date && $p->expiry_date->lt(\Illuminate\Support\Carbon::today())) $st = 'expired';
                        $pClr = match($st) { 'pending' => '#f59e0b', 'approved' => '#10b981', 'rejected' => '#ef4444', 'expired' => '#64748b', default => '#64748b' };
                        $pIcon = match($st) { 'pending' => 'hourglass-split', 'approved' => 'patch-check-fill', 'rejected' => 'x-circle-fill', 'expired' => 'clock-history', default => 'dash-circle' };
                        $pLabel = match($st) { 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'expired' => 'Kadaluarsa', default => ucfirst($st ?? '-') };
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $permits->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold">{{ $p->business_name }}</div>
                            @if($p->permit_number)
                                <small class="text-muted" style="font-size:0.65rem"><i class="bi bi-tag me-1"></i>{{ $p->permit_number }}</small>
                            @endif
                            @if($p->kecamatan || $p->desa)
                                <br><small class="text-muted" style="font-size:0.65rem"><i class="bi bi-geo-alt me-1"></i>{{ trim(($p->kecamatan ? 'Kec. '.$p->kecamatan : '').($p->desa ? ', '.$p->desa : ''), ', ') }}</small>
                            @endif
                        </td>
                        <td>
                            <small class="fw-semibold">{{ $p->applicant_name }}</small>
                            @if($p->applicant_phone)
                                <br><small class="text-muted" style="font-size:0.65rem">{{ $p->applicant_phone }}</small>
                            @endif
                        </td>
                        <td>
                            @if($p->nib)
                                <span class="badge" style="background:rgba(99,102,241,0.08);color:var(--primary);font-size:0.68rem;font-family:monospace">{{ $p->nib }}</span>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                            @if($p->nib_document_url)
                                <br><a href="{{ $p->nib_document_url }}" target="_blank" class="small" style="color:var(--primary);text-decoration:none;font-size:0.65rem"><i class="bi bi-file-earmark-arrow-up me-1"></i>Dok. NIB</a>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background:rgba(99,102,241,0.08);color:var(--primary);font-size:0.65rem">{{ $p->permit_type_label }}</span>
                            <br><span class="badge" style="background:{{ $pClr }}18;color:{{ $pClr }};font-size:0.62rem;border-radius:8px;font-weight:600"><i class="bi bi-{{ $pIcon }} me-1"></i>{{ $pLabel }}</span>
                        </td>
                        <td><small>{{ $p->issue_date ? $p->issue_date->format('d/m/Y') : '-' }}</small></td>
                        <td>
                            @if($p->expiry_date)
                                <small class="{{ $st === 'expired' ? 'text-danger fw-bold' : '' }}">{{ $p->expiry_date->format('d/m/Y') }}</small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.permits.edit', $p) }}" class="btn btn-sm btn-primary-modern" style="border-radius:6px;font-size:0.7rem;padding:0.25rem 0.6rem" title="Edit"><i class="bi bi-pencil me-1"></i>Edit</a>
                                <button type="button" class="btn btn-sm" style="background:rgba(16,185,129,0.08);color:#10b981;border-radius:6px;font-size:0.7rem;padding:0.25rem 0.6rem" title="Upload Dokumen NIB" data-bs-toggle="modal" data-bs-target="#modalUploadNib" data-permit-id="{{ $p->id }}" data-permit-name="{{ $p->business_name }}" onclick="prepareUploadNib(this)"><i class="bi bi-file-earmark-arrow-up me-1"></i>NIB</button>
                                <form action="{{ route('admin.permits.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus data perizinan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.08);color:#ef4444;border-radius:6px;font-size:0.7rem" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div style="font-size:2rem;opacity:0.3;margin-bottom:0.5rem"><i class="bi bi-file-earmark-text"></i></div>
                            <p class="text-muted mb-0">Belum ada data perizinan.</p>
                            <a href="{{ route('admin.permits.create') }}" class="btn btn-sm btn-primary-modern mt-2"><i class="bi bi-plus-lg me-1"></i>Tambah Data Perizinan</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($permits->hasPages())
    <div class="card-footer p-3" style="background:var(--bg-card)">
        {{ $permits->withQueryString()->links() }}
    </div>
    @endif
</div>

<!-- Modal Upload Dokumen NIB -->
<div class="modal fade" id="modalUploadNib" tabindex="-1" aria-labelledby="modalUploadNibLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:none">
            <div class="modal-header" style="background:linear-gradient(135deg,rgba(16,185,129,0.08),rgba(99,102,241,0.05));border-bottom:1px solid #e2e8f0">
                <h6 class="modal-title fw-bold" id="modalUploadNibLabel" style="font-size:0.9rem"><i class="bi bi-file-earmark-arrow-up me-2" style="color:#10b981"></i>Upload Dokumen NIB</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="formUploadNib" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-2 small fw-semibold" id="uploadNibTarget" style="color:var(--primary)"></div>
                    <label class="form-label small fw-semibold text-muted">Pilih Dokumen (PDF / JPG / PNG, maks 5MB)</label>
                    <input type="file" name="nib_document" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" required>
                    <small class="text-muted" style="font-size:0.72rem">Dokumen lama (jika ada) akan otomatis diganti dengan yang baru.</small>
                </div>
                <div class="modal-footer" style="border-top:1px solid #e2e8f0">
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background:rgba(100,116,139,0.08);color:#64748b">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-modern"><i class="bi bi-upload me-1"></i>Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ====== Dropdown Desa dinamis (dependen Kecamatan) ======
    const KEC_DESA_ADMIN = @json(\App\Support\TualWilayah::kecamatan());
    const kecSelectAdmin = document.getElementById('filterKecamatanAdmin');
    const desaSelectAdmin = document.getElementById('filterDesaAdmin');
    const selectedDesaAdmin = {{ json_encode(request('desa')) }};

    function renderDesaAdmin() {
        if (!kecSelectAdmin || !desaSelectAdmin) return;
        const kec = kecSelectAdmin.value;
        desaSelectAdmin.innerHTML = '<option value="">Semua</option>';
        (KEC_DESA_ADMIN[kec] || []).forEach(function (d) {
            const opt = document.createElement('option');
            opt.value = d;
            opt.textContent = d;
            if (d === selectedDesaAdmin) opt.selected = true;
            desaSelectAdmin.appendChild(opt);
        });
    }
    if (kecSelectAdmin) {
        renderDesaAdmin();
        kecSelectAdmin.addEventListener('change', renderDesaAdmin);
    }

    // ====== GRAFIK INTERAKTIF PERIZINAN (revisi 15 Okt 2026) ======
    (function initPermitCharts() {
        if (typeof Chart === 'undefined') {
            console.warn('[PANCA] Chart.js belum termuat — grafik perizinan dilewati.');
            return;
        }

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.animation.duration = 1200;
        Chart.defaults.animation.easing = 'easeOutQuart';
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.boxWidth = 8;
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(15,23,42,0.92)';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 10;

        const cssVar = n => getComputedStyle(document.documentElement).getPropertyValue(n).trim();
        const tickColor = '#64748b';

        // ===== 1. DOUGHNUT STATUS PERIZINAN (rotate + stagger + total di tengah) =====
        try {
            const el = document.getElementById('permitStatusChart');
            const data = {{ \Illuminate\Support\Js::from($chartStatus) }};
            const total = {{ (int) $chartTotal }};
            if (el && total > 0) {
                const centerTextPlugin = {
                    id: 'permitCenterText',
                    afterDraw(chart) {
                        const meta = chart.getDatasetMeta(0);
                        if (!meta.data.length) return;
                        const { x, y } = meta.data[0];
                        const ctx = chart.ctx;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "600 11px 'Plus Jakarta Sans', sans-serif";
                        ctx.fillStyle = tickColor;
                        ctx.fillText('Total', x, y - 14);
                        ctx.font = "800 26px 'Plus Jakarta Sans', sans-serif";
                        ctx.fillStyle = cssVar('--text-main') || '#0f172a';
                        ctx.fillText(total, x, y + 10);
                        ctx.restore();
                    }
                };
                new Chart(el, {
                    type: 'doughnut',
                    data: {
                        labels: ['Disetujui ✅', 'Menunggu ⏳', 'Ditolak ❌', 'Kadaluarsa ⌛'],
                        datasets: [{
                            data: [data.approved, data.pending, data.rejected, data.expired],
                            backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#64748b'],
                            borderWidth: 3,
                            borderColor: cssVar('--bg-card') || '#fff',
                            hoverOffset: 12,
                            spacing: 2,
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '62%',
                        animation: { animateRotate: true, animateScale: true, duration: 1400, delay: ctx => ctx.dataIndex * 160 },
                        plugins: {
                            legend: { position: 'bottom', labels: { padding: 12, font: { size: 10 } }, onClick: Chart.defaults.plugins.legend.onClick },
                            tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + ' perizinan (' + Math.round(ctx.parsed / total * 100) + '%)' } }
                        }
                    },
                    plugins: [centerTextPlugin]
                });
            }
        } catch (e) { console.error('[PANCA] permitStatusChart:', e); }

        // ===== 2. LINE/AREA TREN 6 BULAN (progressive draw + titik pantau) =====
        try {
            const el = document.getElementById('permitTrendChart');
            const trend = {{ \Illuminate\Support\Js::from($chartTrend) }};
            if (el && trend.length) {
                const grad = el.getContext('2d').createLinearGradient(0, 0, 0, 260);
                grad.addColorStop(0, 'rgba(16,185,129,0.28)');
                grad.addColorStop(1, 'rgba(16,185,129,0.02)');
                const totalTrend = trend.reduce((a, t) => a + Number(t.total || 0), 0);
                if (totalTrend > 0) {
                    new Chart(el, {
                        type: 'line',
                        data: {
                            labels: trend.map(t => t.label),
                            datasets: [{
                                label: 'Data Perizinan',
                                data: trend.map(t => Number(t.total || 0)),
                                borderColor: '#10b981',
                                backgroundColor: grad,
                                borderWidth: 2.5, fill: true, tension: 0.4,
                                pointRadius: 4, pointHoverRadius: 8,
                                pointBackgroundColor: '#fff', pointBorderColor: '#10b981', pointBorderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            animation: { duration: 1600, delay: ctx => ctx.dataIndex * 120 },
                            plugins: {
                                legend: { display: false },
                                tooltip: { callbacks: { label: ctx => ctx.parsed.y + ' data perizinan masuk' } }
                            },
                            scales: {
                                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0, color: tickColor, font: { size: 10 } }, grid: { color: 'rgba(100,116,139,0.08)' } },
                                x: { grid: { display: false }, ticks: { color: tickColor, font: { size: 10 } } }
                            },
                            interaction: { intersect: false, mode: 'index' }
                        }
                    });
                }
            }
        } catch (e) { console.error('[PANCA] permitTrendChart:', e); }

        // ===== 3. HORIZONTAL BAR PER KECAMATAN (stagger kiri→kanan + hover) =====
        try {
            const el = document.getElementById('permitKecamatanChart');
            const kec = {{ \Illuminate\Support\Js::from($chartKecamatan) }};
            if (el && kec.length && kec.some(k => k.total > 0)) {
                const palette = ['#8b5cf6', '#6366f1', '#10b981', '#f59e0b', '#0ea5e9', '#ef4444'];
                new Chart(el, {
                    type: 'bar',
                    data: {
                        labels: kec.map(k => 'Kec. ' + k.label),
                        datasets: [{
                            label: 'Pelaku Usaha',
                            data: kec.map(k => Number(k.total || 0)),
                            backgroundColor: kec.map((_, i) => palette[i % palette.length] + 'CC'),
                            hoverBackgroundColor: kec.map((_, i) => palette[i % palette.length]),
                            borderRadius: 8,
                            maxBarThickness: 30,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true, maintainAspectRatio: false,
                        animation: { duration: 1100, easing: 'easeOutQuart', delay: ctx => ctx.dataIndex * 140 },
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: ctx => ctx.parsed.x + ' pelaku usaha terdata' } }
                        },
                        scales: {
                            x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0, color: tickColor, font: { size: 10 } }, grid: { color: 'rgba(100,116,139,0.08)' } },
                            y: { grid: { display: false }, ticks: { color: tickColor, font: { size: 10 } } }
                        }
                    }
                });
            }
        } catch (e) { console.error('[PANCA] permitKecamatanChart:', e); }

        // ===== 4. DOUGHNUT JENIS PERIZINAN (rotate + hover mekar) =====
        try {
            const el = document.getElementById('permitTypeChart');
            const types = {{ \Illuminate\Support\Js::from($chartTypes) }};
            if (el && types.length && types.some(t => t.total > 0)) {
                new Chart(el, {
                    type: 'doughnut',
                    data: {
                        labels: types.map(t => t.label),
                        datasets: [{
                            data: types.map(t => Number(t.total || 0)),
                            backgroundColor: ['#6366f1', '#0ea5e9', '#10b981', '#f59e0b', '#8b5cf6'],
                            borderWidth: 3,
                            borderColor: cssVar('--bg-card') || '#fff',
                            hoverOffset: 12,
                            spacing: 2,
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '55%',
                        animation: { animateRotate: true, animateScale: true, duration: 1400, delay: ctx => ctx.dataIndex * 150 },
                        plugins: {
                            legend: { position: 'bottom', labels: { padding: 10, font: { size: 10 } } },
                            tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + ' perizinan' } }
                        }
                    }
                });
            }
        } catch (e) { console.error('[PANCA] permitTypeChart:', e); }
    })();

    // ====== Modal Upload Dokumen NIB ======
    function prepareUploadNib(btn) {
        const id = btn.getAttribute('data-permit-id');
        const name = btn.getAttribute('data-permit-name');
        document.getElementById('formUploadNib').action = '{{ url('admin/permits') }}/' + id + '/upload-nib';
        document.getElementById('uploadNibTarget').textContent = 'Untuk: ' + name;
        document.getElementById('formUploadNib').reset();
    }
</script>
@endpush
