@extends('layouts.app')
@section('title', 'Keranjang - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h4>
    @if(empty($cart))
        <div class="text-center py-5"><i class="bi bi-cart-x display-1 text-muted"></i><p class="text-muted mt-3">Keranjang belanja kosong.</p><a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill">Mulai Belanja</a></div>
    @else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light"><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th></th></tr></thead>
            <tbody>
                @foreach($cart as $id => $item)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ (!empty($item['image']) && filter_var($item['image'], FILTER_VALIDATE_URL)) ? $item['image'] : (!empty($item['image']) ? Storage::url($item['image']) : 'https://placehold.co/60x60/eee/999?text=IMG') }}" width="60" height="60" class="rounded">
                            <div><strong>{{ $item['name'] }}</strong><br><small class="text-muted">{{ $item['umkm_name'] }}</small></div>
                        </div>
                    </td>
                    <td>Rp {{ number_format($item['price'],  0,  ', ', '.') }}</td>
                    <td>
                        <form action="{{ route('cart.update') }}" method="POST" class="d-flex gap-1">@csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}" class="form-control form-control-sm" style="width:70px">
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-clockwise"></i></button>
                        </form>
                    </td>
                    <td class="fw-bold">Rp {{ number_format($item['price']*$item['quantity'],  0,  ', ', '.') }}</td>
                    <td><form action="{{ route('cart.remove', $id) }}" method="POST">@csrf<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot><tr><td colspan="3" class="text-end fw-bold fs-5">Total</td><td class="fw-bold text-primary fs-5">Rp {{ number_format($total,  0,  ', ', '.') }}</td><td></td></tr></tfoot>
        </table>
    </div>
    <div class="d-flex justify-content-between">
        <form action="{{ route('cart.clear') }}" method="POST">@csrf<button class="btn btn-outline-danger rounded-pill"><i class="bi bi-trash me-1"></i>Kosongkan</button></form>
        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg rounded-pill fw-semibold"><i class="bi bi-bag-check me-2"></i>Checkout</a>
    </div>
    @endif
</div>
@endsection
