@extends('layouts.admin')
@section('title', 'Laporan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<style>
    /* ===== REPORT CARDS — seragam & rapi ===== */
    .report-grid .report-card {
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid rgba(0,0,0,0.04);
        background: var(--bg-card);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform .35s cubic-bezier(.4,0,.2,1), box-shadow .35s cubic-bezier(.4,0,.2,1);
        position: relative;
        overflow: hidden;
    }
    .report-grid .report-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: var(--rc-accent, #6366f1);
    }
    .report-grid .report-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    .report-grid .report-body {
        padding: 1.15rem;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
    }
    .report-grid .report-head {
        display: flex;
        align-items: center;
        gap: .85rem;
        min-height: 60px;
        margin-bottom: 1rem;
    }
    .report-grid .report-icon {
        width: 50px; height: 50px;
        border-radius: 14px;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        background: var(--rc-soft, rgba(99,102,241,0.1));
        color: var(--rc-accent, #6366f1);
        transition: transform .35s cubic-bezier(.4,0,.2,1);
    }
    .report-grid .report-card:hover .report-icon { transform: scale(1.08) rotate(-4deg); }
    .report-grid .report-title {
        font-weight: 700;
        font-size: .95rem;
        margin: 0;
        line-height: 1.3;
        color: var(--text-main);
    }
    .report-grid .report-desc {
        font-size: .74rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.4;
    }
    .report-grid .report-form {
        margin-top: auto;          /* pin form ke bawah agar tombol sejajar antar card */
        display: flex;
        flex-direction: column;
        gap: .8rem;
    }
    .report-grid .form-label {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--text-muted);
        margin-bottom: .3rem;
        white-space: nowrap;
    }
    .report-grid .form-control, .report-grid .form-select {
        font-size: .83rem;
        border-radius: 9px;
        padding: .48rem .65rem;
        min-height: 40px;
    }
    .report-btn {
        border: none;
        border-radius: 11px;
        font-weight: 700;
        font-size: .84rem;
        padding: .68rem 1rem;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        cursor: pointer;
        color: #fff;
        background: var(--rc-accent, #6366f1);
        box-shadow: 0 4px 14px var(--rc-shadow, rgba(99,102,241,0.35));
        transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
        white-space: nowrap;
    }
    .report-btn:hover { transform: translateY(-2px); filter: brightness(1.07); color: #fff; }
    .report-btn:active { transform: translateY(0); }
    .report-hint {
        font-size: .7rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: .35rem;
        margin: 0;
    }
    /* Dark mode */
    html.dark-mode .report-grid .report-card { border-color: #334155; }
    /* Mobile */
    @media (max-width: 767.98px) {
        .report-grid .report-head { min-height: auto; }
        .report-grid .report-body { padding: 1rem; }
        .report-grid .report-icon { width: 44px; height: 44px; font-size: 1.1rem; border-radius: 12px; }
        .report-grid .report-title { font-size: .88rem; }
        .report-btn { font-size: .8rem; padding: .6rem .8rem; }
    }
    @media (max-width: 360px) {
        .report-grid .report-body { padding: .85rem; }
        .report-grid .report-head { gap: .6rem; }
    }
</style>

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-file-earmark-bar-graph me-2"></i>Export Laporan 📄</h4>
        <p class="text-muted mb-0 small">Download laporan PDF — semua laporan dapat difilter berdasarkan rentang tanggal</p>
    </div>
    <span class="badge-modern" style="background:rgba(99,102,241,0.1);color:#6366f1"><i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}</span>
</div>

<div class="row g-3 g-xl-4 report-grid">

    {{-- ============ LAPORAN PANEL HARGA (default 1 tahun) ============ --}}
    <div class="col-12 col-md-6 col-xl-4 animate-fade-up delay-1">
        <div class="report-card" style="--rc-accent:#f59e0b;--rc-soft:rgba(245,158,11,0.12);--rc-shadow:rgba(245,158,11,0.3)">
            <div class="report-body">
                <div class="report-head">
                    <div class="report-icon"><i class="bi bi-graph-up"></i></div>
                    <div>
                        <h6 class="report-title">Laporan Panel Harga</h6>
                        <p class="report-desc">Harga komoditas + perbandingan minggu/bulan/tahun lalu</p>
                    </div>
                </div>
                <form action="{{ route('admin.reports.commodity') }}" method="POST" class="report-form">@csrf
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Dari Tanggal <em class="fw-normal text-lowercase">(default bulan ini)</em></label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Sampai</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', now()->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <button type="submit" class="report-btn"><i class="bi bi-download"></i>Download PDF</button>
                    <p class="report-hint"><i class="bi bi-arrow-left-right"></i>Laporan terpisah dari ketersediaan pangan</p>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ LAPORAN KETERSEDIAAN PANGAN (TERPISAH — revisi 14 Okt 2026) ============ --}}
    <div class="col-12 col-md-6 col-xl-4 animate-fade-up delay-2">
        <div class="report-card" style="--rc-accent:#10b981;--rc-soft:rgba(16,185,129,0.12);--rc-shadow:rgba(16,185,129,0.3)">
            <div class="report-body">
                <div class="report-head">
                    <div class="report-icon"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <h6 class="report-title">Laporan Ketersediaan Pangan</h6>
                        <p class="report-desc">Stok &amp; pasokan + riwayat pemantauan (data terpisah)</p>
                    </div>
                </div>
                <form action="{{ route('admin.reports.availability') }}" method="POST" class="report-form">@csrf
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Dari Tanggal <em class="fw-normal text-lowercase">(default bulan ini)</em></label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Sampai</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', now()->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <button type="submit" class="report-btn"><i class="bi bi-download"></i>Download PDF</button>
                    <p class="report-hint"><i class="bi bi-arrow-left-right"></i>Laporan terpisah dari panel harga</p>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ LAPORAN KEAMANAN PANGAN ============ --}}
    <div class="col-12 col-md-6 col-xl-4 animate-fade-up delay-3">
        <div class="report-card" style="--rc-accent:#a855f7;--rc-soft:rgba(168,85,247,0.12);--rc-shadow:rgba(168,85,247,0.3)">
            <div class="report-body">
                <div class="report-head">
                    <div class="report-icon"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <h6 class="report-title">Keamanan Pangan</h6>
                        <p class="report-desc">Rapid Test &amp; Uji Laboratorium</p>
                    </div>
                </div>
                <form action="{{ route('admin.reports.food-safety') }}" method="POST" class="report-form">@csrf
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Dari Tanggal <em class="fw-normal text-lowercase">(default bulan ini)</em></label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Sampai</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', now()->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Jenis Laporan</label>
                        <select name="report_type" class="form-select">
                            <option value="all">Semua (Rapid + Uji Lab)</option>
                            <option value="rapid">Hanya Rapid Test</option>
                            <option value="lab">Hanya Uji Laboratorium</option>
                        </select>
                    </div>
                    <button type="submit" class="report-btn"><i class="bi bi-file-earmark-pdf"></i>Download PDF</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ LAPORAN PERIZINAN (REKAP DOKUMEN — revisi 14 Okt 2026) ============ --}}
    <div class="col-12 col-md-6 col-xl-4 animate-fade-up delay-4">
        <div class="report-card" style="--rc-accent:#0ea5e9;--rc-soft:rgba(14,165,233,0.12);--rc-shadow:rgba(14,165,233,0.3)">
            <div class="report-body">
                <div class="report-head">
                    <div class="report-icon"><i class="bi bi-file-earmark-text"></i></div>
                    <div>
                        <h6 class="report-title">Laporan Perizinan Pangan</h6>
                        <p class="report-desc">Rekap pemilik usaha, alamat &amp; dokumen perizinan</p>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2" style="font-size:0.78rem;color:var(--text-muted);margin-top:auto">
                    <div class="d-flex gap-2"><i class="bi bi-check2-circle mt-1" style="color:#10b981"></i><span>Menampilkan <strong>pemilik usaha, alamat &amp; dokumen</strong> perizinan pangan.</span></div>
                    <div class="d-flex gap-2"><i class="bi bi-info-circle mt-1" style="color:#0ea5e9"></i><span>Bersifat <strong>laporan data</strong> — penerbitan izin tetap di sistem satu pintu.</span></div>
                </div>
                <a href="{{ route('admin.permits.index') }}" class="report-btn mt-3"><i class="bi bi-eye"></i>Lihat Daftar Perizinan</a>
            </div>
        </div>
    </div>

    {{-- ================= PETUNJUK ================= --}}
    <div class="col-12 col-md-6 col-xl-4 animate-fade-up delay-6">
        <div class="report-card" style="--rc-accent:#64748b;--rc-soft:rgba(100,116,139,0.12);--rc-shadow:rgba(100,116,139,0.25)">
            <div class="report-body">
                <div class="report-head">
                    <div class="report-icon"><i class="bi bi-info-circle"></i></div>
                    <div>
                        <h6 class="report-title">Petunjuk</h6>
                        <p class="report-desc">Cara menggunakan filter tanggal</p>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2" style="font-size:.78rem;color:var(--text-muted)">
                    <div class="d-flex gap-2"><i class="bi bi-check2-circle mt-1" style="color:#10b981"></i><span>Langsung klik <strong>Download PDF</strong> → laporan hanya <strong>bulan berjalan</strong>.</span></div>
                    <div class="d-flex gap-2"><i class="bi bi-check2-circle mt-1" style="color:#10b981"></i><span>Ubah rentang tanggal secara manual jika ingin periode lain (mis. bulan/tahun sebelumnya).</span></div>
                    <div class="d-flex gap-2"><i class="bi bi-check2-circle mt-1" style="color:#10b981"></i><span>Semua laporan diunduh dalam format <strong>PDF</strong> siap cetak.</span></div>
                </div>
                <p class="report-hint mt-3 mb-0"><i class="bi bi-clock-history"></i>Periode mengikuti tanggal pendataan pada sistem.</p>
            </div>
        </div>
    </div>

</div>
@endsection
