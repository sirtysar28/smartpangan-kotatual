@extends('layouts.app')
@section('title', 'Pesanan Saya - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-bag me-2"></i>Pesanan Saya</h4>
    @forelse($orders as $order)
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <div><strong>#{{ $order->order_number }}</strong> · {{ $order->created_at->format('d M Y H:i') }}</div>
            <span class="badge bg-{{ $order->statusBadge }} bg-opacity-25 text-{{ $order->statusBadge }}">{{ $order->statusText }}</span>
        </div>
        <div class="card-body">
            @foreach($order->items as $item)
            <div class="d-flex gap-2 mb-2">
                <img src="{{ $item->product_image ? Storage::url($item->product_image) : 'https://placehold.co/50x50/eee/999' }}" width="50" height="50" class="rounded">
                <div class="flex-grow-1"><small>{{ $item->product_name }} x{{ $item->quantity }}</small></div>
                <div class="text-end"><small class="fw-bold">Rp {{ number_format($item->subtotal,  0, ', ','.') }}</small></div>
            </div>
            @endforeach
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                <strong class="text-primary">Total: Rp {{ number_format($order->total_amount,  0, ', ','.') }}</strong>
                <div class="d-flex gap-2">
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary rounded-pill">Detail</a>
                    @if($order->status==='shipped')
                        <form action="{{ route('orders.receive', $order) }}" method="POST">@csrf<button class="btn btn-sm btn-success rounded-pill">Terima</button></form>
                    @endif
                    @if(in_array($order->status,['pending','paid']))
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onclick="return confirm('Batalkan pesanan?')">@csrf<button class="btn btn-sm btn-outline-danger rounded-pill">Batal</button></form>
                    @endif
                    @if($order->status==='pending')
                        <a href="{{ route('payment.select', $order) }}" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-credit-card me-1"></i>Bayar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5"><i class="bi bi-bag-x display-1 text-muted"></i><p class="text-muted mt-3">Belum ada pesanan.</p></div>
    @endforelse
    {{ $orders->links() }}
</div>
@endsection
