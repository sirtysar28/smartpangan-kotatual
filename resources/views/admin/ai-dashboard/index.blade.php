@extends('layouts.admin')
@section('title', 'AI Dashboard - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-robot me-2"></i>AI Market Intelligence 🤖</h4>
        <p class="text-muted mb-0 small">Analisis AI untuk tren pasar, stok, dan rekomendasi bisnis UMKM</p>
    </div>
    <form action="{{ route('admin.ai-dashboard.generate') }}" method="POST">@csrf
        <button class="btn btn-primary-modern"><i class="bi bi-lightning me-2"></i>Generate Insights</button>
    </form>
</div>

<div class="row g-4">
    <div class="col-lg-6 animate-fade-up delay-1">
        <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3 pb-0">
                <h6 class="fw-bold" style="font-size:0.95rem"><i class="bi bi-trophy me-2" style="color:#f59e0b"></i>Produk Terlaris</h6>
            </div>
            <div class="p-3 pt-2">
                @foreach($trendingProducts->take(10) as $i=>$p)
                <div class="d-flex align-items-center gap-2 mb-2 p-2" style="border-radius:10px;background:{{ $i < 3 ? 'linear-gradient(135deg,rgba(245,158,11,0.06),rgba(99,102,241,0.03))' : 'var(--bg)' }}">
                    <span class="badge-modern" style="background:{{ $i < 3 ? 'linear-gradient(135deg,#f59e0b,#f97316);color:#fff' : 'rgba(100,116,139,0.1);color:#64748b' }};width:28px;height:28px;display:flex;align-items:center;justify-content:center;padding:0;border-radius:8px;font-size:0.75rem">{{ $i+1 }}</span>
                    <div class="flex-grow-1">
                        <small class="fw-semibold" style="font-size:0.82rem">{{ $p->name }}</small>
                        <div class="text-muted" style="font-size:0.7rem">{{ $p->umkm->business_name }}</div>
                    </div>
                    <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981;font-size:0.7rem">{{ $p->total_sold }} terjual</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-6 animate-fade-up delay-2">
        <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3 pb-0">
                <h6 class="fw-bold" style="font-size:0.95rem"><i class="bi bi-exclamation-triangle me-2" style="color:#ef4444"></i>Peringatan Stok</h6>
            </div>
            <div class="p-3 pt-2">
                @foreach($stockAlerts->take(8) as $p)
                <div class="d-flex justify-content-between align-items-center mb-2 p-2" style="border-radius:10px;background:{{ $p->stock<=5?'rgba(239,68,68,0.06);border-left:3px solid #ef4444':'rgba(245,158,11,0.06);border-left:3px solid #f59e0b' }}">
                    <div>
                        <small class="fw-semibold" style="font-size:0.82rem">{{ $p->name }}</small>
                        <div class="text-muted" style="font-size:0.7rem">{{ $p->umkm->business_name }}</div>
                    </div>
                    <span class="badge-modern" style="background:{{ $p->stock<=5?'rgba(239,68,68,0.1);color:#ef4444':'rgba(245,158,11,0.1);color:#f59e0b' }};font-size:0.7rem">{{ $p->stock }} sisa</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- AI Insights -->
<div class="card border-0 mt-4 animate-fade-up delay-3" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-3">
        <h6 class="fw-bold mb-3" style="font-size:0.95rem"><i class="bi bi-lightbulb me-2" style="color:#6366f1"></i>Semua AI Insights</h6>
        @foreach($allInsights as $insight)
        @php
            $sev = $insight->severity;
            if ($sev === 'critical') { $bgS='rgba(239,68,68,0.06)'; $bdrS='#ef4444'; $icS='exclamation-triangle-fill'; $icC='#ef4444'; }
            elseif ($sev === 'warning') { $bgS='rgba(245,158,11,0.06)'; $bdrS='#f59e0b'; $icS='exclamation-circle-fill'; $icC='#f59e0b'; }
            else { $bgS='rgba(99,102,241,0.06)'; $bdrS='#6366f1'; $icS='lightbulb-fill'; $icC='#6366f1'; }
        @endphp
        <div class="d-flex gap-3 mb-2 p-2" style="border-radius:10px;background:{{ $bgS }};border-left:3px solid {{ $bdrS }}">
            <i class="bi bi-{{ $icS }}" style="font-size:1.3rem;color:{{ $icC }};margin-top:2px"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold small">{{ $insight->title }}</div>
                <p class="text-muted small mb-1">{{ $insight->description }}</p>
                <small class="text-muted">Confidence: {{ $insight->confidence_score }}% · {{ $insight->created_at->diffForHumans() }}</small>
            </div>
        </div>
        @endforeach
        {{ $allInsights->withQueryString()->links() }}
    </div>
</div>
@endsection