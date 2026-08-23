@extends('layouts.app')
@section('title', 'Transfer Bank - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-bank me-2"></i>Pembayaran Transfer Bank</h4>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-8">
            {{-- Bank Account Details --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-3">
                <h6 class="fw-bold mb-3">Transfer ke Rekening Berikut:</h6>

                <div class="border rounded-3 p-3 mb-3 bg-light">
                    <div class="row align-items-center">
                        <div class="col-2 text-center">
                            <div class="bg-white rounded-3 p-2 shadow-sm">
                                <small class="fw-bold text-primary d-block">BCA</small>
                            </div>
                        </div>
                        <div class="col-10">
                            <p class="fw-bold mb-0 fs-5" id="account-number">1234567890</p>
                            <p class="text-muted small mb-0">a.n. <strong>CV Cipta Karya Digital Indonesia</strong></p>
                        </div>
                        <div class="col-12 mt-2 text-end">
                            <button onclick="copyAccount()" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-clipboard me-1"></i>Salin Nomor Rekening
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border rounded-3 p-3 mb-3 bg-light">
                    <div class="row align-items-center">
                        <div class="col-2 text-center">
                            <div class="bg-white rounded-3 p-2 shadow-sm">
                                <small class="fw-bold text-success d-block">BRI</small>
                            </div>
                        </div>
                        <div class="col-10">
                            <p class="fw-bold mb-0 fs-5" id="account-number-bri">0987654321</p>
                            <p class="text-muted small mb-0">a.n. <strong>CV Cipta Karya Digital Indonesia</strong></p>
                        </div>
                        <div class="col-12 mt-2 text-end">
                            <button onclick="copyAccountBri()" class="btn btn-sm btn-outline-success rounded-pill">
                                <i class="bi bi-clipboard me-1"></i>Salin Nomor Rekening
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Details --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-3">
                <h6 class="fw-bold mb-3">Detail Pembayaran</h6>
                <div class="row mb-2">
                    <div class="col-4 text-muted">Nomor Pesanan</div>
                    <div class="col-8 fw-semibold">#{{ $order->order_number }}</div>
                </div>
                @foreach($order->items as $item)
                <div class="row mb-1 small">
                    <div class="col-8 text-muted">{{ $item->product_name }} x{{ $item->quantity }}</div>
                    <div class="col-4 text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
                <hr>
                <div class="row mb-1 small text-muted">
                    <div class="col-8">Subtotal</div>
                    <div class="col-4 text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                </div>
                <div class="row mb-1 small text-muted">
                    <div class="col-8">Ongkir ({{ strtoupper($order->shipping_courier ?? 'JNE') }})</div>
                    <div class="col-4 text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</div>
                </div>
                <hr class="mb-2">
                <div class="row">
                    <div class="col-8 fw-bold">Total Transfer</div>
                    <div class="col-4 text-end fw-bold text-primary fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 border-warning mb-3">
                <h6 class="fw-bold text-warning mb-3"><i class="bi bi-exclamation-triangle me-2"></i>Petunjuk Transfer</h6>
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="text-center p-2">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;"><span class="fw-bold text-warning">1</span></div>
                            <small class="d-block">Transfer sesuai <strong>total</strong> yang tertera</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-2">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;"><span class="fw-bold text-warning">2</span></div>
                            <small class="d-block">Bukti transfer akan <strong>diverifikasi manual</strong></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-2">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;"><span class="fw-bold text-warning">3</span></div>
                            <small class="d-block">Pesanan diproses setelah <strong>konfirmasi</strong></small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                Pesanan Anda sedang <strong>menunggu pembayaran</strong>. Silakan transfer sesuai nominal di atas. Pesanan akan diproses setelah admin memverifikasi pembayaran Anda.
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary rounded-pill">
                    <i class="bi bi-box me-1"></i>Lihat Pesanan
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="bi bi-list me-1"></i>Pesanan Saya
                </a>
                <a href="{{ route('payment.select', $order) }}" class="btn btn-outline-primary rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i>Ganti Metode
                </a>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function copyAccount() {
    const text = document.getElementById('account-number').innerText;
    navigator.clipboard.writeText(text).then(() => {
        showToast('Nomor rekening BCA berhasil disalin!');
    }).catch(() => {
        // Fallback for older browsers
        const el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        showToast('Nomor rekening BCA berhasil disalin!');
    });
}

function copyAccountBri() {
    const text = document.getElementById('account-number-bri').innerText;
    navigator.clipboard.writeText(text).then(() => {
        showToast('Nomor rekening BRI berhasil disalin!');
    }).catch(() => {
        const el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        showToast('Nomor rekening BRI berhasil disalin!');
    });
}

function showToast(msg) {
    let toast = document.createElement('div');
    toast.className = 'position-fixed bottom-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = '<div class="toast show align-items-center text-bg-success border-0 rounded-pill px-4 py-3 shadow" role="alert"><div class="d-flex"><div class="toast-body">' + msg + '</div></div></div>';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endsection
