@extends('layouts.umkm')
@section('title', 'Detail Pesanan - UMKM Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="mb-3">
    <a href="{{ route('umkm.orders.index') }}" class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Kembali ke Pesanan</a>
</div>
<div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold"><i class="bi bi-bag me-2"></i>Pesanan #{{ $order->order_number }}</h5>
    <span class="badge bg-{{ $order->statusBadge }} fs-6">{{ $order->statusText }}</span>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-3 p-3">
            <h6 class="fw-bold">Item Pesanan</h6>
            @foreach($order->items as $item)
            <div class="d-flex gap-3 mb-3 align-items-center border-bottom pb-3">
                <img src="{{ $item->product_image ? Storage::url($item->product_image) : 'https://placehold.co/60x60/eee/999?text=IMG' }}" width="60" height="60" class="rounded">
                <div class="flex-grow-1">
                    <strong>{{ $item->product_name }}</strong><br>
                    <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price,  0,  ', ', '.') }}</small>
                </div>
                <strong class="text-primary">Rp {{ number_format($item->subtotal,  0,  ', ', '.') }}</strong>
            </div>
            @endforeach
        </div>

        @if($order->status === 'paid')
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
            <h6 class="fw-bold text-success"><i class="bi bi-check-circle me-2"></i>Pembayaran Diterima</h6>
            <p class="mb-2">Pesanan sudah dibayar. Silakan proses dan kirim.</p>
            <form action="{{ route('umkm.orders.process', $order) }}" method="POST">
                @csrf
                <button class="btn btn-primary rounded-pill"><i class="bi bi-gear me-1"></i>Mulai Proses Pesanan</button>
            </form>
        </div>
        @endif

        @if($order->status === 'processing')
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
            <h6 class="fw-bold"><i class="bi bi-truck me-2"></i>Kirim Pesanan</h6>
            <form action="{{ route('umkm.orders.ship', $order) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nomor Resi Pengiriman *</label>
                    <input type="text" name="tracking_number" class="form-control" placeholder="Masukkan no. resi..." required>
                </div>
                <button class="btn btn-success rounded-pill"><i class="bi bi-box-arrow-up me-1"></i>Kirim Pesanan</button>
            </form>
        </div>
        @endif

        @if($order->status === 'shipped')
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 bg-info bg-opacity-10">
            <h6 class="fw-bold text-info"><i class="bi bi-truck me-2"></i>Pesanan Sedang Dikirim</h6>
            <p>No. Resi: <strong>{{ $order->tracking_number }}</strong></p>
            <small class="text-muted">Menunggu konfirmasi dari pembeli.</small>
        </div>
        @endif

        @if($order->status === 'delivered')
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 bg-success bg-opacity-10">
            <h6 class="fw-bold text-success"><i class="bi bi-check-circle-fill me-2"></i>Pesanan Sudah Diterima</h6>
            <p class="mb-0">Pesanan telah diterima oleh pembeli pada {{ $order->delivered_at?->format('d M Y H:i') }}.</p>
        </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
            <h6 class="fw-bold">Info Pembeli</h6>
            <div class="small"><strong>Nama:</strong> {{ $order->user->name }}</div>
            <div class="small"><strong>Email:</strong> {{ $order->user->email }}</div>
            <div class="small"><strong>Telepon:</strong> {{ $order->user->phone ?? '-' }}</div>
        </div>
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
            <h6 class="fw-bold">Pengiriman</h6>
            <div class="small"><strong>Alamat:</strong> {{ $order->shipping_address }}</div>
            <div class="small"><strong>Kurir:</strong> {{ strtoupper($order->shipping_courier ?? '-') }}</div>
            @if($order->tracking_number)
            <div class="small"><strong>No. Resi:</strong> <span class="text-primary fw-bold">{{ $order->tracking_number }}</span></div>
            @endif
        </div>
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <h6 class="fw-bold">Ringkasan Pembayaran</h6>
            <div class="d-flex justify-content-between small mb-1">
                <span>Subtotal</span><span>Rp {{ number_format($order->subtotal,  0,  ', ', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between small mb-1">
                <span>Ongkir</span><span>Rp {{ number_format($order->shipping_cost,  0,  ', ', '.') }}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between fw-bold">
                <span>Total</span><span class="text-primary">Rp {{ number_format($order->total_amount,  0,  ', ', '.') }}</span>
            </div>
            @if($order->payment_method)
            <div class="small mt-2 text-muted">Metode: {{ strtoupper($order->payment_method) }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
