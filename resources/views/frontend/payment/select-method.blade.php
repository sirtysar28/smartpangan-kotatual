@extends('layouts.app')
@section('title', 'Pilih Metode Pembayaran - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-wallet2 me-2"></i>Pilih Metode Pembayaran</h4>

    <div class="row g-4">
        {{-- Order Summary --}}
        <div class="col-lg-5 order-lg-2">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <h6 class="fw-bold mb-3">Ringkasan Pesanan</h6>
                <p class="text-muted small mb-3">Pesanan <strong>#{{ $order->order_number }}</strong></p>
                @foreach($order->items as $item)
                <div class="d-flex justify-content-between small mb-2">
                    <span>{{ $item->product_name }} x{{ $item->quantity }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-2">
                    <span>Ongkir ({{ strtoupper($order->shipping_courier ?? 'JNE') }})</span>
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total Bayar</span>
                    <span class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Payment Methods --}}
        <div class="col-lg-7 order-lg-1">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-3">
                <h6 class="fw-bold mb-3"><i class="bi bi-credit-card-2-front me-2"></i>Metode Pembayaran</h6>

                {{-- Midtrans Online Payment --}}
                <a href="{{ route('payment.pay', $order) }}" class="text-decoration-none">
                    <div class="border rounded-3 p-3 mb-3 payment-option d-flex align-items-center {{ ($order->payment_method === 'midtrans') ? 'border-primary bg-primary bg-opacity-5' : 'hover-border-primary' }}" style="cursor:pointer; transition: all .2s;">
                        <div class="me-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                                <i class="bi bi-phone text-primary" style="font-size:1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 text-dark">Pembayaran Online (Midtrans)</h6>
                            <small class="text-muted d-block mb-1">Bayar langsung via QRIS, GoPay, ShopeePay, Dana, LinkAja, atau Virtual Account (BCA, BNI, BRI, Mandiri).</small>
                            <small class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-lightning me-1"></i>Verifikasi otomatis & instan</small>
                        </div>
                        <div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </div>
                </a>

                {{-- Bank Transfer Manual --}}
                <a href="{{ route('payment.transfer', $order) }}" class="text-decoration-none">
                    <div class="border rounded-3 p-3 payment-option d-flex align-items-center {{ ($order->payment_method === 'transfer_bank') ? 'border-warning bg-warning bg-opacity-5' : 'hover-border-warning' }}" style="cursor:pointer; transition: all .2s;">
                        <div class="me-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                                <i class="bi bi-bank text-warning" style="font-size:1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 text-dark">Transfer Bank Manual</h6>
                            <small class="text-muted d-block mb-1">Transfer via ATM, Mobile Banking, atau Internet Banking ke rekening berikut.</small>
                            <small class="badge bg-warning bg-opacity-10 text-warning"><i class="bi bi-clock me-1"></i>Verifikasi manual 1x24 jam</small>
                        </div>
                        <div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Info --}}
            <div class="alert alert-info small">
                <i class="bi bi-shield-check me-2"></i>
                <strong>Perhatian:</strong> Pesanan akan otomatis kadaluarsa dalam <strong>24 jam</strong> jika belum dibayar. Pastikan transfer sesuai dengan total yang tertera.
            </div>
        </div>
    </div>
</div>

<style>
    .payment-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08) !important;
    }
    .payment-option:hover .bi-chevron-right {
        transform: translateX(4px);
    }
</style>
@endsection
