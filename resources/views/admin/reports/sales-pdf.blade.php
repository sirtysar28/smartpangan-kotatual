<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Laporan Penjualan</title>
<style>body{font-family:sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:6px;text-align:left}th{background:#1a5276;color:#fff}.header{text-align:center;margin-bottom:20px}.header h2{color:#1a5276;margin:0}</style>
</head><body>
@include('admin.reports.partials.kop')
<div class="header" style="text-align:center;margin-bottom:12px"><h2 style="color:#1a5276;margin:0;font-size:13px">LAPORAN PENJUALAN</h2><p style="margin:3px 0 0;font-size:10px">Periode: {{ $summary['start_date'] }} s/d {{ $summary['end_date'] }}</p></div>
<p>Total Pesanan: <strong>{{ $summary['total_orders'] }}</strong> | Total Pendapatan: <strong>Rp {{ number_format($summary['total_revenue'],  0,  ', ','.') }}</strong></p>
<table><thead><tr><th>No</th><th>No. Pesanan</th><th>Pembeli</th><th>UMKM</th><th>Total</th><th>Status</th><th>Tanggal</th></tr></thead>
<tbody>@foreach($orders as $i=>$o)<tr><td>{{ $i+1 }}</td><td>{{ $o->order_number }}</td><td>{{ $o->user->name }}</td><td>{{ $o->umkm->business_name }}</td><td>Rp {{ number_format($o->total_amount,  0,  ', ','.') }}</td><td>{{ $o->status }}</td><td>{{ $o->created_at->format('d/m/Y') }}</td></tr>@endforeach</tbody></table>
<p style="text-align:center;font-size:9px;color:#94a3b8;margin:14px 0 0">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
@include('admin.reports.partials.footer')
</body></html>
