@extends('layouts.app')
@section('title', $product->name . ' - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb"><ol class="breadcrumb small"><li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li><li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li><li class="breadcrumb-item active">{{ $product->name }}</li></ol></nav>
    <div class="row g-4">
        <div class="col-md-5">
            <img src="{{ $product->image_url ?? 'https://placehold.co/600x400/1a5276/ffffff?text='.urlencode($product->name) }}" class="w-100 rounded-4 shadow" alt="{{ $product->name }}">
        </div>
        <div class="col-md-7">
            <span class="badge bg-primary">{{ $product->category->name }}</span>
            <h3 class="fw-bold mt-2">{{ $product->name }}</h3>
            <p class="text-muted">oleh <strong>{{ $product->umkm->business_name }}</strong> · <i class="bi bi-geo-alt"></i> {{ $product->umkm->city }}</p>
            <div class="mb-3">
                @if($product->discount_price)
                    <span class="text-danger fw-bold fs-3">Rp {{ number_format($product->discount_price,  0,  ', ', '.') }}</span>
                    <span class="price-original fs-5 ms-2">Rp {{ number_format($product->price,  0,  ', ', '.') }}</span>
                    <span class="badge bg-danger ms-2">-{{ $product->discount_percent }}%</span>
                @else
                    <span class="text-primary fw-bold fs-3">Rp {{ number_format($product->price,  0,  ', ', '.') }}</span>
                @endif
            </div>
            <div class="d-flex gap-3 mb-3">
                <span><i class="bi bi-star-fill text-warning"></i> {{ $product->rating }}</span>
                <span><i class="bi bi-bag-check"></i> Terjual {{ $product->total_sold }}</span>
                <span><i class="bi bi-eye"></i> {{ $product->total_views }}x dilihat</span>
            </div>
            <p>{{ $product->description }}</p>
            <p><strong>Stok:</strong> {{ $product->stock }} {{ $product->unit }} · <strong>Berat:</strong> {{ $product->weight }}g</p>
            @auth
            <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-2 align-items-center">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width:80px">
                <button type="submit" class="btn btn-warning fw-semibold flex-grow-1" {{ $product->stock<=0?'disabled':'' }}>
                    <i class="bi bi-cart-plus me-2"></i>{{ $product->stock<=0?'Habis':'Tambah ke Keranjang' }}
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary fw-semibold"><i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Belanja</a>
            @endauth
        </div>
    </div>
    @if($related->count())
    <hr class="my-5">
    <h5 class="fw-bold mb-3">Produk Serupa</h5>
    <div class="row g-3">
        @foreach($related as $r)
        <div class="col-6 col-md-3">
            <div class="card card-product h-100">
                <img src="{{ $r->image_url ?? 'https://placehold.co/400x300/1a5276/ffffff?text='.urlencode($r->name) }}" class="product-img card-img-top" loading="lazy">
                <div class="card-body p-2">
                    <h6 class="small fw-bold">{{ Str::limit($r->name,30) }}</h6>
                    <div class="text-primary fw-bold small">Rp {{ number_format($r->effective_price,  0, ', ','.') }}</div>
                    <a href="{{ route('products.show',$r->slug) }}" class="btn btn-sm btn-outline-primary w-100 mt-1 rounded-pill">Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
