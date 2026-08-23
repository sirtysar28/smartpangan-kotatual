<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Produk Terlaris</title>
<style>body{font-family:sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:6px;text-align:left}th{background:#1a5276;color:#fff}.header{text-align:center;margin-bottom:20px}.header h2{color:#1a5276;margin:0}</style>
</head><body>
<div class="header"><h2>Pangan Cerdas (Panca) Maryadat Kota Tual - Produk Terlaris</h2><p>Tanggal: {{ now()->format('d M Y') }}</p></div>
<table><thead><tr><th>No</th><th>Produk</th><th>UMKM</th><th>Kategori</th><th>Harga</th><th>Terjual</th></tr></thead>
<tbody>@foreach($products as $i=>$p)<tr><td>{{ $i+1 }}</td><td>{{ $p->name }}</td><td>{{ $p->umkm->business_name }}</td><td>{{ $p->category->name }}</td><td>Rp {{ number_format($p->price,  0,  ', ','.') }}</td><td>{{ $p->total_sold }}</td></tr>@endforeach</tbody></table>
<p class="text-center text-muted" style="margin-top:20px">Pangan Cerdas (Panca) Maryadat Kota Tual &copy; Pemkot Tual</p>
</body></html>
