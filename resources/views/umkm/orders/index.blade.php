@extends('layouts.umkm')
@section('title', 'Pesanan Masuk - UMKM Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-bag me-2"></i>Pesanan Masuk</h5>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light"><tr><th>No. Pesanan</th><th>Pembeli</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
        <tbody>
        @foreach($orders as $o)
        <tr>
            <td class="fw-bold">{{ $o->order_number }}</td>
            <td>{{ $o->user->name }}</td>
            <td>Rp {{ number_format($o->total_amount, 0, ', ','.') }}</td>
            <td><span class="badge bg-{{ $o->statusBadge }}">{{ $o->statusText }}</span></td>
            <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
            <td class="d-flex gap-1">
                <a href="{{ route('umkm.orders.show',$o) }}" class="btn btn-sm btn-outline-primary rounded-pill">Detail</a>
                @if($o->status==='paid')
                    <form action="{{ route('umkm.orders.process',$o) }}" method="POST">@csrf<button class="btn btn-sm btn-success rounded-pill">Proses</button></form>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $orders->withQueryString()->links() }}
@endsection
