@extends('layouts.app')
@section('title', 'Produk UMKM - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-search me-2"></i>Cari Produk UMKM</h4>
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <h6 class="fw-bold">Filter</h6>
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="mb-3"><label class="form-label small">Cari</label><input type="text" name="search" class="form-control" value="{{ request('search') }}"></div>
                    <div class="mb-3"><label class="form-label small">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua</option>
                            @foreach($categories as $c)<option value="{{ $c->id }}" {{ request('category')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label small">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="newest" {{ request('sort')=='newest'?'selected':'' }}>Terbaru</option>
                            <option value="popular" {{ request('sort')=='popular'?'selected':'' }}>Terlaris</option>
                            <option value="price_low" {{ request('sort')=='price_low'?'selected':'' }}>Harga Terendah</option>
                            <option value="price_high" {{ request('sort')=='price_high'?'selected':'' }}>Harga Tertinggi</option>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100 rounded-pill"><i class="bi bi-funnel me-1"></i>Terapkan</button>
                </form>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="row g-3">
                @forelse($products as $p)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card card-product h-100">
                        @if($p->discount_price)<span class="badge bg-danger badge-discount">-{{ $p->discount_percent }}%</span>@endif
                        <img src="{{ $p->image_url ?? 'https://placehold.co/400x300/1a5276/ffffff?text='.urlencode($p->name) }}" class="product-img card-img-top" loading="lazy">
                        <div class="card-body p-2">
                            <small class="text-muted">{{ $p->umkm->business_name ?? '' }}</small>
                            <h6 class="fw-bold small mb-1">{{ Str::limit($p->name, 30) }}</h6>
                            <div class="text-primary fw-bold small">Rp {{ number_format($p->effective_price,  0,  ', ', '.') }}</div>
                            <a href="{{ route('products.show', $p->slug) }}" class="btn btn-sm btn-outline-primary w-100 mt-2 rounded-pill">Detail</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5"><i class="bi bi-inbox display-1 text-muted"></i><p class="text-muted mt-3">Produk tidak ditemukan.</p></div>
                @endforelse
            </div>
            <div class="mt-4">{{ $products->withQueryString()->links() }}</div>
        </div>
    </div>
</div>
@endsection
