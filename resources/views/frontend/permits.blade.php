@extends('layouts.app')
@section('title', 'Perizinan Pangan - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<!-- Header -->
<section class="py-4" style="background:linear-gradient(135deg,#0f172a,#3b0764)">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <nav aria-label="breadcrumb" style="font-size:0.72rem">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;margin:0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.5);text-decoration:none">Beranda</a></li>
                        <li class="breadcrumb-item active" style="color:#8b5cf6">Perizinan Pangan</li>
                    </ol>
                </nav>
                <h4 class="fw-bold text-white mb-1"><i class="bi bi-file-earmark-text me-2"></i>Perizinan Pangan</h4>
                <small class="text-white-50">Data perizinan dan legalitas pelaku usaha pangan di Kota Tual</small>
            </div>
            <div class="d-flex gap-2">
                <span class="badge-modern" style="background:rgba(139,92,246,0.15);color:#8b5cf6"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>{{ $totalUmkms }} Total UMKM</span>
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
                    <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:#10b981"><i class="bi bi-patch-check-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#10b981">{{ $totalVerified }}</div>
                        <small class="text-muted">Terverifikasi</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #f59e0b">
                    <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#f59e0b">{{ $totalPending }}</div>
                        <small class="text-muted">Menunggu Verifikasi</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #ef4444">
                    <div class="stat-icon" style="background:rgba(239,68,68,0.1);color:#ef4444"><i class="bi bi-x-circle-fill"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:#ef4444">{{ $totalRejected }}</div>
                        <small class="text-muted">Ditolak</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-left:4px solid #6366f1">
                    <div class="stat-icon" style="background:rgba(99,102,241,0.1);color:#6366f1"><i class="bi bi-shop"></i></div>
                    <div>
                        <div class="fw-bold" style="font-size:1.5rem;color:var(--primary)">{{ $totalUmkms }}</div>
                        <small class="text-muted">Total UMKM Terdaftar</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Progress Bar -->
@if($totalUmkms > 0)
<section class="py-3" style="background:var(--bg)">
    <div class="container">
        <div class="p-3" style="border-radius:var(--radius);background:var(--bg-card);border:1px solid #e2e8f0">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0" style="font-size:0.85rem"><i class="bi bi-speedometer2 me-2" style="color:var(--primary)"></i>Progres Verifikasi UMKM</h6>
                <span class="fw-bold" style="color:var(--primary)">{{ $totalUmkms > 0 ? round(($totalVerified / $totalUmkms) * 100, 1) : 0 }}%</span>
            </div>
            <div style="height:10px;border-radius:10px;background:#e2e8f0;overflow:hidden">
                <div style="height:100%;border-radius:10px;width:{{ $totalUmkms > 0 ? ($totalVerified / $totalUmkms) * 100 : 0 }}%;background:linear-gradient(90deg,#10b981,#34d399);transition:width 1s ease"></div>
            </div>
            <div class="d-flex gap-4 mt-2">
                <small style="font-size:0.75rem;color:#10b981"><span style="width:8px;height:8px;border-radius:50%;background:#10b981;display:inline-block;margin-right:4px"></span>Terverifikasi: {{ $totalVerified }}</small>
                <small style="font-size:0.75rem;color:#f59e0b"><span style="width:8px;height:8px;border-radius:50%;background:#f59e0b;display:inline-block;margin-right:4px"></span>Menunggu: {{ $totalPending }}</small>
                <small style="font-size:0.75rem;color:#ef4444"><span style="width:8px;height:8px;border-radius:50%;background:#ef4444;display:inline-block;margin-right:4px"></span>Ditolak: {{ $totalRejected }}</small>
            </div>
        </div>
    </div>
</section>
@endif

<!-- UMKM Table -->
<section class="py-4" style="background:var(--bg-card)">
    <div class="container">
        <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden">
            <div class="card-header p-3" style="background:linear-gradient(135deg,rgba(139,92,246,0.06),rgba(99,102,241,0.04));border-bottom:1px solid rgba(139,92,246,0.08)">
                <h6 class="fw-bold mb-0" style="font-size:0.88rem"><i class="bi bi-list-ul me-2" style="color:#8b5cf6"></i>Daftar Pelaku Usaha Pangan (UMKM)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0" style="font-size:0.82rem">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Nama Usaha</th>
                                <th>Pemilik</th>
                                <th>Kategori</th>
                                <th>Produk</th>
                                <th>Status</th>
                                <th>Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($umkms as $i => $umkm)
                            @php
                                $uStatus = $umkm->status;
                                $uClr = match($uStatus) { 'verified' => '#10b981', 'active' => '#10b981', 'pending' => '#f59e0b', 'rejected' => '#ef4444', 'suspended' => '#ef4444', default => '#64748b' };
                                $uIcon = match($uStatus) { 'verified' => 'patch-check-fill', 'active' => 'check-circle-fill', 'pending' => 'hourglass-split', 'rejected' => 'x-circle-fill', 'suspended' => 'pause-circle-fill', default => 'dash-circle' };
                                $uLabel = match($uStatus) { 'verified' => 'Terverifikasi', 'active' => 'Aktif', 'pending' => 'Menunggu', 'rejected' => 'Ditolak', 'suspended' => 'Ditangguhkan', default => ucfirst($uStatus ?? '-') };
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $umkms->firstItem() + $i }}</td>
                                <td class="fw-semibold">{{ $umkm->business_name }}</td>
                                <td>{{ $umkm->user?->name ?? '-' }}</td>
                                <td>{{ $umkm->business_category ?? '-' }}</td>
                                <td>
                                    <span class="badge" style="background:rgba(99,102,241,0.08);color:var(--primary);font-size:0.65rem">
                                        {{ $umkm->products_count ?? 0 }} produk
                                    </span>
                                </td>
                                <td><span class="badge" style="background:{{ $uClr }}22;color:{{ $uClr }};font-size:0.7rem"><i class="bi bi-{{ $uIcon }} me-1"></i>{{ $uLabel }}</span></td>
                                <td><small class="text-muted">{{ $umkm->created_at?->format('d/m/Y') }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div style="font-size:2rem;opacity:0.3;margin-bottom:0.5rem"><i class="bi bi-shop"></i></div>
                                    <p class="text-muted mb-0">Belum ada data pelaku usaha pangan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($umkms->hasPages())
            <div class="card-footer p-3" style="background:var(--bg-card)">
                {{ $umkms->links() }}
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
