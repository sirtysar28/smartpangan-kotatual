@extends('layouts.app')
@section('title', 'Checkout - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-bag-check me-2"></i>Checkout</h4>
    <form action="{{ route('checkout.process') }}" method="POST">@csrf
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 mb-3 p-3">
                <h6 class="fw-bold">Alamat Pengiriman</h6>
                <textarea name="shipping_address" class="form-control" rows="3" required placeholder="Masukkan alamat lengkap...">{{ auth()->user()->address }}</textarea>
                <div class="row mt-3">
                    <div class="col-6"><label class="form-label small">No. HP</label><input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}" required></div>
                    <div class="col-6"><label class="form-label small">Catatan</label><input type="text" name="notes" class="form-control" placeholder="Opsional"></div>
                </div>
            </div>
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <h6 class="fw-bold">Pilih Kurir</h6>
                <div class="row g-2">
                    @foreach(['jne'=>'JNE - Rp 15.000','jnt'=>'J&T - Rp 12.000','sicepat'=>'SiCepat - Rp 10.000','pos'=>'Pos Indonesia - Rp 8.000'] as $k=>$v)
                    <div class="col-6"><label class="form-check-label border rounded-3 p-2 w-100"><input type="radio" name="shipping_courier" value="{{ $k }}" {{ $loop->first?'checked':'' }} class="me-2">{{ $v }}</label></div>
                    @endforeach
                </div>
                <input type="hidden" name="shipping_service" value="REG">
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <h6 class="fw-bold">Ringkasan Pesanan</h6>
                @php $grandTotal = 0; @endphp
                @foreach($cart as $item)
                @php $grandTotal += $item['price']*$item['quantity']; @endphp
                <div class="d-flex justify-content-between small mb-2">
                    <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                    <span>Rp {{ number_format($item['price']*$item['quantity'],  0, ', ','.') }}</span>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total</span><span class="text-primary">Rp {{ number_format($grandTotal+15000,  0, ', ','.') }}</span>
                </div>
                <small class="text-muted">*Termasuk ongkir</small>
                <button type="submit" class="btn btn-primary btn-lg w-100 mt-3 rounded-pill fw-semibold">
                    <i class="bi bi-lock me-2"></i>Buat Pesanan
                </button>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection
