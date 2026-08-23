<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Laporan UMKM</title>
<style>body{font-family:sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:6px;text-align:left}th{background:#1a5276;color:#fff}.header{text-align:center;margin-bottom:20px}.header h2{color:#1a5276;margin:0}</style>
</head><body>
<div class="header"><h2>Pangan Cerdas (Panca) Maryadat Kota Tual - Laporan UMKM</h2><p>Total: {{ $umkms->count() }} UMKM | Tanggal: {{ now()->format('d M Y') }}</p></div>
<table><thead><tr><th>No</th><th>Nama UMKM</th><th>Pemilik</th><th>Telepon</th><th>Produk</th><th>Total Penjualan</th></tr></thead>
<tbody>@foreach($umkms as $i=>$u)<tr><td>{{ $i+1 }}</td><td>{{ $u->business_name }}</td><td>{{ $u->user->name }}</td><td>{{ $u->phone }}</td><td>{{ $u->products->count() }}</td><td>Rp {{ number_format($u->orders->where('status', '!=', 'cancelled')->sum('total_amount'),0,',','.') }}</td></tr>@endforeach</tbody></table>
<p class="text-center text-muted" style="margin-top:20px">Pangan Cerdas (Panca) Maryadat Kota Tual &copy; Pemkot Tual</p>
</body></html>
