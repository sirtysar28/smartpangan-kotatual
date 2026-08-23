@extends('layouts.admin')
@section('title', 'Edit FAQ - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1" style="font-size:1.35rem"><i class="bi bi-pencil-square me-2" style="color:#8b5cf6"></i>Edit FAQ</h4>
        <p class="text-muted mb-0 small">Perbarui konten FAQ</p>
    </div>
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 animate-fade-up" style="border-radius:var(--radius);box-shadow:var(--shadow)">
    <div class="p-4">
        <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="nib" @selected($faq->category==='nib')>NIB (Nomor Induk Berusaha)</option>
                        <option value="psat_pduk" @selected($faq->category==='psat_pduk')>PSAT-PDUK</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Urutan</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $faq->sort_order) }}" min="0">
                    <small class="text-muted">Angka kecil tampil lebih dulu</small>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                    <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question) }}" required maxlength="500">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Jawaban <span class="text-danger">*</span></label>
                    <textarea name="answer" class="form-control" rows="3" required maxlength="5000">{{ old('answer', $faq->answer) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Poin-poin (opsional)</label>
                    <textarea name="items" class="form-control" rows="5" placeholder="Satu poin per baris">{{ old('items', $faq->items ? implode("\n", $faq->items) : '') }}</textarea>
                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Satu poin per baris — tampil sebagai bullet list di frontend.</small>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" @checked(old('is_active', $faq->is_active))>
                        <label class="form-check-label fw-semibold" for="is_active">Tampilkan FAQ ini (Aktif)</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary-modern"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
