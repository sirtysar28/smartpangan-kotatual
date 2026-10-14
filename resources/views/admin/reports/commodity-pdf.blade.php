<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Laporan Panel Harga</title>
<style>
    body{font-family:sans-serif;font-size:11px}
    table{width:100%;border-collapse:collapse}
    th,td{border:1px solid #ddd;padding:5px;text-align:left}
    th{background:#1a5276;color:#fff}
    .header{text-align:center;margin-bottom:20px}
    .header h2{color:#1a5276;margin:0}
    .sub{font-size:9px;color:#64748b}
    .up{color:#ef4444;font-weight:600}
    .down{color:#10b981;font-weight:600}
    .flat{color:#64748b;font-weight:600}
</style>
</head><body>
@include('admin.reports.partials.kop')
<div class="header" style="text-align:center;margin-bottom:12px">
    <h2 style="color:#1a5276;margin:0;font-size:13px">LAPORAN PANEL HARGA KOMODITAS PANGAN</h2>
    <p style="margin:3px 0 0;font-size:10px">{{ $periode ?? 'Semua Periode' }} | Tanggal Cetak: {{ now()->translatedFormat('d M Y') }}</p>
    <p class="sub">Termasuk perbandingan harga: minggu ke minggu, bulan lalu, dan tahun lalu (revisi 14 Okt 2026)</p>
</div>

{{-- Ringkasan tren --}}
@php
    $naik = $commodities->filter(fn($c) => ($c->price_comparison['mtm_percent'] ?? 0) > 0)->count();
    $turun = $commodities->filter(fn($c) => ($c->price_comparison['mtm_percent'] ?? 0) < 0)->count();
    $stabil = $commodities->count() - $naik - $turun;
@endphp
<p style="font-size:10px;margin:0 0 8px">
    <strong>Ringkasan (vs bulan lalu):</strong>
    <span class="up">Naik {{ $naik }}</span> &nbsp;·&nbsp;
    <span class="down">Turun {{ $turun }}</span> &nbsp;·&nbsp;
    <span class="flat">Stabil {{ $stabil }}</span> &nbsp;·&nbsp;
    Total {{ $commodities->count() }} komoditas
</p>

<table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Komoditas</th>
            <th rowspan="2">Satuan</th>
            <th rowspan="2">Harga Terakhir</th>
            <th rowspan="2">Tgl Harga</th>
            <th colspan="2">vs Minggu Lalu</th>
            <th colspan="2">vs Bulan Lalu</th>
            <th colspan="2">vs Tahun Lalu</th>
        </tr>
        <tr>
            <th>Harga</th><th>%</th>
            <th>Harga</th><th>%</th>
            <th>Harga</th><th>%</th>
        </tr>
    </thead>
    <tbody>
        @foreach($commodities as $i=>$c)
        @php
            $pc = $c->price_comparison ?? [];
            $fmtPct = function($pct) {
                if ($pct === null) return '<span class="flat">-</span>';
                $cls = $pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat');
                $sign = $pct > 0 ? '+' : '';
                return '<span class="'.$cls.'">'.$sign.number_format($pct, 1).'%</span>';
            };
            $fmtPrice = fn($p) => $p !== null ? 'Rp '.number_format($p, 0, ',', '.') : '-';
        @endphp
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $c->name }}</td>
            <td>{{ $c->unit }}</td>
            <td>Rp {{ number_format($c->latestPrice?->price ?? 0, 0, ',', '.') }}</td>
            <td>{{ $c->latestPrice?->price_date?->format('d/m/y') ?? '-' }}</td>
            <td>{{ $fmtPrice($pc['last_week_price'] ?? null) }}</td>
            <td>{!! $fmtPct($pc['wtw_percent'] ?? null) !!}</td>
            <td>{{ $fmtPrice($pc['last_month_price'] ?? null) }}</td>
            <td>{!! $fmtPct($pc['mtm_percent'] ?? null) !!}</td>
            <td>{{ $fmtPrice($pc['last_year_price'] ?? null) }}</td>
            <td>{!! $fmtPct($pc['yoy_percent'] ?? null) !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Riwayat harga per komoditas (riwayat mingguan/bulanan dalam periode) --}}
@foreach($commodities->take(15) as $c)
    @if($c->prices->count() > 1)
    <h3>Riwayat Harga — {{ $c->name }} ({{ $c->unit }})</h3>
    <table>
        <thead><tr><th>No</th><th>Tanggal</th><th>Harga</th><th>Perubahan</th></tr></thead>
        <tbody>
            @foreach($c->prices->sortByDesc('price_date')->values() as $j => $p)
            <tr>
                <td>{{ $j+1 }}</td>
                <td>{{ $p->price_date->format('d/m/Y') }}</td>
                <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                <td>{!! $fmtPct($p->change_percent) !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endforeach

@include('admin.reports.partials.footer')
</body></html>
