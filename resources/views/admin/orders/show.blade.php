@extends('layouts.admin')
@section('title', 'Detail Pesanan - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<a href="{{ route('admin.orders.index') }}" class="text-decoration-none mb-3 d-inline-block small" style="color:var(--primary)"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <h6 class="fw-bold">Pesanan #{{ $order->order_number }}</h6>
                <div class="row g-2 mb-3 small">
                    <div class="col-6"><strong>Pembeli:</strong> {{ $order->user->name }} ({{ $order->user->email }})</div>
                    <div class="col-6"><strong>UMKM:</strong> {{ $order->umkm->business_name }}</div>
                    <div class="col-12"><strong>Alamat:</strong> {{ $order->shipping_address }}</div>
                    <div class="col-6"><strong>Kurir:</strong> {{ strtoupper($order->shipping_courier ?? '-') }}</div>
                    @if($order->tracking_number)
                    <div class="col-6"><strong>Resi:</strong> {{ $order->tracking_number }}</div>
                    @endif
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                    @foreach($order->items as $i)
                    <tr><td>{{ $i->product_name }}</td><td>Rp {{ number_format($i->price,  0,  ', ','.') }}</td><td>{{ $i->quantity }}</td><td class="fw-semibold">Rp {{ number_format($i->subtotal,  0,  ', ','.') }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 animate-fade-up delay-1" style="border-radius:var(--radius);box-shadow:var(--shadow)">
            <div class="p-3">
                <h6 class="fw-bold mb-3">Ringkasan</h6>
                @php
                    $st = $order->status;
                    if ($st === 'delivered') { $stBg = 'rgba(16,185,129,0.1)'; $stColor = '#10b981'; }
                    elseif ($st === 'cancelled') { $stBg = 'rgba(239,68,68,0.1)'; $stColor = '#ef4444'; }
                    else { $stBg = 'rgba(14,165,233,0.1)'; $stColor = '#0ea5e9'; }
                @endphp
                <span class="badge-modern mb-3" style="background:{{ $stBg }};color:{{ $stColor }}">{{ $order->statusText }}</span>
                <div class="d-flex justify-content-between small mb-1"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal,  0,  ', ','.') }}</span></div>
                <div class="d-flex justify-content-between small mb-2"><span>Ongkir</span><span>Rp {{ number_format($order->shipping_cost,  0,  ', ','.') }}</span></div>
                <hr style="margin:0.5rem 0">
                <div class="d-flex justify-content-between"><strong style="color:var(--primary);font-size:1.1rem">Total</strong><strong style="color:var(--primary);font-size:1.1rem">Rp {{ number_format($order->total_amount,  0,  ', ','.') }}</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection