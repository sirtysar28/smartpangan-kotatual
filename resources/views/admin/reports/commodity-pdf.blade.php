<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Laporan Komoditas</title>
<style>body{font-family:sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:6px;text-align:left}th{background:#1a5276;color:#fff}.header{text-align:center;margin-bottom:20px}.header h2{color:#1a5276;margin:0}</style>
</head><body>
<div class="header"><h2>Pangan Cerdas (Panca) Maryadat Kota Tual - Laporan Harga Komoditas</h2><p>Tanggal: {{ now()->format('d M Y') }}</p></div>
<table><thead><tr><th>No</th><th>Komoditas</th><th>Satuan</th><th>Harga Terakhir</th><th>Perubahan</th></tr></thead>
<tbody>@foreach($commodities as $i=>$c)<tr><td>{{ $i+1 }}</td><td>{{ $c->name }}</td><td>{{ $c->unit }}</td><td>Rp {{ number_format($c->latestPrice?->price??0,  0,  ', ','.') }}</td><td>{{ $c->latestPrice?->change_percent ?? 0 }}%</td></tr>@endforeach</tbody></table>
<p class="text-center text-muted" style="margin-top:20px">Pangan Cerdas (Panca) Maryadat Kota Tual &copy; Pemkot Tual</p>
</body></html>
