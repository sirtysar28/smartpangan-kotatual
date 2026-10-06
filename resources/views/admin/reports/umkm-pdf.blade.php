<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Laporan UMKM</title>
<style>body{font-family:sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:6px;text-align:left}th{background:#1a5276;color:#fff}.header{text-align:center;margin-bottom:20px}.header h2{color:#1a5276;margin:0}</style>
</head><body>
@include('admin.reports.partials.kop')
<div class="header" style="text-align:center;margin-bottom:12px"><h2 style="color:#1a5276;margin:0;font-size:13px">LAPORAN UMKM</h2><p style="margin:3px 0 0;font-size:10px">{{ $periode ?? 'Semua Periode' }} | Total: {{ $umkms->count() }} UMKM | Tanggal Cetak: {{ now()->translatedFormat('d M Y') }}</p></div>
<table><thead><tr><th>No</th><th>Nama UMKM</th><th>Pemilik</th><th>Telepon</th><th>Produk</th><th>Total Penjualan</th></tr></thead>
<tbody>@foreach($umkms as $i=>$u)<tr><td>{{ $i+1 }}</td><td>{{ $u->business_name }}</td><td>{{ $u->user->name }}</td><td>{{ $u->phone }}</td><td>{{ $u->products->count() }}</td><td>Rp {{ number_format($u->orders->where('status', '!=', 'cancelled')->sum('total_amount'),0,',','.') }}</td></tr>@endforeach</tbody></table>
@include('admin.reports.partials.footer')
</body></html>
