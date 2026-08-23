@extends('layouts.umkm')
@section('title', 'Verifikasi UMKM - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            @if($umkm && $umkm->status === 'pending')
                <div class="card border-0 shadow-sm rounded-4 p-5">
                    <div class="bg-warning bg-opacity-10 rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width:120px;height:120px">
                        <i class="bi bi-clock display-1 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mt-4">Menunggu Verifikasi</h3>
                    <p class="text-muted mt-3">Pendaftaran UMKM Anda <strong>{{ $umkm->business_name }}</strong> sedang menunggu verifikasi dari Pemerintah Daerah Kota Tual.</p>
                    <p class="text-muted small">Proses verifikasi biasanya memakan waktu 1-3 hari kerja.</p>
                    <div class="mt-4 p-3 bg-light rounded-3">
                        <div class="row text-start">
                            <div class="col-6"><small class="text-muted">Nama UMKM</small><p class="fw-bold mb-2">{{ $umkm->business_name }}</p></div>
                            <div class="col-6"><small class="text-muted">Status</small><p><span class="badge bg-warning text-dark">Pending</span></p></div>
                            <div class="col-6"><small class="text-muted">Telepon</small><p class="fw-bold mb-2">{{ $umkm->phone }}</p></div>
                            <div class="col-6"><small class="text-muted">Alamat</small><p class="small mb-2">{{ Str::limit($umkm->address, 40) }}</p></div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill me-2"><i class="bi bi-house me-1"></i>Ke Marketplace</a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf<button class="btn btn-outline-danger rounded-pill"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button></form>
                    </div>
                </div>
            @elseif($umkm && $umkm->status === 'rejected')
                <div class="card border-0 shadow-sm rounded-4 p-5">
                    <div class="bg-danger bg-opacity-10 rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width:120px;height:120px">
                        <i class="bi bi-x-circle display-1 text-danger"></i>
                    </div>
                    <h3 class="fw-bold mt-4 text-danger">Verifikasi Ditolak</h3>
                    <p class="text-muted mt-3">Pendaftaran UMKM <strong>{{ $umkm->business_name }}</strong> ditolak.</p>
                    @if($umkm->rejection_reason)
                    <div class="alert alert-danger text-start mt-3">
                        <strong>Alasan:</strong> {{ $umkm->rejection_reason }}
                    </div>
                    @endif
                    <p class="text-muted small">Silakan hubungi admin atau daftar ulang dengan data yang benar.</p>
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill me-2"><i class="bi bi-house me-1"></i>Ke Marketplace</a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf<button class="btn btn-outline-danger rounded-pill"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button></form>
                    </div>
                </div>
            @elseif($umkm && $umkm->status === 'suspended')
                <div class="card border-0 shadow-sm rounded-4 p-5">
                    <div class="bg-secondary bg-opacity-10 rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width:120px;height:120px">
                        <i class="bi bi-slash-circle display-1 text-secondary"></i>
                    </div>
                    <h3 class="fw-bold mt-4">Akun Ditangguhkan</h3>
                    <p class="text-muted mt-3">UMKM <strong>{{ $umkm->business_name }}</strong> telah ditangguhkan oleh admin.</p>
                    <p class="text-muted small">Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill me-2"><i class="bi bi-house me-1"></i>Ke Marketplace</a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf<button class="btn btn-outline-danger rounded-pill"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button></form>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5">
                    <div class="bg-info bg-opacity-10 rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width:120px;height:120px">
                        <i class="bi bi-shop display-1 text-info"></i>
                    </div>
                    <h3 class="fw-bold mt-4">Daftar UMKM</h3>
                    <p class="text-muted mt-3">Anda belum terdaftar sebagai UMKM.</p>
                    <a href="{{ route('umkm.register') }}" class="btn btn-primary btn-lg rounded-pill mt-2"><i class="bi bi-shop me-2"></i>Daftar Sekarang</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
