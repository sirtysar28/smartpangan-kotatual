@extends('layouts.admin')
@section('title', 'Dashboard Inflasi - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-bar-chart me-2"></i>Dashboard Inflasi Daerah 📈</h4>
        <p class="text-muted mb-0 small">Monitoring perubahan harga komoditas pangan</p>
    </div>
</div>

<!-- Color Legend -->
<div class="d-flex gap-3 mb-3 small animate-fade-up">
    <span style="color:#ef4444"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>Naik Tinggi (>5%)</span>
    <span style="color:#f59e0b"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>Naik (<5%)</span>
    <span style="color:#10b981"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>Stabil / Turun</span>
</div>

<div class="row g-4">
    <div class="col-lg-8 animate-fade-up delay-1">
        <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3 pb-0">
                <h6 class="fw-bold" style="font-size:0.95rem">Harga Komoditas Pangan</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead><tr><th>Komoditas</th><th>Harga</th><th>Perubahan</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($commodities as $c)
                    <tr>
                        <td class="fw-semibold" style="font-size:0.85rem">{{ $c->name }}</td>
                        <td>Rp {{ number_format($c->latestPrice?->price ?? 0,  0,  ', ','.') }} / {{ $c->unit }}</td>
                        <td>
                            @if($c->latestPrice?->change_percent)
                                <span style="color:{{ $c->latestPrice->change_percent > 5 ? '#ef4444' : ($c->latestPrice->change_percent > 0 ? '#f59e0b' : '#10b981') }};font-weight:600">
                                    {{ $c->latestPrice->change_percent > 0 ? '↑' : '↓' }} {{ abs($c->latestPrice->change_percent) }}%
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($c->latestPrice?->change_percent > 5)
                                <span class="badge-modern" style="background:rgba(239,68,68,0.1);color:#ef4444">Naik Tinggi</span>
                            @elseif($c->latestPrice?->change_percent > 0)
                                <span class="badge-modern" style="background:rgba(245,158,11,0.1);color:#f59e0b">Naik</span>
                            @else
                                <span class="badge-modern" style="background:rgba(16,185,129,0.1);color:#10b981">Stabil</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 mb-4 animate-fade-up delay-2" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <h6 class="fw-bold mb-3" style="font-size:0.95rem;color:#ef4444"><i class="bi bi-graph-up-arrow me-2"></i>Harga Naik (7 hari)</h6>
                @foreach($priceIncreases->take(8) as $p)
                <div class="d-flex justify-content-between align-items-center mb-2 p-2" style="border-radius:8px;background:rgba(239,68,68,0.04);border-left:2px solid #ef4444">
                    <div>
                        <small class="fw-semibold">{{ $p->commodity->name }}</small>
                        <div class="text-muted" style="font-size:0.7rem">Rp {{ number_format($p->price,  0,  ', ','.') }}</div>
                    </div>
                    <span class="badge-modern" style="background:rgba(239,68,68,0.1);color:#ef4444">+{{ $p->change_percent }}%</span>
                </div>
                @endforeach
            </div>
        </div>
        @if($inflationPredictions->count() > 0)
        <div class="card border-0 animate-fade-up delay-3" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <h6 class="fw-bold mb-3" style="font-size:0.95rem;color:#6366f1"><i class="bi bi-robot me-2"></i>Prediksi Inflasi AI</h6>
                @foreach($inflationPredictions as $pred)
                <div class="p-2 mb-2" style="border-radius:8px;background:rgba(99,102,241,0.04);border-left:2px solid #6366f1">
                    <strong style="font-size:0.82rem">{{ $pred->title }}</strong>
                    <p class="text-muted small mb-1">{{ $pred->description }}</p>
                    <small class="text-muted">Confidence: {{ $pred->confidence_score }}%</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection