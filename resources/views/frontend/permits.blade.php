@extends('layouts.app')
@section('title', 'Laporan Perizinan Pangan - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<!-- Header -->
<section class="py-4" style="background:linear-gradient(135deg,#0f172a,#3b0764)">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <nav aria-label="breadcrumb" style="font-size:0.72rem">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;margin:0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.5);text-decoration:none">Beranda</a></li>
                        <li class="breadcrumb-item active" style="color:#8b5cf6">Laporan Perizinan Pangan</li>
                    </ol>
                </nav>
                <h4 class="fw-bold text-white mb-1"><i class="bi bi-file-earmark-text me-2"></i>Laporan Perizinan Pangan</h4>
                <small class="text-white-50">Daftar data perizinan pangan &amp; NIB pelaku usaha pangan di Kota Tual — informasi laporan, penerbitan izin tetap melalui sistem satu pintu</small>
            </div>
            <div class="d-flex gap-2">
                <span class="badge-modern" style="background:rgba(139,92,246,0.15);color:#8b5cf6"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>{{ $stats['total'] }} Data Perizinan</span>
            </div>
        </div>
    </div>
</section>

<!-- Stats Cards -->
<section class="py-4" style="background:var(--bg-card)">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #6366f1">
                    <div class="stat-icon" style="background:rgba(99,102,241,0.1);color:#6366f1"><i class="bi bi-file-earmark-text"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:var(--primary)">{{ $stats['total'] }}</div>
                        <small class="text-muted">Total Perizinan</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #10b981">
                    <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-patch-check-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#10b981">{{ $stats['approved'] }}</div>
                        <small class="text-muted">Disetujui</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #f59e0b">
                    <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#f59e0b">{{ $stats['pending'] }}</div>
                        <small class="text-muted">Menunggu</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #64748b">
                    <div class="stat-icon" style="background:rgba(100,116,139,0.1);color:#64748b"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#64748b">{{ $stats['expired'] }}</div>
                        <small class="text-muted">Kadaluarsa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter + Grafik per Kecamatan -->
<section class="py-4" style="background:var(--bg)">
    <div class="container">
        <div class="row g-4">
            <!-- Filter Pencarian -->
            <div class="col-lg-5">
                <div class="p-3 p-md-4" style="border-radius:var(--radius);background:var(--bg-card);border:1px solid #e2e8f0;height:100%">
                    <h6 class="fw-bold mb-3" style="font-size:0.88rem"><i class="bi bi-funnel me-2" style="color:var(--primary)"></i>Cari Data Perizinan / NIB</h6>
                    <form method="GET" action="{{ route('frontend.permits') }}">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Pencarian</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:rgba(99,102,241,0.06);border-radius:10px 0 0 10px"><i class="bi bi-search"></i></span>
                                <input type="text" name="q" class="form-control" style="border-radius:0 10px 10px 0" placeholder="Nomor Izin Berusaha (NIB) / nama usaha / pelaku usaha..." value="{{ request('q') }}">
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-sm-6 mb-2">
                                <label class="form-label small fw-semibold text-muted">Kecamatan</label>
                                <select name="kecamatan" id="filterKecamatan" class="form-select">
                                    <option value="">Semua Kecamatan</option>
                                    @foreach(array_keys($kecamatanDesa) as $kec)
                                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>Kec. {{ $kec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <label class="form-label small fw-semibold text-muted">Desa / Kelurahan</label>
                                <select name="desa" id="filterDesa" class="form-select">
                                    <option value="">Semua Desa</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label small fw-semibold text-muted">Jenis Perizinan</label>
                                <select name="jenis" class="form-select">
                                    <option value="">Semua Jenis</option>
                                    <option value="pduk" {{ request('jenis') == 'pduk' ? 'selected' : '' }}>PDUK</option>
                                    <option value="bpom" {{ request('jenis') == 'bpom' ? 'selected' : '' }}>BPOM</option>
                                    <option value="halal" {{ request('jenis') == 'halal' ? 'selected' : '' }}>Halal</option>
                                    <option value="sp_irt" {{ request('jenis') == 'sp_irt' ? 'selected' : '' }}>SP-IRT</option>
                                    <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-modern flex-fill"><i class="bi bi-search me-1"></i>Cari</button>
                            <a href="{{ route('frontend.permits') }}" class="btn btn-outline-secondary" style="border-radius:10px"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Grafik per Kecamatan -->
            <div class="col-lg-7">
                <div class="chart-container" style="height:100%;min-height:340px">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <h6 class="fw-bold mb-0" style="font-size:0.88rem"><i class="bi bi-bar-chart me-2" style="color:var(--primary)"></i>Sebaran Pelaku Usaha Pangan per Kecamatan</h6>
                        <span class="badge" style="background:rgba(99,102,241,0.08);color:var(--primary);font-size:0.65rem">{{ array_sum($chartValues) }} Pelaku Usaha Terdata</span>
                    </div>
                    <div style="height:270px"><canvas id="chartKecamatan"></canvas></div>
                    <small class="text-muted d-block mt-2" style="font-size:0.72rem"><i class="bi bi-info-circle me-1"></i>Grafik menampilkan jumlah pelaku usaha pangan berdasarkan wilayah kecamatan di Kota Tual.</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tabel Laporan Perizinan -->
<section class="py-4" style="background:var(--bg-card)">
    <div class="container">
        <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden">
            <div class="card-header p-3" style="background:linear-gradient(135deg,rgba(139,92,246,0.06),rgba(99,102,241,0.04));border-bottom:1px solid rgba(139,92,246,0.08)">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="fw-bold mb-0" style="font-size:0.88rem"><i class="bi bi-list-ul me-2" style="color:#8b5cf6"></i>Daftar Perizinan Pangan &amp; NIB Pelaku Usaha</h6>
                    @if(request()->filled(['q','kecamatan','desa','jenis']))
                        <span class="badge" style="background:rgba(139,92,246,0.1);color:#8b5cf6;font-size:0.65rem"><i class="bi bi-funnel me-1"></i>Hasil Filter</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0" style="font-size:0.82rem">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Nama Usaha</th>
                                <th>Nama Pelaku Usaha</th>
                                <th>Nomor Izin Berusaha (NIB)</th>
                                <th>Jenis Perizinan</th>
                                <th>Kecamatan</th>
                                <th>Desa / Kelurahan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permits as $i => $p)
                            @php
                                $st = $p->permit_status;
                                if ($st === 'approved' && $p->expiry_date && $p->expiry_date->lt(\Illuminate\Support\Carbon::today())) $st = 'expired';
                                $uClr = match($st) { 'approved' => '#10b981', 'pending' => '#f59e0b', 'rejected' => '#ef4444', 'expired' => '#64748b', default => '#64748b' };
                                $uIcon = match($st) { 'approved' => 'patch-check-fill', 'pending' => 'hourglass-split', 'rejected' => 'x-circle-fill', 'expired' => 'clock-history', default => 'dash-circle' };
                                $uLabel = match($st) { 'approved' => 'Disetujui', 'pending' => 'Menunggu', 'rejected' => 'Ditolak', 'expired' => 'Kadaluarsa', default => ucfirst($st ?? '-') };
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $permits->firstItem() + $i }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $p->business_name }}</div>
                                    @if($p->permit_number)
                                        <small class="text-muted" style="font-size:0.68rem"><i class="bi bi-tag me-1"></i>{{ $p->permit_number }}</small>
                                    @endif
                                </td>
                                <td>{{ $p->applicant_name ?? '-' }}</td>
                                <td>
                                    @if($p->nib)
                                        <span class="badge" style="background:rgba(99,102,241,0.08);color:var(--primary);font-size:0.7rem;font-family:monospace">{{ $p->nib }}</span>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td><span class="badge" style="background:rgba(139,92,246,0.1);color:#8b5cf6;font-size:0.65rem">{{ $p->permit_type_label }}</span></td>
                                <td><small>{{ $p->kecamatan ? 'Kec. '.$p->kecamatan : '-' }}</small></td>
                                <td><small>{{ $p->desa ?? '-' }}</small></td>
                                <td><span class="badge" style="background:{{ $uClr }}22;color:{{ $uClr }};font-size:0.7rem"><i class="bi bi-{{ $uIcon }} me-1"></i>{{ $uLabel }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div style="font-size:2rem;opacity:0.3;margin-bottom:0.5rem"><i class="bi bi-file-earmark-text"></i></div>
                                    @if(request()->filled(['q','kecamatan','desa','jenis']))
                                        <p class="text-muted mb-0">Data perizinan tidak ditemukan. Coba ubah kata kunci atau filter wilayah.</p>
                                    @else
                                        <p class="text-muted mb-0">Belum ada data perizinan pangan yang dilaporkan.</p>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($permits->hasPages())
            <div class="card-footer p-3" style="background:var(--bg-card)">
                {{ $permits->links() }}
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="py-4" style="background:var(--bg)">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4" style="border-radius:var(--radius);background:var(--bg-card);border:1px solid #e2e8f0">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="width:36px;height:36px;border-radius:10px;background:rgba(139,92,246,0.1);color:#8b5cf6;display:flex;align-items:center;justify-content:center"><i class="bi bi-file-earmark-check"></i></span>
                        <h6 class="fw-bold mb-0" style="font-size:0.85rem">Izin Usaha Pangan</h6>
                    </div>
                    <p class="text-muted small mb-0">Setiap pelaku usaha pangan wajib memiliki izin usaha pangan yang diterbitkan oleh Dinas Ketahanan Pangan sesuai peraturan yang berlaku.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4" style="border-radius:var(--radius);background:var(--bg-card);border:1px solid #e2e8f0">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="width:36px;height:36px;border-radius:10px;background:rgba(139,92,246,0.1);color:#8b5cf6;display:flex;align-items:center;justify-content:center"><i class="bi bi-card-checklist"></i></span>
                        <h6 class="fw-bold mb-0" style="font-size:0.85rem">Legalitas UMKM</h6>
                    </div>
                    <p class="text-muted small mb-0">Data dokumen legalitas UMKM pangan yang terdaftar di Kota Tual meliputi NIB, sertifikasi halal, dan izin edar produk.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4" style="border-radius:var(--radius);background:var(--bg-card);border:1px solid #e2e8f0">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="width:36px;height:36px;border-radius:10px;background:rgba(139,92,246,0.1);color:#8b5cf6;display:flex;align-items:center;justify-content:center"><i class="bi bi-search"></i></span>
                        <h6 class="fw-bold mb-0" style="font-size:0.85rem">Tracking Perizinan</h6>
                    </div>
                    <p class="text-muted small mb-0">Pantau status perizinan Anda secara real-time. Daftar UMKM baru melalui halaman pendaftaran yang tersedia.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================= -->
<!-- SECTION: FAQ PERIZINAN (NIB & PSAT-PDUK) — data dari DB, dikelola via Admin -->
<!-- ============================= -->
<style>
    /* CSS variable FAQ — dark mode otomatis ikut */
    :root {
        --faq-section-bg:#f1f5f9; --faq-card-bg:#ffffff; --faq-text:#1e293b; --faq-text-muted:#475569;
        --faq-border:#e2e8f0; --faq-active-bg:rgba(139,92,246,0.06); --faq-active-text:#6d28d9;
    }
    html.dark-mode {
        --faq-section-bg:#0f172a; --faq-card-bg:#1e293b; --faq-text:#e2e8f0; --faq-text-muted:#cbd5e1;
        --faq-border:rgba(139,92,246,0.2); --faq-active-bg:rgba(139,92,246,0.12); --faq-active-text:#c4b5fd;
    }
    /* Tab sederhana */
    .faq-tabs .nav-link {
        color:var(--faq-text-muted); background:transparent; border:1px solid var(--faq-border);
        border-radius:30px; padding:0.5rem 1.25rem; font-size:0.85rem; font-weight:600; margin:0 0.25rem 0.5rem;
    }
    .faq-tabs .nav-link:hover { color:var(--faq-active-text); }
    .faq-tabs .nav-link.active {
        background:linear-gradient(135deg,#8b5cf6,#6366f1); color:#fff; border-color:transparent;
        box-shadow:0 8px 20px rgba(139,92,246,0.25);
    }
    /* State aktif & focus tombol akordion (Bootstrap pakai class, perlu !important) */
    .faq-acc .accordion-button:not(.collapsed) { background:var(--faq-active-bg)!important; color:var(--faq-active-text)!important; box-shadow:none!important; }
    .faq-acc .accordion-button:focus { box-shadow:none!important; border-color:var(--faq-border)!important; }
    /* FIX KRITIS: Tailwind .collapse{visibility:collapse} konflik dgn Bootstrap accordion.
       Saat akordion terbuka (class "collapse show") Tailwind memaksa visibility:collapse
       sehingga teks hilang. Override ke visible. */
    #faq-perizinan .faq-acc .accordion-collapse { visibility: visible !important; }
    /* Nomor pertanyaan */
    .faq-q-num {
        display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px; border-radius:50%;
        background:linear-gradient(135deg,#8b5cf6,#6366f1); color:#fff; font-size:0.72rem; font-weight:700;
        margin-right:0.75rem; flex-shrink:0; margin-top:1px;
    }
</style>

<section class="py-5" id="faq-perizinan" style="background:var(--faq-section-bg)">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge mb-2 px-3 py-2" style="background:rgba(139,92,246,0.1);color:#8b5cf6;border-radius:20px;font-size:0.75rem;font-weight:600"><i class="bi bi-patch-question me-1"></i>FAQ Perizinan</span>
            <h3 class="fw-bold mb-2" style="font-size:1.5rem;color:var(--faq-text)">Pertanyaan yang Sering Diajukan</h3>
            <p style="color:var(--faq-text-muted);max-width:640px;margin:0 auto;font-size:0.9rem">Informasi seputar perizinan <strong>NIB</strong> dan <strong>PSAT-PDUK</strong> untuk pelaku usaha pangan di Kota Tual.</p>
        </div>

        @php
            $faqNib  = $faqs->where('category', 'nib');
            $faqPsat = $faqs->where('category', 'psat_pduk');
        @endphp

        <!-- Tab Navigasi -->
        <ul class="nav nav-pills faq-tabs justify-content-center mb-4" id="faqTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="faq-nib-tab" data-bs-toggle="pill" data-bs-target="#faq-nib" type="button" role="tab">
                    <i class="bi bi-card-checklist me-1"></i>FAQ NIB
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="faq-psat-tab" data-bs-toggle="pill" data-bs-target="#faq-psat" type="button" role="tab">
                    <i class="bi bi-leaf me-1"></i>FAQ PSAT-PDUK
                </button>
            </li>
        </ul>

        <div class="tab-content" style="max-width:820px;margin:0 auto">
            <!-- TAB: FAQ NIB -->
            <div class="tab-pane fade show active" id="faq-nib" role="tabpanel" aria-labelledby="faq-nib-tab">
                <div class="accordion faq-acc" id="faqNibAccordion">
                    @forelse($faqNib as $i => $f)
                    <div class="accordion-item" style="background:var(--faq-card-bg);border:1px solid var(--faq-border);border-radius:14px;margin-bottom:0.75rem;overflow:hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqn-{{ $f->id }}" style="background:var(--faq-card-bg);color:var(--faq-text);font-weight:600;font-size:0.9rem;padding:1rem 1.25rem">
                                <span class="faq-q-num">{{ $i + 1 }}</span>{{ $f->question }}
                            </button>
                        </h2>
                        <div id="faqn-{{ $f->id }}" class="accordion-collapse collapse" data-bs-parent="#faqNibAccordion">
                            <div class="accordion-body" style="background:var(--faq-card-bg);color:var(--faq-text);padding:0 1.25rem 1.1rem">
                                <p style="color:var(--faq-text);margin:0;font-size:0.85rem;line-height:1.65">{{ $f->answer }}</p>
                                @if($f->items)
                                <ul style="color:var(--faq-text);list-style:disc;padding-left:1.4rem;margin:0.4rem 0 0;font-size:0.85rem;line-height:1.65">
                                    @foreach($f->items as $it)
                                    <li style="color:var(--faq-text);margin-bottom:0.35rem">{{ $it }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4" style="color:var(--faq-text-muted)">
                        <i class="bi bi-inbox" style="font-size:1.8rem;opacity:0.4"></i>
                        <p class="mt-2 mb-0 small">Belum ada FAQ NIB.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- TAB: FAQ PSAT-PDUK -->
            <div class="tab-pane fade" id="faq-psat" role="tabpanel" aria-labelledby="faq-psat-tab">
                <div class="accordion faq-acc" id="faqPsatAccordion">
                    @forelse($faqPsat as $i => $f)
                    <div class="accordion-item" style="background:var(--faq-card-bg);border:1px solid var(--faq-border);border-radius:14px;margin-bottom:0.75rem;overflow:hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqp-{{ $f->id }}" style="background:var(--faq-card-bg);color:var(--faq-text);font-weight:600;font-size:0.9rem;padding:1rem 1.25rem">
                                <span class="faq-q-num">{{ $i + 1 }}</span>{{ $f->question }}
                            </button>
                        </h2>
                        <div id="faqp-{{ $f->id }}" class="accordion-collapse collapse" data-bs-parent="#faqPsatAccordion">
                            <div class="accordion-body" style="background:var(--faq-card-bg);color:var(--faq-text);padding:0 1.25rem 1.1rem">
                                <p style="color:var(--faq-text);margin:0;font-size:0.85rem;line-height:1.65">{{ $f->answer }}</p>
                                @if($f->items)
                                <ul style="color:var(--faq-text);list-style:disc;padding-left:1.4rem;margin:0.4rem 0 0;font-size:0.85rem;line-height:1.65">
                                    @foreach($f->items as $it)
                                    <li style="color:var(--faq-text);margin-bottom:0.35rem">{{ $it }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4" style="color:var(--faq-text-muted)">
                        <i class="bi bi-inbox" style="font-size:1.8rem;opacity:0.4"></i>
                        <p class="mt-2 mb-0 small">Belum ada FAQ PSAT-PDUK.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kontak DPMPTSP -->
        <div class="mt-4 p-3 p-md-4" style="max-width:820px;margin:1.5rem auto 0;border-radius:var(--radius);background:linear-gradient(135deg,rgba(139,92,246,0.06),rgba(99,102,241,0.04));border:1px solid var(--faq-border)">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                <span style="width:38px;height:38px;border-radius:10px;background:rgba(139,92,246,0.12);color:#8b5cf6;display:flex;align-items:center;justify-content:center"><i class="bi bi-headset"></i></span>
                <div>
                    <div class="fw-bold" style="font-size:0.95rem;color:var(--faq-text)">Butuh informasi lebih lanjut?</div>
                    <small style="color:var(--faq-text-muted);font-size:0.75rem">Hubungi DPMPTSP Kota Tual</small>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-center gap-3" style="font-size:0.82rem">
                <a href="https://dpmptsp.tualkota.go.id/" target="_blank" rel="noopener" class="text-decoration-none" style="color:#0ea5e9"><i class="bi bi-globe me-1"></i>dpmptsp.tualkota.go.id</a>
                <a href="https://wa.me/62811441233" target="_blank" rel="noopener" class="text-decoration-none" style="color:#10b981"><i class="bi bi-whatsapp me-1"></i>0811 441 233</a>
                <span style="color:#6366f1"><i class="bi bi-instagram me-1"></i>@dpmptsptktual</span>
                <span style="color:#3b82f6"><i class="bi bi-facebook me-1"></i>Dinas Pmptsptk Kota Tual</span>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ====== Dropdown Desa dinamis (dependen Kecamatan) ======
        const KEC_DESA = @json($kecamatanDesa);
        const kecSelect = document.getElementById('filterKecamatan');
        const desaSelect = document.getElementById('filterDesa');
        const selectedDesa = {{ json_encode(request('desa')) }};

        function renderDesa() {
            if (!kecSelect || !desaSelect) return;
            const kec = kecSelect.value;
            desaSelect.innerHTML = '<option value="">Semua Desa</option>';
            (KEC_DESA[kec] || []).forEach(function (d) {
                const opt = document.createElement('option');
                opt.value = d;
                opt.textContent = d;
                if (d === selectedDesa) opt.selected = true;
                desaSelect.appendChild(opt);
            });
        }
        if (kecSelect) {
            renderDesa();
            kecSelect.addEventListener('change', renderDesa);
        }

        // ====== Grafik Sebaran Pelaku Usaha per Kecamatan ======
        const ctx = document.getElementById('chartKecamatan');
        if (ctx && window.Chart) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Jumlah Pelaku Usaha',
                        data: @json($chartValues),
                        backgroundColor: [
                            'rgba(139,92,246,0.75)', 'rgba(99,102,241,0.75)',
                            'rgba(16,185,129,0.75)', 'rgba(245,158,11,0.75)',
                            'rgba(14,165,233,0.75)', 'rgba(239,68,68,0.75)'
                        ],
                        borderColor: [
                            '#8b5cf6', '#6366f1', '#10b981', '#f59e0b', '#0ea5e9', '#ef4444'
                        ],
                        borderWidth: 1.5,
                        borderRadius: 8,
                        maxBarThickness: 46
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, color: '#94a3b8' },
                            grid: { color: 'rgba(148,163,184,0.15)' }
                        },
                        x: {
                            ticks: { color: '#94a3b8', font: { size: 10 }, maxRotation: 35, minRotation: 0 },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
