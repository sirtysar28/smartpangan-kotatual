@extends('layouts.app')
@section('title', 'Detail Pesanan - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h5 class="fw-bold"><i class="bi bi-bag me-2"></i>Pesanan #{{ $order->order_number }}</h5>
        <span class="badge bg-{{ $order->statusBadge }} bg-opacity-25 text-{{ $order->statusBadge }} fs-6">{{ $order->statusText }}</span>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-3 p-3">
                <h6 class="fw-bold">Item Pesanan</h6>
                @foreach($order->items as $item)
                <div class="d-flex gap-3 mb-3 align-items-center">
                    <img src="{{ $item->product_image ? Storage::url($item->product_image) : 'https://placehold.co/60x60/eee/999' }}" width="60" height="60" class="rounded">
                    <div class="flex-grow-1"><strong>{{ $item->product_name }}</strong><br><small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price,  0, ', ','.') }}</small></div>
                    <strong>Rp {{ number_format($item->subtotal,  0, ', ','.') }}</strong>
                </div>
                @endforeach
            </div>
            @if($order->status==='delivered')
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <h6 class="fw-bold">Beri Ulasan</h6>
                @foreach($order->items as $item)
                <form action="{{ route('orders.review', $order) }}" method="POST" class="mb-3 border-bottom pb-3">@csrf
                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                    <strong class="small">{{ $item->product_name }}</strong>
                    <div class="d-flex gap-1 my-2">
                        @for($i=1;$i<=5;$i++)<input type="radio" name="rating" value="{{ $i }}" {{ $i===1?'checked':'' }} class="btn-check" id="r{{ $item->product_id }}_{{ $i }}"><label for="r{{ $item->product_id }}_{{ $i }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-star-fill"></i></label>@endfor
                    </div>
                    <textarea name="comment" class="form-control form-control-sm" rows="2" placeholder="Tulis ulasan..."></textarea>
                    <button class="btn btn-sm btn-primary mt-2 rounded-pill">Kirim Ulasan</button>
                </form>
                @endforeach
            </div>
            @endif
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <h6 class="fw-bold">Ringkasan</h6>
                <div class="small"><strong>Alamat:</strong> {{ $order->shipping_address }}</div>
                <div class="small"><strong>Kurir:</strong> {{ strtoupper($order->shipping_courier ?? '-') }}</div>
                @if($order->tracking_number)<div class="small"><strong>No. Resi:</strong> {{ $order->tracking_number }}</div>@endif
                <hr>
                <div class="d-flex justify-content-between small"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal,  0, ', ','.') }}</span></div>
                <div class="d-flex justify-content-between small"><span>Ongkir</span><span>Rp {{ number_format($order->shipping_cost,  0, ', ','.') }}</span></div>
                <div class="d-flex justify-content-between fw-bold mt-2"><span>Total</span><span class="text-primary">Rp {{ number_format($order->total_amount,  0, ', ','.') }}</span></div>
                @if($order->status === 'pending')
                <hr>
                <a href="{{ route('payment.select', $order) }}" class="btn btn-primary w-100 rounded-pill fw-semibold mt-1">
                    <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
