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
    .detail-popup-modal.wide { max-width: 900px; }
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
                <small class="text-white-50">Hasil Uji Rapid Test & Uji Laboratorium pangan di Kota Tual</small>
            </div>
            <div class="d-flex gap-2">
                <span class="badge-modern" style="background:rgba(168,85,247,0.15);color:#c084fc"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>{{ $stats['total_rapid'] }} Uji Rapid Test</span>
                <span class="badge-modern" style="background:rgba(239,68,68,0.15);color:#f87171"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>{{ $stats['total_lab'] }} Uji Lab</span>
            </div>
        </div>
    </div>
</section>

<!-- Stats Cards -->
<section class="py-4" style="background:var(--bg-card)">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #a855f7">
                    <div class="stat-icon" style="background:rgba(168,85,247,0.1);color:#a855f7"><i class="bi bi-lightning-charge-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#a855f7">{{ $stats['total_rapid'] }}</div>
                        <small class="text-muted">Total Uji Rapid Test</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #10b981">
                    <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#10b981">{{ $stats['rapid_negatif'] }}</div>
                        <small class="text-muted">Rapid Test Negatif (Aman)</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #ef4444">
                    <div class="stat-icon" style="background:rgba(239,68,68,0.1);color:#ef4444"><i class="bi bi-x-circle-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#ef4444">{{ $stats['rapid_positif'] }}</div>
                        <small class="text-muted">Rapid Test Positif</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #0ea5e9">
                    <div class="stat-icon" style="background:rgba(14,165,233,0.1);color:#0ea5e9"><i class="bi bi-eyedropper"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#0ea5e9">{{ $stats['total_lab'] }}</div>
                        <small class="text-muted">Total Uji Laboratorium</small>
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
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#rapidPanel" type="button" role="tab" style="font-size:0.85rem;font-weight:600;border-radius:8px 8px 0 0">
                    <i class="bi bi-lightning-charge me-1"></i>Uji Rapid Test
                    <span class="badge ms-1" style="background:rgba(168,85,247,0.1);color:#a855f7;font-size:0.65rem">{{ $stats['total_rapid'] }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#labPanel" type="button" role="tab" style="font-size:0.85rem;font-weight:600;border-radius:8px 8px 0 0">
                    <i class="bi bi-eyedropper me-1"></i>Uji Laboratorium
                    <span class="badge ms-1" style="background:rgba(239,68,68,0.1);color:#ef4444;font-size:0.65rem">{{ $stats['total_lab'] }}</span>
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Uji Rapid Test Tab -->
            <div class="tab-pane fade show active" id="rapidPanel" role="tabpanel">
                <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0" style="font-size:0.82rem">
                                <thead>
                                    <tr>
                                        <th style="width:40px">#</th>
                                        <th>Tanggal</th>
                                        <th>Komoditas</th>
                                        <th>Jenis Komoditas</th>
                                        <th>Lokasi Sampel</th>
                                        <th>Jenis Rapid Test</th>
                                        <th>Hasil Uji</th>
                                        <th style="width:30px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rapidTests as $i => $rapid)
                                    <tr class="check-row rapid-test-row" data-rapid-id="{{ $rapid->id }}" style="border-left:3px solid {{ $rapid->result_color }}">
                                        <td class="text-muted">{{ $rapidTests->firstItem() + $i }}</td>
                                        <td>{{ $rapid->test_date?->format('d/m/Y') }}</td>
                                        <td class="fw-semibold">{{ $rapid->commodity_name }}</td>
                                        <td>{{ $rapid->commodity_type ?? '-' }}</td>
                                        <td>{{ $rapid->sample_location }}</td>
                                        <td><span class="badge" style="background:rgba(168,85,247,0.08);color:#a855f7;font-size:0.65rem"><i class="bi bi-lightning-charge me-1"></i>{{ $rapid->type_label }}</span>@if($rapid->parameter)<div class="small text-muted mt-1"><i class="bi bi-sliders me-1"></i>{{ $rapid->parameter_label }}</div>@endif</td>
                                        <td>
                                            <span class="badge" style="background:{{ $rapid->result_color }}22;color:{{ $rapid->result_color }};font-size:0.7rem">
                                                <i class="bi bi-{{ $rapid->result === 'positif' ? 'x-circle-fill' : 'check-circle-fill' }} me-1"></i>{{ $rapid->result_label }}
                                            </span>
                                        </td>
                                        <td><i class="bi bi-eye" style="color:var(--primary);opacity:0.4;font-size:0.8rem"></i></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div style="font-size:2rem;opacity:0.3;margin-bottom:0.5rem"><i class="bi bi-lightning-charge"></i></div>
                                            <p class="text-muted mb-0">Belum ada data uji rapid test.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($rapidTests->hasPages())
                    <div class="card-footer p-3" style="background:var(--bg-card)">{{ $rapidTests->links() }}</div>
                    @endif
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted"><i class="bi bi-cursor me-1"></i>Klik baris untuk melihat detail hasil uji rapid test</small>
                </div>
            </div>

            <!-- Uji Laboratorium Tab -->
            <div class="tab-pane fade" id="labPanel" role="tabpanel">
                <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0" style="font-size:0.82rem">
                                <thead>
                                    <tr>
                                        <th style="width:40px">#</th>
                                        <th>Tanggal</th>
                                        <th>Komoditas</th>
                                        <th>Jenis PSAT</th>
                                        <th>Lab Uji</th>
                                        <th>Lokasi Sampel</th>
                                        <th>Status</th>
                                        <th>CoA</th>
                                        <th style="width:30px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($labTests as $i => $lab)
                                    <tr class="check-row lab-test-row" data-lab-id="{{ $lab->id }}" style="border-left:3px solid {{ $lab->overall_status_color }}">
                                        <td class="text-muted">{{ $labTests->firstItem() + $i }}</td>
                                        <td>{{ $lab->test_date?->format('d/m/Y') }}</td>
                                        <td class="fw-semibold">{{ $lab->commodity_name }}</td>
                                        <td><span class="badge" style="background:rgba(14,165,233,0.08);color:#0ea5e9;font-size:0.65rem">{{ $lab->psat_type_label }}</span></td>
                                        <td>{{ $lab->lab_name }}</td>
                                        <td>{{ $lab->sample_location }}</td>
                                        <td>
                                            <span class="badge" style="background:{{ $lab->overall_status_color }}22;color:{{ $lab->overall_status_color }};font-size:0.7rem">
                                                <i class="bi bi-{{ $lab->overall_status === 'TMS' ? 'x-circle-fill' : 'check-circle-fill' }} me-1"></i>{{ $lab->overall_status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($lab->certificate_file)
                                                <a href="{{ $lab->certificate_file_url }}" target="_blank" class="badge" style="background:rgba(239,68,68,0.1);color:#ef4444;font-size:0.65rem;text-decoration:none" onclick="event.stopPropagation()"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><i class="bi bi-eye" style="color:var(--primary);opacity:0.4;font-size:0.8rem"></i></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div style="font-size:2rem;opacity:0.3;margin-bottom:0.5rem"><i class="bi bi-eyedropper"></i></div>
                                            <p class="text-muted mb-0">Belum ada data hasil uji laboratorium.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($labTests->hasPages())
                    <div class="card-footer p-3" style="background:var(--bg-card)">{{ $labTests->links() }}</div>
                    @endif
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted"><i class="bi bi-cursor me-1"></i>Klik baris untuk melihat hasil uji laboratorium lengkap (gaya sertifikat lab)</small>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Legend -->
<section class="py-4" style="background:var(--bg-card)">
    <div class="container">
        <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-info-circle me-2" style="color:var(--primary)"></i>Penjelasan Istilah Hasil Uji</h6>
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="p-3 h-100" style="border-radius:var(--radius-sm);background:rgba(168,85,247,0.04);border:1px solid rgba(168,85,247,0.1)">
                    <h6 class="fw-bold mb-1" style="font-size:0.8rem"><i class="bi bi-lightning-charge-fill me-1" style="color:#a855f7"></i>Rapid Test</h6>
                    <small class="text-muted">Uji cepat pemeriksaan bahan berbahaya (boraks, formalin, pewarna, dll) langsung di lokasi sampel</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3 h-100" style="border-radius:var(--radius-sm);background:rgba(16,185,129,0.04);border:1px solid rgba(16,185,129,0.1)">
                    <h6 class="fw-bold mb-1" style="font-size:0.8rem"><i class="bi bi-check-circle-fill me-1" style="color:#10b981"></i>Negatif / MS</h6>
                    <small class="text-muted">Negatif = tidak terdeteksi bahan berbahaya. MS = Memenuhi Syarat sesuai baku mutu</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3 h-100" style="border-radius:var(--radius-sm);background:rgba(239,68,68,0.04);border:1px solid rgba(239,68,68,0.1)">
                    <h6 class="fw-bold mb-1" style="font-size:0.8rem"><i class="bi bi-x-circle-fill me-1" style="color:#ef4444"></i>Positif / TMS</h6>
                    <small class="text-muted">Positif = terdeteksi bahan berbahaya. TMS = Tidak Memenuhi Syarat sesuai baku mutu</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3 h-100" style="border-radius:var(--radius-sm);background:rgba(14,165,233,0.04);border:1px solid rgba(14,165,233,0.1)">
                    <h6 class="fw-bold mb-1" style="font-size:0.8rem"><i class="bi bi-file-earmark-pdf me-1" style="color:#0ea5e9"></i>LOD / LOQ / CoA</h6>
                    <small class="text-muted">LOD/LOQ = batas deteksi & batas kuantifikasi alat uji. CoA = Certificate of Analysis dari laboratorium</small>
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

    function openPopup(html, hdrColor, hdrTitle, hdrSub, wide) {
        header.style.background = hdrColor || 'linear-gradient(135deg,#0c4a6e,#0ea5e9)';
        titleEl.textContent = hdrTitle || 'Detail';
        subtitleEl.textContent = hdrSub || '';
        bodyEl.innerHTML = html;
        var modal = overlay.querySelector('.detail-popup-modal');
        if (modal) modal.classList.toggle('wide', !!wide);
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

    // ===== EMBED DATA =====

    var rapidTestsData = {!! json_encode($rapidTests->mapWithKeys(function($r) {
        return [$r->id => [
            'date' => $r->test_date ? $r->test_date->format('d/m/Y') : '-',
            'location' => $r->sample_location ?? '-',
            'commodityName' => $r->commodity_name ?? '',
            'commodityType' => $r->commodity_type ?? '-',
            'type' => $r->type_label ?? '-',
            'parameter' => $r->parameter_label,
            'result' => $r->result_label ?? '-',
            'resultColor' => $r->result_color ?? '#64748b',
            'notes' => $r->notes,
        ]];
    })) !!};

    var labTestsData = {!! json_encode($labTests->mapWithKeys(function($lab) {
        return [$lab->id => [
            'date' => $lab->test_date ? $lab->test_date->format('d/m/Y') : '-',
            'location' => $lab->sample_location ?? '-',
            'labName' => $lab->lab_name ?? '-',
            'commodityName' => $lab->commodity_name ?? '',
            'psatType' => $lab->psat_type_label ?? '-',
            'method' => $lab->method ?? '-',
            'notes' => $lab->notes,
            'certificate' => $lab->certificate_file_url,
            'overallStatus' => $lab->overall_status_label ?? '-',
            'overallColor' => $lab->overall_status_color ?? '#64748b',
            'params' => $lab->parameters->map(function($p) {
                return [
                    'category' => $p->test_category,
                    'categoryLabel' => $p->category_label,
                    'categoryIcon' => $p->category_icon,
                    'categoryColor' => $p->category_color,
                    'parameter' => $p->parameter,
                    'result' => $p->result_label,
                    'resultColor' => $p->result_color,
                    'value' => $p->value ?? '',
                    'unit' => $p->unit ?? '',
                    'standard' => $p->standard ?? '',
                    'lod' => $p->lod ?? '',
                    'loq' => $p->loq ?? '',
                    'status' => $p->status_label ?? '-',
                    'statusColor' => $p->status_color ?? '#64748b',
                    'method' => $p->method ?? '-',
                ];
            }),
        ]];
    })) !!};

    // Click rapid test row
    document.querySelectorAll('.rapid-test-row').forEach(function(row) {
        row.addEventListener('click', function() {
            var id = this.dataset.rapidId;
            var d = rapidTestsData[id];
            if (!d) return;

            var isPos = d.result.toLowerCase().indexOf('positif') >= 0;
            var hdrColor = isPos ? 'linear-gradient(135deg,#7f1d1d,#ef4444)' : 'linear-gradient(135deg,#581c87,#a855f7)';

            var html = '<div class="detail-info-grid">';
            html += infoItem('Tanggal Pengujian', esc(d.date));
            html += infoItem('Komoditas', '<strong>' + esc(d.commodityName) + '</strong>');
            html += infoItem('Jenis Komoditas', esc(d.commodityType));
            html += infoItem('Lokasi Sampel', esc(d.location));
            html += infoItem('Jenis Rapid Test', '<i class="bi bi-lightning-charge me-1"></i>' + esc(d.type));
            if (d.parameter) {
                html += infoItem('Parameter Uji', '<i class="bi bi-sliders me-1"></i>' + esc(d.parameter));
            }
            html += infoItem('Hasil Uji', '<strong style="color:' + d.resultColor + '">' + esc(d.result) + '</strong>');
            html += '</div>';

            if (d.notes) {
                html += '<div class="mt-2 p-3" style="border-radius:12px;background:rgba(148,163,184,0.04);border:1px solid rgba(148,163,184,0.08)">';
                html += '<div style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;font-weight:600;margin-bottom:4px">Catatan</div>';
                html += '<div style="font-size:0.85rem">' + esc(d.notes) + '</div></div>';
            }

            openPopup(html, hdrColor, d.commodityName, 'Uji Rapid Test — ' + d.type + ' — ' + d.date);
        });
    });

    // Click lab test row → popup gaya sertifikat lab (CoA)
    document.querySelectorAll('.lab-test-row').forEach(function(row) {
        row.addEventListener('click', function() {
            var id = this.dataset.labId;
            var d = labTestsData[id];
            if (!d) return;

            var isTms = d.overallStatus.indexOf('TMS') >= 0 || d.overallStatus.indexOf('Tidak') >= 0;
            var hdrColor = isTms ? 'linear-gradient(135deg,#7f1d1d,#ef4444)' : 'linear-gradient(135deg,#0c4a6e,#0ea5e9)';

            var html = '<div class="p-3 mb-2" style="border-radius:12px;background:' + d.overallColor + '10;border:1px solid ' + d.overallColor + '22;display:flex;align-items:center;justify-content:space-between;gap:0.75rem;flex-wrap:wrap">';
            html += '<div><div style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;font-weight:600">Kesimpulan Hasil Uji</div>';
            html += '<div class="fw-bold" style="font-size:1.05rem;color:' + d.overallColor + '">' + esc(d.overallStatus) + '</div></div>';
            html += '<small class="text-muted">' + d.params.length + ' parameter diuji</small>';
            html += '</div>';

            html += '<div class="detail-info-grid">';
            html += infoItem('Tanggal Pengujian', esc(d.date));
            html += infoItem('Nama Komoditas', '<strong>' + esc(d.commodityName) + '</strong>');
            html += infoItem('Jenis PSAT', esc(d.psatType));
            html += infoItem('Lab Uji', esc(d.labName));
            html += infoItem('Lokasi Sampel', esc(d.location));
            html += infoItem('Metode Uji (Umum)', esc(d.method));
            html += '</div>';

            // Tabel parameter per kelompok uji (gaya CoA)
            var groups = {};
            var order = [];
            d.params.forEach(function(p) {
                if (!groups[p.category]) { groups[p.category] = []; order.push(p); }
                groups[p.category].push(p);
            });

            html += '<div class="mt-3">';
            order.forEach(function(first) {
                var list = groups[first.category];
                html += '<div class="mb-3">';
                html += '<div class="fw-bold mb-1" style="font-size:0.8rem;color:' + first.categoryColor + '"><i class="bi ' + first.categoryIcon + ' me-1"></i>' + esc(first.categoryLabel) + '</div>';
                html += '<div style="overflow-x:auto;border-radius:12px;border:1px solid rgba(148,163,184,0.15)">';
                html += '<table style="width:100%;border-collapse:collapse;font-size:0.7rem;min-width:640px">';
                html += '<thead><tr style="background:' + first.categoryColor + '0d">';
                ['Parameter','Hasil','Nilai','Satuan','Standar','LOD','LOQ','Status','Metode'].forEach(function(h){ html += '<th style="padding:0.45rem 0.6rem;text-align:left;font-size:0.62rem;text-transform:uppercase;letter-spacing:0.3px;color:#64748b">' + h + '</th>'; });
                html += '</tr></thead><tbody>';
                list.forEach(function(p, idx) {
                    var bg = idx % 2 ? 'background:rgba(148,163,184,0.03)' : '';
                    html += '<tr style="' + bg + '">';
                    html += '<td style="padding:0.45rem 0.6rem;font-weight:600;white-space:nowrap">' + esc(p.parameter) + '</td>';
                    html += '<td style="padding:0.45rem 0.6rem;color:' + p.resultColor + ';font-weight:600;white-space:nowrap">' + esc(p.result) + '</td>';
                    html += '<td style="padding:0.45rem 0.6rem">' + esc(p.value || '-') + '</td>';
                    html += '<td style="padding:0.45rem 0.6rem">' + esc(p.unit || '-') + '</td>';
                    html += '<td style="padding:0.45rem 0.6rem">' + esc(p.standard || '-') + '</td>';
                    html += '<td style="padding:0.45rem 0.6rem">' + esc(p.lod || '-') + '</td>';
                    html += '<td style="padding:0.45rem 0.6rem">' + esc(p.loq || '-') + '</td>';
                    html += '<td style="padding:0.45rem 0.6rem;white-space:nowrap"><span style="color:' + p.statusColor + ';font-weight:600">' + esc(p.status) + '</span></td>';
                    html += '<td style="padding:0.45rem 0.6rem">' + esc(p.method) + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table></div></div>';
            });
            html += '</div>';

            if (d.certificate) {
                html += '<a href="' + esc(d.certificate) + '" target="_blank" class="btn btn-sm mt-1" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:10px;font-weight:600;font-size:0.75rem"><i class="bi bi-file-earmark-pdf me-1"></i>Unduh Sertifikat CoA (PDF)</a>';
            }
            if (d.notes) {
                html += '<div class="mt-2 p-3" style="border-radius:12px;background:rgba(148,163,184,0.04);border:1px solid rgba(148,163,184,0.08)">';
                html += '<div style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;font-weight:600;margin-bottom:4px">Catatan</div>';
                html += '<div style="font-size:0.85rem">' + esc(d.notes) + '</div></div>';
            }

            openPopup(html, hdrColor, d.commodityName, 'Hasil Uji Laboratorium — ' + d.labName + ' — ' + d.date, true);
        });
    });
})();
</script>
@endsection
