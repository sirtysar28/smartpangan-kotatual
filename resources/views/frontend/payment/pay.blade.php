@extends('layouts.app')
@section('title', 'Pembayaran - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow rounded-4 p-4 text-center">
                <h4 class="fw-bold mb-3"><i class="bi bi-credit-card me-2"></i>Pembayaran</h4>
                <p>Pesanan <strong>#{{ $order->order_number }}</strong></p>
                <h3 class="text-primary fw-bold mb-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>

                <div class="text-start mb-3 p-3 bg-light rounded-3">
                    <small class="text-muted d-block mb-1">Detail Pesanan:</small>
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between">
                            <span>{{ $item->product_name }} x{{ $item->quantity }}</span>
                            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <hr class="my-2">
                    <div class="d-flex justify-content-between text-muted">
                        <span>Ongkir ({{ strtoupper($order->shipping_courier ?? 'JNE') }})</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold mt-1">
                        <span>Total</span>
                        <span class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div id="snap-container">
                    @if($snapToken)
                        <button id="pay-button" class="btn btn-primary btn-lg rounded-pill fw-semibold px-5">
                            <i class="bi bi-lock me-2"></i>Bayar Sekarang
                        </button>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>Gagal memuat payment gateway.
                            <br><small>Silakan coba lagi atau hubungi admin.</small>
                        </div>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary rounded-pill mt-2">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Pesanan
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@if($snapToken)
<script type="text/javascript" src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
document.getElementById('pay-button').onclick = function() {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            console.log('Payment success:', result);
            window.location.href = '{{ route("payment.success", $order->id) }}';
        },
        onPending: function(result) {
            console.log('Payment pending:', result);
            window.location.href = '{{ route("payment.pending", $order->id) }}';
        },
        onError: function(result) {
            console.log('Payment error:', result);
            window.location.href = '{{ route("payment.error", $order->id) }}';
        },
        onClose: function() {
            console.log('Payment popup closed by user');
        }
    });
};
</script>
@endif
@endsection
