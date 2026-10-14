{{-- Form bersama Uji Laboratorium (create & edit) --}}
@php
    $editMode = isset($labTest) && $labTest->exists;
    $oldParams = old('parameters');
    $prefill = $editMode && !$oldParams ? $labTest->parameters->groupBy('test_category') : collect();
@endphp

<form action="{{ $editMode ? route('admin.food-safety.lab.update', $labTest) : route('admin.food-safety.lab.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($editMode) @method('PUT') @endif

    <h6 class="fw-bold mb-3"><i class="bi bi-flask me-2" style="color:var(--primary)"></i>Data Uji Lab</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Tanggal Pengujian *</label><input type="date" name="test_date" class="form-control" required value="{{ old('test_date', $editMode ? $labTest->test_date->format('Y-m-d') : today()->format('Y-m-d')) }}"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Lokasi Sampel *</label><input type="text" name="sample_location" class="form-control" required placeholder="Contoh: Pasar Tual, Kec. Dullah Selatan" value="{{ old('sample_location', $editMode ? $labTest->sample_location : '') }}"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Lab Uji *</label><input type="text" name="lab_name" class="form-control" required placeholder="Contoh: Balai Besar Laboratorium Kesehatan Makassar" value="{{ old('lab_name', $editMode ? $labTest->lab_name : '') }}"></div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Nama Komoditas *</label><input type="text" name="commodity_name" class="form-control" required placeholder="Contoh: Ikan Tuna, Cabai Rawit, Beras" value="{{ old('commodity_name', $editMode ? $labTest->commodity_name : '') }}"></div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Jenis PSAT *</label>
                <select name="psat_type" class="form-select" required>
                    <option value="">-- Pilih Jenis PSAT --</option>
                    @foreach(\App\Models\LabTest::PSAT_TYPES as $value => $label)
                    <option value="{{ $value }}" {{ old('psat_type', $editMode ? $labTest->psat_type : '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3"><label class="form-label">Metode Uji (Umum)</label><input type="text" name="method" class="form-control" placeholder="Contoh: SNI 01-2894-1992" value="{{ old('method', $editMode ? $labTest->method : '') }}"></div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">File Sertifikat CoA (PDF)</label>
                <input type="file" name="certificate_file" class="form-control" accept="application/pdf">
                @if($editMode && $labTest->certificate_file)
                    <small class="text-muted"><i class="bi bi-file-earmark-pdf me-1" style="color:#ef4444"></i>Sudah ada: <a href="{{ $labTest->certificate_file_url }}" target="_blank">lihat sertifikat saat ini</a> (upload baru untuk mengganti)</small>
                @else
                    <small class="text-muted">Upload sertifikat Certificate of Analysis dari lab (maks 10MB)</small>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3"><label class="form-label">Catatan</label><input type="text" name="notes" class="form-control" placeholder="Catatan tambahan..." value="{{ old('notes', $editMode ? $labTest->notes : '') }}"></div>
        </div>
    </div>

    <hr class="my-4">
    <h6 class="fw-bold mb-1"><i class="bi bi-clipboard2-data me-2" style="color:var(--primary)"></i>Data Hasil Uji</h6>
    <p class="text-muted small mb-3">Isi parameter yang diuji. Kolom <strong>Nilai</strong> diisi melalui popup. Parameter bersifat <strong>fleksibel</strong> — bisa dipilih dari data master, diisi manual, atau <strong>di kosongkan</strong> untuk jenis uji tanpa parameter (mis. boraks).</p>

    {{-- Revisi 14 Okt 2026: Pemilih parameter dari Data Master (terhubung menu Master Parameter) --}}
    @php $masterParams = \App\Models\TestParameterMaster::active()->ordered()->get(); @endphp
    @if($masterParams->count() > 0)
    <div class="card border-0 mb-3" style="border-radius:var(--radius);box-shadow:var(--shadow-sm);background:linear-gradient(135deg,rgba(99,102,241,0.04),rgba(14,165,233,0.03))">
        <div class="p-3">
            <label class="form-label small fw-semibold mb-2" style="color:var(--primary)"><i class="bi bi-database-fill-check me-1"></i>Pilih Cepat dari Data Master Parameter ({{ $masterParams->count() }} parameter aktif)</label>
            <div class="row g-2">
                <div class="col-md-9">
                    <select id="masterParamSelect" class="form-select form-select-sm">
                        <option value="">-- Pilih parameter dari data master --</option>
                        @foreach($masterParams as $mp)
                        <option value="{{ $mp->id }}"
                                data-name="{{ $mp->name }}"
                                data-category="{{ $mp->category ?? '' }}"
                                data-unit="{{ $mp->unit }}"
                                data-standard="{{ $mp->standard }}"
                                data-method="{{ $mp->method }}">{{ $mp->name }}@if($mp->category) — {{ $mp->category_label }}@endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" id="masterParamAdd" class="btn btn-sm w-100" style="background:var(--primary);color:#fff;border-radius:8px;font-weight:600"><i class="bi bi-plus-lg me-1"></i>Tambah ke Form</button>
                </div>
            </div>
            <small class="text-muted">Parameter terpilih otomatis masuk ke kelompok uji yang sesuai. Kelola daftar master di menu <strong>Master Parameter</strong>.</small>
        </div>
    </div>
    @endif

    @foreach(\App\Models\LabTestParameter::CATEGORIES as $catKey => $cat)
    <div class="card border-0 mb-3" style="border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden">
        <div class="p-3 d-flex justify-content-between align-items-center" style="background:{{ $cat['color'] }}0d;border-bottom:1px solid {{ $cat['color'] }}22">
            <h6 class="fw-bold mb-0" style="font-size:0.88rem;color:{{ $cat['color'] }}">
                <i class="bi {{ $cat['icon'] }} me-2"></i>{{ $cat['label'] }}
            </h6>
            <button type="button" class="btn btn-sm add-param-btn" data-cat="{{ $catKey }}" style="background:{{ $cat['color'] }}1a;color:{{ $cat['color'] }};border-radius:8px;font-weight:600;font-size:0.72rem">
                <i class="bi bi-plus-lg me-1"></i>Tambah Parameter
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-modern param-table mb-0" data-cat="{{ $catKey }}" style="font-size:0.72rem">
                <thead>
                    <tr style="background:rgba(148,163,184,0.04)">
                        <th style="min-width:170px">Parameter</th>
                        <th style="min-width:130px">Hasil Uji</th>
                        <th style="min-width:110px">Nilai</th>
                        <th style="min-width:80px">Satuan</th>
                        <th style="min-width:110px">Standar</th>
                        <th style="min-width:90px">LOD</th>
                        <th style="min-width:90px">LOQ</th>
                        <th style="min-width:120px">Status</th>
                        <th style="min-width:120px">Metode Uji</th>
                        <th style="width:40px"></th>
                    </tr>
                </thead>
                <tbody class="param-tbody" data-cat="{{ $catKey }}"></tbody>
            </table>
        </div>
    </div>
    @endforeach

    {{-- Revisi 14 Okt 2026: Grup parameter tanpa kelompok (kategori kosong — mis. boraks, formalin) --}}
    <div class="card border-0 mb-3" style="border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden">
        <div class="p-3 d-flex justify-content-between align-items-center" style="background:rgba(100,116,139,0.06);border-bottom:1px solid rgba(100,116,139,0.15)">
            <h6 class="fw-bold mb-0" style="font-size:0.88rem;color:#64748b">
                <i class="bi bi-clipboard2-plus me-2"></i>Parameter Lainnya / Tanpa Kelompok <span class="text-muted fw-normal" style="font-size:0.72rem">(opsional — mis. boraks, formalin)</span>
            </h6>
            <button type="button" class="btn btn-sm add-param-btn" data-cat="" style="background:rgba(100,116,139,0.12);color:#64748b;border-radius:8px;font-weight:600;font-size:0.72rem">
                <i class="bi bi-plus-lg me-1"></i>Tambah Parameter
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-modern param-table mb-0" data-cat="" style="font-size:0.72rem">
                <thead>
                    <tr style="background:rgba(148,163,184,0.04)">
                        <th style="min-width:170px">Parameter</th>
                        <th style="min-width:130px">Hasil Uji</th>
                        <th style="min-width:110px">Nilai</th>
                        <th style="min-width:80px">Satuan</th>
                        <th style="min-width:110px">Standar</th>
                        <th style="min-width:90px">LOD</th>
                        <th style="min-width:90px">LOQ</th>
                        <th style="min-width:120px">Status</th>
                        <th style="min-width:120px">Metode Uji</th>
                        <th style="width:40px"></th>
                    </tr>
                </thead>
                <tbody class="param-tbody" data-cat=""></tbody>
            </table>
        </div>
    </div>

    <hr class="my-3">
    <button class="btn btn-primary rounded-pill"><i class="bi bi-save me-1"></i>{{ $editMode ? 'Simpan Perubahan' : 'Simpan Hasil Uji Lab' }}</button>
    <a href="{{ route('admin.food-safety.index', ['tab' => 'lab']) }}" class="btn btn-outline-secondary rounded-pill ms-2">Batal</a>
</form>

{{-- Popup isi Nilai --}}
<div class="modal fade" id="valuePopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:16px;overflow:hidden">
            <div class="modal-header py-2" style="background:linear-gradient(135deg,#0c4a6e,#0ea5e9);color:#fff">
                <h6 class="modal-title fw-bold" style="font-size:0.88rem"><i class="bi bi-pencil-square me-1"></i>Isi Nilai</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <label class="form-label small fw-semibold" id="valuePopupLabel">Nilai Hasil Uji</label>
                <input type="text" class="form-control" id="valuePopupInput" placeholder="Contoh: 0.85">
                <div class="d-flex flex-wrap gap-1 mt-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill quick-value-btn" data-value="Tidak Terdeteksi" style="font-size:0.7rem">Tidak Terdeteksi</button>
                    <button type="button" class="btn btn-outline-warning btn-sm rounded-pill quick-value-btn" data-value="<LOD" style="font-size:0.7rem">&lt;LOD</button>
                    <button type="button" class="btn btn-outline-info btn-sm rounded-pill quick-value-btn" data-value="<LOQ" style="font-size:0.7rem">&lt;LOQ</button>
                </div>
                <small class="text-muted d-block mt-2">Isi angka hasil pengukuran, contoh: <code>0.85</code>, <code>1.2 x 10²</code>, atau pilih tombol cepat di atas.</small>
            </div>
            <div class="modal-footer py-2 p-3">
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-4" id="valuePopupSave"><i class="bi bi-check-lg me-1"></i>Simpan Nilai</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JS not loaded');
        return;
    }

    var rowIdx = 0;
    var presets = {!! json_encode(\App\Models\LabTestParameter::PRESETS) !!};
    var catInfo = {!! json_encode(\App\Models\LabTestParameter::CATEGORIES) !!};

    function opt(val, label, cur) { return '<option value="'+val+'"'+(cur===val?' selected':'')+'>'+label+'</option>'; }
    function esc(s) { return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    function addParamRow(cat, data) {
        data = data || {};
        var tbody = document.querySelector('.param-tbody[data-cat="'+cat+'"]');
        if (!tbody) return;
        var i = rowIdx++;
        var dlId = 'dl-' + cat + '-' + i;
        var presetOpts = (presets[cat] || []).map(function(p){ return '<option value="'+esc(p)+'">'; }).join('');
        var color = (catInfo[cat] || {}).color || '#6366f1';

        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td><input type="hidden" name="parameters['+i+'][test_category]" value="'+cat+'">' +
            '<input list="'+dlId+'" type="text" name="parameters['+i+'][parameter]" class="form-control form-control-sm" placeholder="Nama parameter..." value="'+esc(data.parameter)+'" style="font-size:0.72rem">' +
            '<datalist id="'+dlId+'">'+presetOpts+'</datalist></td>' +
            '<td><select name="parameters['+i+'][result]" class="form-select form-select-sm" style="font-size:0.72rem">' +
                opt('tidak_terdeteksi','Tidak Terdeteksi', data.result || 'tidak_terdeteksi') +
                opt('terdeteksi','Terdeteksi', data.result) +
            '</select></td>' +
            '<td class="text-nowrap">' +
                '<input type="hidden" name="parameters['+i+'][value]" value="'+esc(data.value)+'" class="value-input">' +
                '<button type="button" class="btn btn-sm value-btn" style="background:'+color+'14;color:'+color+';border-radius:6px;font-size:0.68rem;font-weight:600;min-width:78px"><i class="bi bi-pencil-square me-1"></i>'+(esc(data.value) || 'Isi Nilai')+'</button>' +
            '</td>' +
            '<td><input type="text" name="parameters['+i+'][unit]" class="form-control form-control-sm" placeholder="ppm" value="'+esc(data.unit)+'" style="font-size:0.72rem"></td>' +
            '<td><input type="text" name="parameters['+i+'][standard]" class="form-control form-control-sm" placeholder="Baku mutu" value="'+esc(data.standard)+'" style="font-size:0.72rem"></td>' +
            '<td><input type="text" name="parameters['+i+'][lod]" class="form-control form-control-sm" placeholder="LOD" value="'+esc(data.lod)+'" style="font-size:0.72rem"></td>' +
            '<td><input type="text" name="parameters['+i+'][loq]" class="form-control form-control-sm" placeholder="LOQ" value="'+esc(data.loq)+'" style="font-size:0.72rem"></td>' +
            '<td><select name="parameters['+i+'][status]" class="form-select form-select-sm" style="font-size:0.72rem">' +
                '<option value="">-</option>' +
                opt('MS','✅ MS', data.status) +
                opt('TMS','❌ TMS', data.status) +
            '</select></td>' +
            '<td><input type="text" name="parameters['+i+'][method]" class="form-control form-control-sm" placeholder="Metode" value="'+esc(data.method)+'" style="font-size:0.72rem"></td>' +
            '<td><button type="button" class="btn btn-sm remove-row-btn" title="Hapus baris" style="background:rgba(239,68,68,0.1);color:#ef4444;border-radius:6px;font-size:0.68rem"><i class="bi bi-x-lg"></i></button></td>';
        tbody.appendChild(tr);

        tr.querySelector('.remove-row-btn').addEventListener('click', function() { tr.remove(); });
        tr.querySelector('.value-btn').addEventListener('click', function() {
            openValuePopup(tr.querySelector('.value-input'), this);
        });
        return tr;
    }

    // Tombol tambah parameter
    document.querySelectorAll('.add-param-btn').forEach(function(btn) {
        btn.addEventListener('click', function() { addParamRow(this.dataset.cat); });
    });

    // Revisi 14 Okt 2026: Pilih cepat dari data master parameter
    var masterAddBtn = document.getElementById('masterParamAdd');
    var masterSelect = document.getElementById('masterParamSelect');
    if (masterAddBtn && masterSelect) {
        masterAddBtn.addEventListener('click', function() {
            var opt = masterSelect.options[masterSelect.selectedIndex];
            if (!opt || !opt.value) { masterSelect.focus(); return; }
            var tr = addParamRow(opt.dataset.category || '', {
                parameter: opt.dataset.name,
                unit: opt.dataset.unit,
                standard: opt.dataset.standard,
                method: opt.dataset.method
            });
            if (tr) {
                tr.querySelector('input[name$="[parameter]"]').readOnly = true;
                tr.style.background = 'rgba(99,102,241,0.04)';
            }
            masterSelect.selectedIndex = 0;
        });
    }

    // ==== POPUP NILAI ====
    var popupEl = document.getElementById('valuePopup');
    var popupInput = document.getElementById('valuePopupInput');
    var popupLabel = document.getElementById('valuePopupLabel');
    var activeValueInput = null;
    var activeValueBtn = null;
    var popup = new bootstrap.Modal(popupEl);

    function openValuePopup(input, btn) {
        activeValueInput = input;
        activeValueBtn = btn;
        var row = input.closest('tr');
        var paramInput = row ? row.querySelector('input[name$="[parameter]"]') : null;
        popupLabel.textContent = 'Nilai Hasil Uji' + (paramInput && paramInput.value ? ' — ' + paramInput.value : '');
        popupInput.value = input.value || '';
        popup.show();
        setTimeout(function(){ popupInput.focus(); }, 300);
    }

    // ==== QUICK VALUE BUTTONS (Tidak Terdeteksi, <LOD, <LOQ) ====
    document.querySelectorAll('.quick-value-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            popupInput.value = this.dataset.value;
            popupInput.focus();
        });
    });

    document.getElementById('valuePopupSave').addEventListener('click', function() {
        if (activeValueInput) {
            activeValueInput.value = popupInput.value;
            activeValueBtn.innerHTML = '<i class="bi bi-pencil-square me-1"></i>' + (popupInput.value ? esc(popupInput.value) : 'Isi Nilai');
        }
        popup.hide();
    });
    popupInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); document.getElementById('valuePopupSave').click(); } });

    // ==== PREFILL (edit mode / old input) ====
    var prefillData = {!! $oldParams ? json_encode($oldParams) : ($editMode ? json_encode($labTest->parameters->map(fn($p) => ['test_category' => $p->test_category, 'parameter' => $p->parameter, 'result' => $p->result, 'value' => $p->value, 'unit' => $p->unit, 'standard' => $p->standard, 'lod' => $p->lod, 'loq' => $p->loq, 'status' => $p->status, 'method' => $p->method])->values()->all()) : 'null') !!};
    if (prefillData) {
        prefillData.forEach(function(row) {
            if (row && row.parameter) { addParamRow(row.test_category || '', row); }
        });
    }
})();
</script>
@endpush