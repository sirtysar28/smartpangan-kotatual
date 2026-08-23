@extends('layouts.app')
@section('title', 'Keamanan Pangan - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('styles')
<style>
    .check-row { cursor: pointer; transition: all 0.2s; }
    .check-row:hover { background: rgba(99,102,241,0.04) !important; }
    .activity-card { cursor: pointer; transition: all 0.3s; }
    .activity-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg) !important; }

    /* Detail Popup */
    .detail-popup-overlay {
        position: fixed; inset: 0; z-index: 100000;
        background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);
        display: none; align-items: center; justify-content: center;
        padding: 1rem; animation: popupFadeIn 0.25s ease;
    }
    .detail-popup-overlay.active { display: flex; }
    @keyframes popupFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes popupSlideUp { from { opacity: 0; transform: translateY(40px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .detail-popup-modal {
        background: var(--bg-card); border-radius: 20px;
        width: 100%; max-width: 640px;
        box-shadow: 0 25px 80px rgba(0,0,0,0.3);
        animation: popupSlideUp 0.4s cubic-bezier(0.16,1,0.3,1);
        position: relative; overflow: hidden;
        max-height: 90vh; overflow-y: auto;
    }
    .detail-popup-close {
        position: absolute; top: 12px; right: 12px; z-index: 10;
        width: 36px; height: 36px; border-radius: 50%;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(8px);
        border: none; color: #fff; font-size: 0.85rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    .detail-popup-close:hover { background: rgba(239,68,68,0.8); transform: scale(1.1); }
    .detail-popup-header {
        padding: 1.5rem 1.5rem 1rem; position: relative;
        background: linear-gradient(135deg, #0c4a6e, #0ea5e9);
        color: #fff;
    }
    .detail-popup-body { padding: 1.25rem; }
    .detail-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .detail-info-item {
        padding: 0.75rem; border-radius: 12px;
        background: rgba(99,102,241,0.04); border: 1px solid rgba(99,102,241,0.08);
    }
    .detail-info-item .info-label {
        font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;
        color: #94a3b8; font-weight: 600; margin-bottom: 4px;
    }
    .detail-info-item .info-value {
        font-size: 0.85rem; font-weight: 600; color: var(--text-main);
        word-break: break-word;
    }
    .detail-info-item.full { grid-column: 1 / -1; }
    .detail-cert-row {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.6rem 0.85rem; border-radius: 10px; margin-bottom: 0.5rem;
    }
    .detail-cert-row.cert-yes {
        background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.12);
    }
    .detail-cert-row.cert-no {
        background: rgba(148,163,184,0.04); border: 1px solid rgba(148,163,184,0.08);
    }
    .detail-cert-row .cert-icon { font-size: 1.1rem; flex-shrink: 0; }
    .detail-cert-row .cert-name { font-size: 0.82rem; font-weight: 600; }
    .detail-cert-row .cert-number {
        font-size: 0.72rem; color: #94a3b8; margin-left: auto;
    }
    .detail-photo-grid { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .detail-photo-grid img {
        width: 100px; height: 100px; border-radius: 10px; object-fit: cover;
        border: 1px solid #e2e8f0; cursor: pointer; transition: all 0.2s;
    }
    .detail-photo-grid img:hover { transform: scale(1.05); box-shadow: var(--shadow); }
    html.dark-mode .detail-popup-modal { background: #1e293b; }
    html.dark-mode .detail-info-item { background: rgba(99,102,241,0.06); border-color: rgba(99,102,241,0.12); }
    html.dark-mode .detail-info-item .info-label { color: #64748b; }
    html.dark-mode .detail-info-item .info-value { color: #e2e8f0; }
    html.dark-mode .detail-cert-row.cert-no { background: rgba(148,163,184,0.06); border-color: rgba(148,163,184,0.12); }
    html.dark-mode .detail-photo-grid img { border-color: #334155; }
    @media (max-width: 575.98px) {
        .detail-popup-modal { max-width: 100%; border-radius: 16px 16px 0 0; }
        .detail-popup-overlay.active { align-items: flex-end; }
        .detail-info-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection
@section('content')

<!-- Header -->
<section class="py-4" style="background:linear-gradient(135deg,#0f172a,#0c4a6e)">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <nav aria-label="breadcrumb" style="font-size:0.72rem">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;margin:0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.5);text-decoration:none">Beranda</a></li>
                        <li class="breadcrumb-item active" style="color:#0ea5e9">Keamanan Pangan</li>
                    </ol>
                </nav>
                <h4 class="fw-bold text-white mb-1"><i class="bi bi-shield-check me-2"></i>Keamanan Pangan</h4>
                <small class="text-white-50">Pemeriksaan dan kegiatan keamanan mutu pangan di Kota Tual</small>
            </div>
            <div class="d-flex gap-2">
                <span class="badge-modern" style="background:rgba(14,165,233,0.15);color:#0ea5e9"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>{{ $stats['total_checks'] }} Pemeriksaan</span>
                <span class="badge-modern" style="background:rgba(16,185,129,0.15);color:#10b981"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>{{ $stats['total_activities'] }} Kegiatan</span>
            </div>
        </div>
    </div>
</section>

<!-- Stats Cards -->
<section class="py-4" style="background:var(--bg-card)">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #10b981">
                    <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#10b981">{{ $stats['layak'] }}</div>
                        <small class="text-muted">Layak Konsumsi</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #ef4444">
                    <div class="stat-icon" style="background:rgba(239,68,68,0.1);color:#ef4444"><i class="bi bi-x-circle-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#ef4444">{{ $stats['tidak_layak'] }}</div>
                        <small class="text-muted">Tidak Layak</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #f59e0b">
                    <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#f59e0b">{{ $stats['perlu_tindak_lanjut'] }}</div>
                        <small class="text-muted">Perlu Tindak Lanjut</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #6366f1">
                    <div class="stat-icon" style="background:rgba(99,102,241,0.1);color:#6366f1"><i class="bi bi-clipboard-data"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:var(--primary)">{{ $stats['total_checks'] }}</div>
                        <small class="text-muted">Total Pemeriksaan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tabs -->
<section class="py-4" style="background:var(--bg)">
    <div class="container">
        <ul class="nav nav-tabs mb-3" role="tablist" style="border-bottom:2px solid #e2e8f0">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#checkPanel" type="button" role="tab" style="font-size:0.85rem;font-weight:600;border-radius:8px 8px 0 0">
                    <i class="bi bi-clipboard2-check me-1"></i>Pemeriksaan
                    <span class="badge ms-1" style="background:rgba(14,165,233,0.1);color:#0ea5e9;font-size:0.65rem">{{ $stats['total_checks'] }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activityPanel" type="button" role="tab" style="font-size:0.85rem;font-weight:600;border-radius:8px 8px 0 0">
                    <i class="bi bi-calendar-event me-1"></i>Kegiatan
                    <span class="badge ms-1" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.65rem">{{ $stats['total_activities'] }}</span>
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Pemeriksaan Tab -->
            <div class="tab-pane fade show active" id="checkPanel" role="tabpanel">
                <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0" style="font-size:0.82rem">
                                <thead>
                                    <tr>
                                        <th style="width:40px">#</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pangan</th>
                                        <th>Asal</th>
                                        <th>Pelaku Usaha</th>
                                        <th>Sertifikasi</th>
                                        <th>Hasil</th>
                                        <th style="width:30px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($checks as $i => $check)
                                    @php
                                        $rawResult = $check->inspection_result ?? '-';
                                        $fcResult = $check->inspection_result_label ?? $rawResult;
                                        // Color based on raw DB value
                                        $rl = strtolower($rawResult);
                                        if ($rl === '-' || $rawResult === '-') {
                                            $fcClr = '#64748b';
                                        } elseif (strpos($rl, 'tidak') !== false) {
                                            $fcClr = '#ef4444';
                                        } elseif (strpos($rl, 'layak') !== false) {
                                            $fcClr = '#10b981';
                                        } else {
                                            $fcClr = '#f59e0b';
                                        }
                                        // Icon
                                        if ($rl === '-' || $rawResult === '-') {
                                            $fcIcon = 'dash-circle';
                                        } elseif (strpos($rl, 'tidak') !== false) {
                                            $fcIcon = 'x-circle-fill';
                                        } elseif (strpos($rl, 'layak') !== false) {
                                            $fcIcon = 'check-circle-fill';
                                        } elseif (strpos($rl, 'tindak') !== false) {
                                            $fcIcon = 'exclamation-triangle-fill';
                                        } else {
                                            $fcIcon = 'dash-circle';
                                        }
                                    @endphp
                                    <tr class="check-row safety-check-row" data-check-id="{{ $check->id }}">
                                        <td class="text-muted">{{ $checks->firstItem() + $i }}</td>
                                        <td>{{ $check->check_date?->format('d/m/Y') }}</td>
                                        <td class="fw-semibold">{{ $check->food_name }}</td>
                                        <td><span class="badge" style="background:rgba(99,102,241,0.08);color:var(--primary);font-size:0.65rem">{{ $check->source_type_label ?? $check->food_source_type ?? '-' }}</span></td>
                                        <td>
                                            <small class="fw-medium">{{ $check->distributor_name ?? '-' }}</small>
                                            @if($check->retailer_name)
                                                <br><small class="text-muted">{{ $check->retailer_name }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <span title="{{ $check->bpom_number ? 'BPOM: '.$check->bpom_number : 'Belum ada BPOM' }}">
                                                    <i class="bi bi-{{ $check->bpom_number ? 'check-circle-fill' : 'dash-circle' }}" style="color:{{ $check->bpom_number ? '#10b981' : '#94a3b8' }};font-size:0.8rem"></i>
                                                </span>
                                                <span title="{{ $check->is_halal ? 'Halal'.($check->halal_number ? ': '.$check->halal_number : '') : 'Belum Halal' }}">
                                                    <i class="bi bi-{{ $check->is_halal ? 'check-circle-fill' : 'dash-circle' }}" style="color:{{ $check->is_halal ? '#10b981' : '#94a3b8' }};font-size:0.8rem"></i>
                                                </span>
                                                <span title="{{ $check->is_pduk ? 'PDUK'.($check->pduk_number ? ': '.$check->pduk_number : '') : 'Belum PDUK' }}">
                                                    <i class="bi bi-{{ $check->is_pduk ? 'check-circle-fill' : 'dash-circle' }}" style="color:{{ $check->is_pduk ? '#10b981' : '#94a3b8' }};font-size:0.8rem"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td><span class="badge" style="background:{{ $fcClr }}22;color:{{ $fcClr }};font-size:0.7rem"><i class="bi bi-{{ $fcIcon }} me-1"></i>{{ $fcResult }}</span></td>
                                        <td><i class="bi bi-eye" style="color:var(--primary);opacity:0.4;font-size:0.8rem"></i></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div style="font-size:2rem;opacity:0.3;margin-bottom:0.5rem"><i class="bi bi-clipboard2-check"></i></div>
                                            <p class="text-muted mb-0">Belum ada data pemeriksaan keamanan pangan.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($checks->hasPages())
                    <div class="card-footer p-3" style="background:var(--bg-card)">{{ $checks->links() }}</div>
                    @endif
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted"><i class="bi bi-cursor me-1"></i>Klik baris untuk melihat detail lengkap</small>
                </div>
            </div>

            <!-- Kegiatan Tab -->
            <div class="tab-pane fade" id="activityPanel" role="tabpanel">
                <div class="row g-3">
                    @forelse($activities as $activity)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 h-100 activity-card" data-activity-id="{{ $activity->id }}" style="border-radius:var(--radius);box-shadow:var(--shadow-sm)">
                            @if($activity->evidence_photo)
                                <div style="overflow:hidden;border-radius:var(--radius) var(--radius) 0 0;height:180px">
                                    <img src="{{ Storage::url($activity->evidence_photo) }}" alt="{{ $activity->title }}" style="width:100%;height:100%;object-fit:cover" loading="lazy">
                                </div>
                            @else
                            <div style="height:80px;background:linear-gradient(135deg,rgba(14,165,233,0.08),rgba(16,185,129,0.06));display:flex;align-items:center;justify-content:center;border-radius:var(--radius) var(--radius) 0 0">
                                <i class="bi bi-calendar-event" style="font-size:2rem;color:#0ea5e9;opacity:0.3"></i>
                            </div>
                            @endif
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge" style="background:rgba(14,165,233,0.08);color:#0ea5e9;font-size:0.65rem">
                                        <i class="bi bi-{{ $activity->activity_type_icon ?? 'activity' }} me-1"></i>{{ $activity->activity_type_label ?? '-' }}
                                    </span>
                                    <span class="badge" style="background:rgba(99,102,241,0.08);color:var(--primary);font-size:0.65rem">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $activity->activity_date?->format('d/m/Y') }}
                                    </span>
                                </div>
                                <h6 class="fw-bold mb-2" style="font-size:0.88rem">{{ $activity->title }}</h6>
                                <p class="text-muted small mb-2">{{ Str::limit($activity->description, 100) }}</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $activity->location ?? '-' }}</small>
                                    @if($activity->participants_count)
                                        <small class="text-muted"><i class="bi bi-people me-1"></i>{{ $activity->participants_count }} peserta</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <div style="font-size:2rem;opacity:0.3;margin-bottom:0.5rem"><i class="bi bi-calendar-event"></i></div>
                        <p class="text-muted mb-0">Belum ada data kegiatan keamanan pangan.</p>
                    </div>
                    @endforelse
                </div>
                @if($activities->hasPages())
                <div class="mt-3">{{ $activities->links() }}</div>
                @endif
                <div class="text-center mt-2">
                    <small class="text-muted"><i class="bi bi-cursor me-1"></i>Klik kartu untuk melihat detail lengkap</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Legend -->
<section class="py-4" style="background:var(--bg-card)">
    <div class="container">
        <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-info-circle me-2" style="color:var(--primary)"></i>Penjelasan Sertifikasi</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 d-flex align-items-start gap-2" style="border-radius:var(--radius-sm);background:rgba(16,185,129,0.04);border:1px solid rgba(16,185,129,0.1)">
                    <i class="bi bi-check-circle-fill" style="color:#10b981;font-size:1.2rem;margin-top:2px"></i>
                    <div><h6 class="fw-bold mb-0" style="font-size:0.82rem">BPOM</h6><small class="text-muted">Badan Pengawas Obat dan Makanan</small></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 d-flex align-items-start gap-2" style="border-radius:var(--radius-sm);background:rgba(16,185,129,0.04);border:1px solid rgba(16,185,129,0.1)">
                    <i class="bi bi-check-circle-fill" style="color:#10b981;font-size:1.2rem;margin-top:2px"></i>
                    <div><h6 class="fw-bold mb-0" style="font-size:0.82rem">Halal</h6><small class="text-muted">Sertifikasi halal BPJPH</small></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 d-flex align-items-start gap-2" style="border-radius:var(--radius-sm);background:rgba(16,185,129,0.04);border:1px solid rgba(16,185,129,0.1)">
                    <i class="bi bi-check-circle-fill" style="color:#10b981;font-size:1.2rem;margin-top:2px"></i>
                    <div><h6 class="fw-bold mb-0" style="font-size:0.82rem">PDUK</h6><small class="text-muted">Perizinan Usaha Pangan</small></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- POPUP -->
<div class="detail-popup-overlay" id="detailPopupOverlay">
    <div class="detail-popup-modal">
        <button class="detail-popup-close" id="detailPopupClose"><i class="bi bi-x-lg"></i></button>
        <div class="detail-popup-header" id="popupHeader">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-shield-check" style="font-size:1.3rem"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" id="popupTitle" style="font-size:1.05rem"></h5>
                    <small id="popupSubtitle" style="opacity:0.7;font-size:0.78rem"></small>
                </div>
            </div>
        </div>
        <div class="detail-popup-body" id="popupBody"></div>
    </div>
</div>

<script>
(function() {
    const overlay = document.getElementById('detailPopupOverlay');
    const closeBtn = document.getElementById('detailPopupClose');
    const header = document.getElementById('popupHeader');
    const titleEl = document.getElementById('popupTitle');
    const subtitleEl = document.getElementById('popupSubtitle');
    const bodyEl = document.getElementById('popupBody');
    if (!overlay || !bodyEl) return;

    function esc(s) { if (!s) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    function openPopup(html, hdrColor, hdrTitle, hdrSub) {
        header.style.background = hdrColor || 'linear-gradient(135deg,#0c4a6e,#0ea5e9)';
        titleEl.textContent = hdrTitle || 'Detail';
        subtitleEl.textContent = hdrSub || '';
        bodyEl.innerHTML = html;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closePopup() { overlay.classList.remove('active'); document.body.style.overflow = ''; }
    closeBtn.addEventListener('click', closePopup);
    overlay.addEventListener('click', function(e) { if (e.target === overlay) closePopup(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closePopup(); });

    function infoItem(label, value, full) {
        return '<div class="detail-info-item'+(full?' full':'')+'"><div class="info-label">'+label+'</div><div class="info-value">'+value+'</div></div>';
    }
    function certRow(name, hasCert, number) {
        var cls = hasCert ? 'cert-yes' : 'cert-no';
        var icon = hasCert ? '<i class="bi bi-check-circle-fill cert-icon" style="color:#10b981"></i>' : '<i class="bi bi-dash-circle cert-icon" style="color:#94a3b8"></i>';
        var numHtml = '';
        if (hasCert && number && number !== '-' && number !== '') {
            numHtml = '<span class="cert-number">(' + esc(number) + ')</span>';
        }
        return '<div class="detail-cert-row '+cls+'">' + icon + ' <span class="cert-name">' + name + '</span>' + numHtml + '</div>';
    }

    // ===== EMBED DATA =====
    
    // Konversi data ke JSON di sisi server (PHP)
    var checksData = {!! json_encode($checks->mapWithKeys(function($check) {
        return [$check->id => [
            'date' => $check->check_date ? $check->check_date->format('d/m/Y') : '-',
            'foodName' => $check->food_name ?? '',
            'sourceType' => $check->source_type_label ?? $check->food_source_type ?? '-',
            'foodCondition' => $check->food_condition_label ?? $check->food_condition ?? '-',
            'packagingType' => $check->packaging_type ?? '-',
            'packagingNetto' => $check->packaging_netto ?? '-',
            'expiryDate' => $check->expiry_date ? $check->expiry_date->format('d/m/Y') : '-',
            'distributorName' => $check->distributor_name ?? '-',
            'retailerName' => $check->retailer_name ?? '-',
            'retailerAddress' => $check->retailer_address ?? '-',
            'bpomNumber' => $check->bpom_number ?? '',
            'isHalal' => (bool) $check->is_halal,
            'halalNumber' => $check->halal_number ?? '',
            'isPduk' => (bool) $check->is_pduk,
            'pdukNumber' => $check->pduk_number ?? '',
            'result' => $check->inspection_result_label ?? $check->inspection_result ?? '-',
            'resultColor' => $check->inspection_result_color ?? '#64748b',
            'recommendation' => $check->recommendation,
            'recommendationPhoto' => $check->recommendation_photo_url,
            'inspectionType' => $check->inspection_type ?? '-',
            'inspectorName' => $check->inspector_name ?? '-',
            'notes' => $check->notes,
            'creatorName' => $check->creator?->name ?? '-',
        ]];
    })) !!};

    var activitiesData = {!! json_encode($activities->mapWithKeys(function($activity) {
        return [$activity->id => [
            'date' => $activity->activity_date ? $activity->activity_date->format('d/m/Y') : '-',
            'title' => $activity->title ?? '',
            'type' => $activity->activity_type_label ?? '-',
            'typeIcon' => $activity->activity_type_icon ?? 'activity',
            'description' => $activity->description,
            'location' => $activity->location ?? '-',
            'participantsCount' => $activity->participants_count ?? 0,
            'evidencePhoto' => $activity->evidence_photo ? \Storage::url($activity->evidence_photo) : null,
            'evidencePhoto2' => $activity->evidence_photo_2 ? \Storage::url($activity->evidence_photo_2) : null,
            'evidencePhoto3' => $activity->evidence_photo_3 ? \Storage::url($activity->evidence_photo_3) : null,
            'outcome' => $activity->outcome,
            'status' => $activity->status ?? '-',
            'creatorName' => $activity->creator?->name ?? '-',
        ]];
    })) !!};

    // Click check row
    document.querySelectorAll('.safety-check-row').forEach(function(row) {
        row.addEventListener('click', function() {
            var id = this.dataset.checkId;
            var d = checksData[id];
            if (!d) { console.warn('No data for check id:', id); return; }

            var r = d.result.toLowerCase();
            var hdrColor = r.indexOf('layak') >= 0 && r.indexOf('tidak') < 0
                ? 'linear-gradient(135deg,#064e3b,#10b981)'
                : (r.indexOf('tidak') >= 0 ? 'linear-gradient(135deg,#7f1d1d,#ef4444)' : 'linear-gradient(135deg,#78350f,#f59e0b)');

            var html = '<div class="detail-info-grid">';
            html += infoItem('Tanggal Pemeriksaan', esc(d.date));
            html += infoItem('Nama Pangan', '<strong>' + esc(d.foodName) + '</strong>');
            html += infoItem('Asal Pangan', esc(d.sourceType));
            html += infoItem('Kondisi Pangan', esc(d.foodCondition));
            html += infoItem('Jenis Kemasan', esc(d.packagingType));
            html += infoItem('Berat Bersih', esc(d.packagingNetto));
            html += infoItem('Tanggal Kadaluarsa', esc(d.expiryDate));
            html += infoItem('Jenis Pemeriksaan', esc(d.inspectionType));
            html += infoItem('Nama Pemeriksa', esc(d.inspectorName));
            html += infoItem('Dibuat Oleh', esc(d.creatorName));
            html += '</div>';

            html += '<h6 class="fw-bold mt-3 mb-2" style="font-size:0.85rem;color:var(--primary)"><i class="bi bi-person-badge me-1"></i>Pelaku Usaha</h6>';
            html += '<div class="detail-info-grid">';
            html += infoItem('Distributor', esc(d.distributorName));
            html += infoItem('Pengecer', esc(d.retailerName));
            html += infoItem('Alamat Pengecer', esc(d.retailerAddress), true);
            html += '</div>';

            html += '<h6 class="fw-bold mt-3 mb-2" style="font-size:0.85rem;color:var(--primary)"><i class="bi bi-patch-check me-1"></i>Sertifikasi</h6>';
            html += certRow('BPOM', d.bpomNumber !== '' && d.bpomNumber !== '-', d.bpomNumber);
            html += certRow('Halal', d.isHalal, d.halalNumber);
            html += certRow('PDUK', d.isPduk, d.pdukNumber);

            html += '<h6 class="fw-bold mt-3 mb-2" style="font-size:0.85rem;color:var(--primary)"><i class="bi bi-clipboard-check me-1"></i>Hasil Pemeriksaan</h6>';
            html += '<div class="p-3" style="border-radius:12px;background:'+d.resultColor+'10;border:1px solid '+d.resultColor+'20;text-align:center">';
            html += '<div class="fw-bold" style="font-size:1.1rem;color:'+d.resultColor+'">' + esc(d.result) + '</div>';
            html += '</div>';

            if (d.recommendation) {
                html += '<div class="mt-2 p-3" style="border-radius:12px;background:rgba(99,102,241,0.04);border:1px solid rgba(99,102,241,0.08)">';
                html += '<div style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;font-weight:600;margin-bottom:4px">Rekomendasi</div>';
                html += '<div style="font-size:0.85rem">' + esc(d.recommendation) + '</div></div>';
            }
            if (d.recommendationPhoto) {
                html += '<div class="mt-2"><img src="'+esc(d.recommendationPhoto)+'" alt="Rekomendasi" style="max-width:100%;border-radius:12px;border:1px solid #e2e8f0"></div>';
            }
            if (d.notes) {
                html += '<div class="mt-2 p-3" style="border-radius:12px;background:rgba(148,163,184,0.04);border:1px solid rgba(148,163,184,0.08)">';
                html += '<div style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;font-weight:600;margin-bottom:4px">Catatan</div>';
                html += '<div style="font-size:0.85rem">' + esc(d.notes) + '</div></div>';
            }

            openPopup(html, hdrColor, d.foodName, 'Pemeriksaan — ' + d.date);
        });
    });

    // Click activity card
    document.querySelectorAll('.activity-card').forEach(function(card) {
        card.addEventListener('click', function() {
            var id = this.dataset.activityId;
            var d = activitiesData[id];
            if (!d) { console.warn('No data for activity id:', id); return; }

            var html = '<div class="detail-info-grid">';
            html += infoItem('Tanggal', esc(d.date));
            html += infoItem('Jenis Kegiatan', '<i class="bi bi-'+esc(d.typeIcon)+' me-1"></i>' + esc(d.type));
            html += infoItem('Lokasi', esc(d.location));
            html += infoItem('Peserta', d.participantsCount + ' orang');
            html += infoItem('Status', esc(d.status));
            html += infoItem('Dibuat Oleh', esc(d.creatorName));
            html += '</div>';

            if (d.description) {
                html += '<h6 class="fw-bold mt-3 mb-2" style="font-size:0.85rem;color:var(--primary)"><i class="bi bi-text-paragraph me-1"></i>Deskripsi</h6>';
                html += '<div class="p-3" style="border-radius:12px;background:rgba(99,102,241,0.04);border:1px solid rgba(99,102,241,0.08)"><div style="font-size:0.85rem;line-height:1.6">'+esc(d.description)+'</div></div>';
            }
            if (d.outcome) {
                html += '<h6 class="fw-bold mt-3 mb-2" style="font-size:0.85rem;color:var(--primary)"><i class="bi bi-flag me-1"></i>Hasil / Outcome</h6>';
                html += '<div class="p-3" style="border-radius:12px;background:rgba(16,185,129,0.04);border:1px solid rgba(16,185,129,0.08)"><div style="font-size:0.85rem;line-height:1.6">'+esc(d.outcome)+'</div></div>';
            }
            var photos = [d.evidencePhoto, d.evidencePhoto2, d.evidencePhoto3].filter(function(p){return p;});
            if (photos.length > 0) {
                html += '<h6 class="fw-bold mt-3 mb-2" style="font-size:0.85rem;color:var(--primary)"><i class="bi bi-images me-1"></i>Dokumentasi</h6>';
                html += '<div class="detail-photo-grid">';
                photos.forEach(function(p){ html += '<img src="'+esc(p)+'" alt="Dokumentasi" loading="lazy">'; });
                html += '</div>';
            }
            openPopup(html, 'linear-gradient(135deg,#064e3b,#10b981)', d.title, d.type + ' — ' + d.date);
        });
    });
})();
</script>
@endsection
