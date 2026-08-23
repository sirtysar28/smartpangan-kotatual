@extends('layouts.app')
@section('title', 'Menunggu Pembayaran - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-5 text-center">
    <div class="card border-0 shadow rounded-4 p-5 mx-auto" style="max-width:550px">
        <div class="mb-4">
            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:100px;height:100px;">
                <i class="bi bi-clock text-warning" style="font-size:3.5rem;"></i>
            </div>
        </div>
        <h3 class="fw-bold">Menunggu Pembayaran</h3>
        <p class="text-muted mt-2">Pesanan Anda sedang menunggu pembayaran.</p>

        <div class="text-start p-3 bg-light rounded-3 mt-3">
            <div class="row mb-2">
                <div class="col-4 text-muted"><small>Nomor Pesanan</small></div>
                <div class="col-8 fw-semibold">#{{ $order->order_number }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-4 text-muted"><small>Total</small></div>
                <div class="col-8 fw-bold text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-4 text-muted"><small>Batas Bayar</small></div>
                <div class="col-8 fw-semibold text-danger">
                    {{ $order->expired_at ? $order->expired_at->addHours(24)->format('d M Y, H:i') : now()->addHours(24)->format('d M Y, H:i') }} WIT
                </div>
            </div>
            <div class="row">
                <div class="col-4 text-muted"><small>Status</small></div>
                <div class="col-8">
                    <span class="badge bg-warning text-dark">{{ $order->status_text }}</span>
                </div>
            </div>
        </div>

        <div class="alert alert-warning mt-3 mb-0 text-start">
            <small><i class="bi bi-exclamation-triangle me-1"></i>Silakan selesaikan pembayaran sebelum batas waktu. Pesanan akan otomatis kadaluarsa jika tidak dibayar.</small>
        </div>

        <div class="d-flex gap-2 justify-content-center mt-4">
            <a href="{{ route('payment.pay', $order) }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-credit-card me-1"></i>Bayar Sekarang
            </a>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-list me-1"></i>Pesanan Saya
            </a>
        </div>
    </div>
</div>
@endsection
