@extends('layouts.admin')
@section('title', $umkm->business_name . ' - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<a href="{{ route('admin.umkms.index') }}" class="text-decoration-none mb-3 d-inline-block"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <div class="bg-primary bg-opacity-10 rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width:80px;height:80px"><i class="bi bi-shop text-primary fs-1"></i></div>
            <h5 class="fw-bold mt-3">{{ $umkm->business_name }}</h5>
            <span class="badge bg-{{ $umkm->status==='verified'?'success':'warning' }}">{{ ucfirst($umkm->status) }}</span>
            <hr>
            <div class="text-start small">
                <p><i class="bi bi-person me-2"></i>{{ $umkm->user->name }}</p>
                <p><i class="bi bi-envelope me-2"></i>{{ $umkm->user->email }}</p>
                <p><i class="bi bi-phone me-2"></i>{{ $umkm->phone }}</p>
                <p><i class="bi bi-geo-alt me-2"></i>{{ $umkm->address }}</p>
                <p><i class="bi bi-card-text me-2"></i>NIB: {{ $umkm->nib ?? '-' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h6 class="fw-bold">Produk ({{ $umkm->products->count() }})</h6>
            <div class="table-responsive"><table class="table table-sm"><thead><tr><th>Produk</th><th>Harga</th><th>Stok</th><th>Terjual</th></tr></thead>
            <tbody>@foreach($umkm->products as $p)<tr><td>{{ $p->name }}</td><td>Rp {{ number_format($p->price,  0,  ', ','.') }}</td><td>{{ $p->stock }}</td><td>{{ $p->total_sold }}</td></tr>@endforeach</tbody></table></div>
        </div>
    </div>
</div>
@endsection
