@extends('layouts.admin')
@section('title', 'Input Uji Rapid Test - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-lightning-charge me-2"></i>Input Uji Rapid Test</h5>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:900px">
<form action="{{ route('admin.food-safety.rapid.store') }}" method="POST">@csrf
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Tanggal Hasil Pengujian *</label><input type="date" name="test_date" class="form-control" required value="{{ old('test_date', today()->format('Y-m-d')) }}"></div>
        </div>
        <div class="col-md-8">
            <div class="mb-3"><label class="form-label">Lokasi Sampel *</label><input type="text" name="sample_location" class="form-control" required placeholder="Contoh: Pasar Waisamu, Kel. Dullah Selatan" value="{{ old('sample_location') }}"></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Komoditas *</label><input type="text" name="commodity_name" class="form-control" required placeholder="Contoh: Ikan Tongkol, Tahu, Kerupuk" value="{{ old('commodity_name') }}"></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Jenis Komoditas</label><input type="text" name="commodity_type" class="form-control" placeholder="Contoh: Pangan Segar / Olahan" value="{{ old('commodity_type') }}"></div>
        </div>
    </div>

    <hr class="my-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-clipboard2-pulse me-2" style="color:var(--primary)"></i>Hasil Uji</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Jenis Rapid Test *</label>
                <select name="rapid_test_type" id="rapidTestType" class="form-select" required>
                    <option value="">-- Pilih Jenis Rapid Test --</option>
                    @foreach(\App\Models\RapidTest::TYPES as $value => $label)
                    <option value="{{ $value }}" {{ old('rapid_test_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Parameter Uji</label>
                {{-- Revisi 15 Okt 2026: pilih parameter yang sudah ada ATAU ketik parameter baru sendiri --}}
                <input type="text" name="parameter" id="rapidTestParameter" class="form-control" list="rapidTestParameterOptions"
                       placeholder="Pilih / ketik parameter (opsional)..." autocomplete="off" value="{{ old('parameter') }}">
                <datalist id="rapidTestParameterOptions"></datalist>
                <div class="form-text">Klik untuk memilih parameter yang sudah ada, atau ketik parameter baru</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Hasil Uji *</label>
                <select name="result" class="form-select" required>
                    <option value="negatif" {{ old('result') === 'negatif' ? 'selected' : '' }}>✅ Negatif (Aman)</option>
                    <option value="positif" {{ old('result') === 'positif' ? 'selected' : '' }}>❌ Positif (Terkontaminasi)</option>
                </select>
            </div>
        </div>
    </div>
    <div class="mb-3"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="3" placeholder="Catatan tambahan hasil pengujian...">{{ old('notes') }}</textarea></div>

    <hr class="my-3">
    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>Simpan Hasil Uji Rapid Test</button>
    <a href="{{ route('admin.food-safety.index', ['tab' => 'rapid']) }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>
</div>

<script>
(function () {
    // Revisi 15 Okt 2026: parameter = pilih yang sudah ada + boleh ketik baru (combobox)
    var paramsByType = {!! json_encode(collect(\App\Models\RapidTest::PARAMETERS)->map(fn ($p) => array_values($p))->all()) !!};
    var usedParams = {!! json_encode($usedParameters ?? []) !!};
    var typeSelect = document.getElementById('rapidTestType');
    var paramInput = document.getElementById('rapidTestParameter');
    var paramList = document.getElementById('rapidTestParameterOptions');

    function updateParameterOptions() {
        var suggestions = (paramsByType[typeSelect.value] || []).concat(usedParams);
        paramList.innerHTML = '';
        suggestions
            .filter(function (v, i, arr) { return v && arr.indexOf(v) === i; }) // unik
            .forEach(function (label) {
                var opt = document.createElement('option');
                opt.value = label;
                paramList.appendChild(opt);
            });
    }

    typeSelect.addEventListener('change', updateParameterOptions);
    updateParameterOptions();
})();
</script>
@endsection
