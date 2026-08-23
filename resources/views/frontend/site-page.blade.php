@extends('layouts.app')
@section('title', $page->title . ' - Pangan Cerdas (Panca) Maryadat Kota Tual')

@section('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, {{ $page->color ?? '#6366f1' }}22, {{ $page->color ?? '#6366f1' }}08);
        padding: 2.5rem 0;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: {{ $page->color ?? '#6366f1' }}08;
    }
    .page-content {
        font-size: 0.95rem;
        line-height: 1.9;
        color: var(--text-muted);
    }
    .page-content p {
        margin-bottom: 1.2rem;
    }
    .page-nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 1rem;
        border-radius: var(--radius-xs);
        transition: all 0.2s;
        text-decoration: none;
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
    }
    .page-nav-item:hover {
        background: rgba(99,102,241,0.08);
        color: var(--primary);
    }
    .page-nav-item.active {
        background: rgba(99,102,241,0.12);
        color: var(--primary);
        font-weight: 600;
    }
    .page-nav-item .nav-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
        background: rgba(99,102,241,0.08);
        color: var(--primary);
    }
    .page-nav-item.active .nav-icon {
        background: var(--primary);
        color: #fff;
    }
    .page-image-cover {
        width: 100%;
        max-height: 350px;
        object-fit: cover;
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
    }
    .breadcrumb-custom {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        margin-bottom: 1rem;
    }
    .breadcrumb-custom a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }
    .breadcrumb-custom a:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="page-hero">
    <div class="container position-relative" style="z-index:2">
        <div class="breadcrumb-custom" data-animate>
            <a href="{{ route('home') }}"><i class="bi bi-house-door-fill"></i> Beranda</a>
            <i class="bi bi-chevron-right" style="font-size:0.65rem;color:var(--gray-light)"></i>
            <a href="{{ route('home') }}#profil">Profil</a>
            <i class="bi bi-chevron-right" style="font-size:0.65rem;color:var(--gray-light)"></i>
            <span style="color:var(--text-muted)">{{ $page->title }}</span>
        </div>

        <div class="row align-items-center g-4" data-animate>
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if($page->image)
                        <img src="{{ Storage::url($page->image) }}" alt="{{ $page->title }}" style="width:56px;height:56px;border-radius:16px;object-fit:cover;flex-shrink:0">
                    @else
                        <div style="width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;background:{{ ($page->color ?? 'var(--primary)') }}20;color:{{ $page->color ?? 'var(--primary)' }};flex-shrink:0">
                            <i class="bi bi-{{ $page->icon ?? 'info-circle' }}"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="fw-bold mb-0" style="font-size:1.6rem;line-height:1.2">{{ $page->title }}</h1>
                        <small style="color:var(--text-muted);font-size:0.78rem">Pangan Cerdas (Panca) Maryadat Kota Tual</small>
                    </div>
                </div>
            </div>
            @if($page->image)
            <div class="col-lg-5 text-center d-none d-lg-block">
                <img src="{{ Storage::url($page->image) }}" alt="{{ $page->title }}" class="page-image-cover" data-animate>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- PIMPINAN DAERAH (Wali Kota & Wakil Wali Kota) -->
@include('frontend.partials.leaders')

<!-- Content Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar Navigation -->
            @if($sitePages && $sitePages->count() > 1)
            <div class="col-lg-3" data-animate>
                <div class="card border-0 p-3" style="border-radius:var(--radius);box-shadow:var(--shadow-sm)">
                    <h6 class="fw-bold mb-3" style="font-size:0.85rem">
                        <i class="bi bi-list-ul me-1" style="color:var(--primary)"></i>Halaman Profil
                    </h6>
                    @foreach($sitePages as $sp)
                        <a href="{{ route('site.page', $sp->slug) }}" class="page-nav-item {{ $page->id === $sp->id ? 'active' : '' }}">
                            <span class="nav-icon" style="{{ $page->id === $sp->id ? '' : 'background:' . ($sp->color ?? 'var(--primary)') . '15;color:' . ($sp->color ?? 'var(--primary)') }}">
                                <i class="bi bi-{{ $sp->icon ?? 'file-earmark-text' }}"></i>
                            </span>
                            <span>{{ $sp->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Main Content -->
            <div class="{{ $sitePages && $sitePages->count() > 1 ? 'col-lg-9' : 'col-lg-12 mx-auto' }}" style="max-width:{{ $sitePages && $sitePages->count() > 1 ? '' : '900px' }}" data-animate>
                <div class="card border-0" style="border-radius:var(--radius);box-shadow:var(--shadow)">
                    <div class="card-body p-4">
                        @if($page->image && $sitePages && $sitePages->count() <= 1)
                            <div class="mb-4 d-block d-lg-none">
                                <img src="{{ Storage::url($page->image) }}" alt="{{ $page->title }}" class="page-image-cover">
                            </div>
                        @endif

                        <div class="page-content">
                            @if($page->content)
                                {!! nl2br(e($page->content)) !!}
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-file-earmark-text display-4 text-muted"></i>
                                    <p class="text-muted mt-2">Konten belum tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Other Pages Navigation -->
                @if($sitePages && $sitePages->count() > 1)
                <div class="d-flex justify-content-between mt-4">
                    @php
                        $currentIndex = $sitePages->search(fn($p) => $p->id === $page->id);
                        $prev = $currentIndex > 0 ? $sitePages[$currentIndex - 1] : null;
                        $next = $currentIndex < $sitePages->count() - 1 ? $sitePages[$currentIndex + 1] : null;
                    @endphp
                    @if($prev)
                    <a href="{{ route('site.page', $prev->slug) }}" class="d-flex align-items-center gap-2 text-decoration-none" style="color:var(--primary);font-size:0.85rem;font-weight:600">
                        <i class="bi bi-arrow-left"></i>
                        <span>{{ $prev->title }}</span>
                    </a>
                    @else
                    <div></div>
                    @endif
                    @if($next)
                    <a href="{{ route('site.page', $next->slug) }}" class="d-flex align-items-center gap-2 text-decoration-none" style="color:var(--primary);font-size:0.85rem;font-weight:600">
                        <span>{{ $next->title }}</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
