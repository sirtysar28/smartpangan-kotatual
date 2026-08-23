@extends('layouts.app')
@section('title', 'Pembayaran Berhasil - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-5 text-center">
    <div class="card border-0 shadow rounded-4 p-5 mx-auto" style="max-width:550px">
        <div class="mb-4">
            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:100px;height:100px;">
                <i class="bi bi-check-circle text-success" style="font-size:3.5rem;"></i>
            </div>
        </div>
        <h3 class="fw-bold text-success">Pembayaran Berhasil!</h3>
        <p class="text-muted mt-2">Terima kasih atas pembayaran Anda.</p>

        <div class="text-start p-3 bg-light rounded-3 mt-3">
            <div class="row mb-2">
                <div class="col-4 text-muted"><small>Nomor Pesanan</small></div>
                <div class="col-8 fw-semibold">#{{ $order->order_number }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-4 text-muted"><small>Total Pembayaran</small></div>
                <div class="col-8 fw-bold text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
            </div>
            @if($order->payment_method)
            <div class="row mb-2">
                <div class="col-4 text-muted"><small>Metode Bayar</small></div>
                <div class="col-8 fw-semibold text-uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</div>
            </div>
            @endif
            <div class="row">
                <div class="col-4 text-muted"><small>Status</small></div>
                <div class="col-8">
                    <span class="badge bg-success">{{ $order->status_text }}</span>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-3 mb-0 text-start">
            <small><i class="bi bi-info-circle me-1"></i>Pesanan Anda sedang diproses oleh penjual. Anda bisa melacak status pesanan di halaman <strong>Pesanan Saya</strong>.</small>
        </div>

        <div class="d-flex gap-2 justify-content-center mt-4">
            <a href="{{ route('orders.show', $order) }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-box me-1"></i>Lihat Pesanan
            </a>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-list me-1"></i>Pesanan Saya
            </a>
        </div>
    </div>
</div>
@endsection
