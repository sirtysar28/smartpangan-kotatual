@extends('layouts.app')
@section('title', 'Pembayaran Gagal - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-5 text-center">
    <div class="card border-0 shadow rounded-4 p-5 mx-auto" style="max-width:500px">
        <i class="bi bi-x-circle text-danger display-1"></i>
        <h3 class="fw-bold mt-3">Pembayaran Gagal</h3>
        <p class="text-muted">Terjadi kesalahan. Silakan coba lagi.</p>
        <a href="{{ route('orders.index') }}" class="btn btn-primary rounded-pill mt-3">Lihat Pesanan</a>
    </div>
</div>
@endsection
