<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Laporan Ketersediaan Pangan</title>
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
    h3{font-size:12px;color:#1a5276;margin:14px 0 4px;border-bottom:2px solid #1a5276;padding-bottom:3px}
</style>
</head><body>
@include('admin.reports.partials.kop')
<div class="header" style="text-align:center;margin-bottom:12px">
    <h2 style="color:#1a5276;margin:0;font-size:13px">LAPORAN KETERSEDIAAN PANGAN (STOK &amp; PASOKAN)</h2>
    <p style="margin:3px 0 0;font-size:10px">{{ $periode ?? 'Semua Periode' }} | Tanggal Cetak: {{ now()->translatedFormat('d M Y') }}</p>
    <p class="sub">Laporan ketersediaan pangan terpisah dari laporan panel harga (revisi 14 Okt 2026)</p>
</div>

<table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Komoditas</th>
            <th rowspan="2">Satuan Stok</th>
            <th colspan="2">Pantauan Terakhir</th>
            <th rowspan="2">Rata-rata Stok</th>
            <th rowspan="2">Rata-rata Daya Tahan (hari)</th>
            <th rowspan="2">Jumlah Pantauan</th>
            <th rowspan="2">Status</th>
        </tr>
        <tr>
            <th>Tanggal</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @forelse($summaries as $i => $s)
        @php
            $latest = $s['latest'];
            $statusLabel = $latest ? match($latest->stock_status) {
                'aman' => 'Aman', 'rawan' => 'Rawan', 'kritis' => 'Kritis', default => '-',
            } : '-';
        @endphp
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $s['commodity']->name }}</td>
            <td>{{ $latest->stock_unit ?? '-' }}</td>
            <td>{{ $latest?->monitoring_date?->format('d/m/Y') ?? '-' }}</td>
            <td>{{ $latest ? number_format($latest->stock_quantity, 0, ',', '.') : '-' }}</td>
            <td>{{ $s['avg_stock'] !== null ? number_format($s['avg_stock'], 0, ',', '.') : '-' }}</td>
            <td>{{ $s['avg_duration'] !== null ? $s['avg_duration'] : '-' }}</td>
            <td>{{ $s['records'] }}</td>
            <td>{{ $statusLabel }}</td>
        </tr>

        {{-- Riwayat pemantauan dalam periode (riwayat stok) --}}
        @if($s['history']->count() > 1)
        <tr>
            <td colspan="9" style="background:#f8fafc;font-size:9px">
                <strong>Riwayat pantauan (terbaru):</strong>
                @foreach($s['history'] as $h)
                    {{ $h->monitoring_date->format('d/m/y') }}: {{ number_format($h->stock_quantity, 0, ',', '.') }} {{ $h->stock_unit }} ({{ ucfirst($h->stock_status) }})@if(!$loop->last) &nbsp;|&nbsp; @endif
                @endforeach
            </td>
        </tr>
        @endif
        @empty
        <tr><td colspan="9" style="text-align:center;padding:16px">Tidak ada data ketersediaan pada periode ini.</td></tr>
        @endforelse
    </tbody>
</table>

<p class="sub" style="margin-top:10px">Catatan: Status stok — Aman (cukup &gt; kebutuhan mingguan), Rawan (mendekati kebutuhan), Kritis (di bawah kebutuhan).</p>
@include('admin.reports.partials.footer')
</body></html>
