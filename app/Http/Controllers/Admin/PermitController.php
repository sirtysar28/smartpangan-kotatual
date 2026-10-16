<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use App\Models\Umkm;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class PermitController extends Controller
{
    public function index(Request $request)
    {
        $query = Permit::with(['umkm', 'approver']);

        if ($request->filled('status')) {
            $query->where('permit_status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('permit_type', $request->type);
        }
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }
        if ($request->filled('desa')) {
            $query->where('desa', $request->desa);
        }
        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('business_name', 'like', $s)
                  ->orWhere('applicant_name', 'like', $s)
                  ->orWhere('nib', 'like', $s)
                  ->orWhere('permit_number', 'like', $s);
            });
        }

        $permits = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Permit::count(),
            'pending' => Permit::where('permit_status', 'pending')->count(),
            'approved' => Permit::where('permit_status', 'approved')->count(),
            'rejected' => Permit::where('permit_status', 'rejected')->count(),
        ];

        // ===== Data grafik interaktif (revisi 15 Okt 2026) =====
        $wilayah = \App\Support\TualWilayah::kecamatan();

        // Status perizinan ("expired" = approved yang sudah lewat tanggal kadaluarsa)
        $expiredCount = Permit::where('permit_status', 'approved')
            ->whereNotNull('expiry_date')->where('expiry_date', '<', today())->count();
        $chartStatus = [
            'approved' => max(0, ($stats['approved'] ?? 0) - $expiredCount),
            'pending'  => (int) $stats['pending'],
            'rejected' => (int) $stats['rejected'],
            'expired'  => $expiredCount + Permit::where('permit_status', 'expired')->count(),
        ];

        // Sebaran per kecamatan (urut sesuai wilayah resmi + kecamatan di luar daftar bila ada)
        $kecRaw = Permit::select('kecamatan', \DB::raw('COUNT(*) AS total'))
            ->whereNotNull('kecamatan')->groupBy('kecamatan')->pluck('total', 'kecamatan');
        $chartKecamatan = collect(array_keys($wilayah))
            ->map(fn ($kec) => ['label' => $kec, 'total' => (int) ($kecRaw[$kec] ?? 0)]);
        $kecRaw->each(function ($total, $kec) use (&$chartKecamatan, $wilayah) {
            if ($kec && !isset($wilayah[$kec])) {
                $chartKecamatan->push(['label' => $kec, 'total' => (int) $total]);
            }
        });

        // Jenis perizinan
        $typeRaw = Permit::select('permit_type', \DB::raw('COUNT(*) AS total'))
            ->groupBy('permit_type')->pluck('total', 'permit_type');
        $chartTypes = collect([
            'pduk' => 'PDUK', 'bpom' => 'BPOM', 'halal' => 'Halal', 'sp_irt' => 'SP-IRT', 'lainnya' => 'Lainnya',
        ])->map(fn ($label, $key) => ['label' => $label, 'total' => (int) ($typeRaw[$key] ?? 0)])->values();

        // Tren data masuk 6 bulan terakhir (pakai issue_date, fallback created_at)
        $trendRaw = Permit::query()
            ->selectRaw('YEAR(COALESCE(issue_date, created_at)) AS y, MONTH(COALESCE(issue_date, created_at)) AS m, COUNT(*) AS total')
            ->whereRaw('COALESCE(issue_date, created_at) >= ?', [now()->subMonths(5)->startOfMonth()])
            ->groupBy('y', 'm')->get();
        $chartTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i)->startOfMonth();
            $row = $trendRaw->where('y', $d->year)->where('m', $d->month)->first();
            $chartTrend[] = [
                'label' => $d->locale('id')->translatedFormat('M Y'),
                'total' => (int) ($row->total ?? 0),
            ];
        }

        return view('admin.permits.index', compact(
            'permits', 'stats', 'chartStatus', 'chartKecamatan', 'chartTypes', 'chartTrend'
        ));
    }

    public function create()
    {
        $umkms = Umkm::where('status', 'verified')->orderBy('business_name')->get();
        return view('admin.permits.create', compact('umkms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'umkm_id' => 'nullable|exists:umkms,id',
            'business_name' => 'required|string|max:255',
            'applicant_name' => 'required|string|max:255',
            'applicant_phone' => 'nullable|string|max:20',
            'business_address' => 'nullable|string',
            'kecamatan' => 'nullable|string|max:100',
            'desa' => 'nullable|string|max:100',
            'business_category' => 'nullable|string|max:255',
            'food_products' => 'nullable|string',
            'nib' => 'nullable|string|max:100',
            'permit_type' => 'required|in:pduk,bpom,halal,sp_irt,lainnya',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'notes' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nib_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Isi otomatis dari data UMKM terkait bila kolom dikosongkan (revisi 14 Okt 2026)
        if (!empty($data['umkm_id'])) {
            $umkm = Umkm::with('user')->find($data['umkm_id']);
            if ($umkm) {
                $data['business_name'] = $data['business_name'] ?: $umkm->business_name;
                $data['applicant_name'] = $data['applicant_name'] ?: ($umkm->user?->name ?? '');
                $data['business_address'] = $data['business_address'] ?: $umkm->address;
                $data['nib'] = $data['nib'] ?: $umkm->nib;
            }
        }

        if ($request->hasFile('document_file')) {
            $data['document_file'] = $request->file('document_file')->store('permits', 'public');
        }
        if ($request->hasFile('nib_document')) {
            $data['nib_document'] = $request->file('nib_document')->store('permits', 'public');
        }

        // Auto-generate permit number
        $prefix = match($data['permit_type']) {
            'pduk' => 'PDUK',
            'bpom' => 'BPOM',
            'halal' => 'HALAL',
            'sp_irt' => 'SP-IRT',
            default => 'IZIN',
        };
        $count = Permit::where('permit_type', $data['permit_type'])->count() + 1;
        $data['permit_number'] = $prefix . '-' . date('Ym') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $permit = Permit::create($data);

        ActivityLogService::log('create', 'permit', "Menambah perizinan: {$permit->permit_number} — {$permit->business_name}", ['permit_id' => $permit->id]);

        return redirect()->route('admin.permits.index')->with('success', "Perizinan {$permit->permit_number} berhasil ditambahkan.");
    }

    public function edit(Permit $permit)
    {
        $umkms = Umkm::where('status', 'verified')->orderBy('business_name')->get();
        return view('admin.permits.edit', compact('permit', 'umkms'));
    }

    public function update(Request $request, Permit $permit)
    {
        $data = $request->validate([
            'umkm_id' => 'nullable|exists:umkms,id',
            'business_name' => 'required|string|max:255',
            'applicant_name' => 'required|string|max:255',
            'applicant_phone' => 'nullable|string|max:20',
            'business_address' => 'nullable|string',
            'kecamatan' => 'nullable|string|max:100',
            'desa' => 'nullable|string|max:100',
            'business_category' => 'nullable|string|max:255',
            'food_products' => 'nullable|string',
            'nib' => 'nullable|string|max:100',
            'permit_type' => 'required|in:pduk,bpom,halal,sp_irt,lainnya',
            'permit_status' => 'nullable|in:pending,approved,rejected,expired',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'notes' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nib_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('document_file')) {
            if ($permit->document_file) {
                \Storage::disk('public')->delete($permit->document_file);
            }
            $data['document_file'] = $request->file('document_file')->store('permits', 'public');
        }
        if ($request->hasFile('nib_document')) {
            if ($permit->nib_document) {
                \Storage::disk('public')->delete($permit->nib_document);
            }
            $data['nib_document'] = $request->file('nib_document')->store('permits', 'public');
        }

        $permit->update($data);

        ActivityLogService::log('update', 'permit', "Update perizinan: {$permit->permit_number} — {$permit->business_name}", ['permit_id' => $permit->id]);

        return redirect()->route('admin.permits.index')->with('success', "Perizinan {$permit->permit_number} berhasil diperbarui.");
    }

    /**
     * Upload dokumen NIB langsung dari daftar (tombol di kolom Aksi).
     */
    public function uploadNib(Request $request, Permit $permit)
    {
        $data = $request->validate([
            'nib_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($permit->nib_document) {
            \Storage::disk('public')->delete($permit->nib_document);
        }
        $permit->update(['nib_document' => $request->file('nib_document')->store('permits', 'public')]);

        ActivityLogService::log('update', 'permit', "Upload dokumen NIB: {$permit->permit_number} — {$permit->business_name}", ['permit_id' => $permit->id]);

        return back()->with('success', "Dokumen NIB untuk {$permit->business_name} berhasil diupload.");
    }

    public function destroy(Permit $permit)
    {
        $name = $permit->permit_number;
        if ($permit->document_file) {
            \Storage::disk('public')->delete($permit->document_file);
        }
        if ($permit->nib_document) {
            \Storage::disk('public')->delete($permit->nib_document);
        }
        $permit->delete();

        ActivityLogService::log('delete', 'permit', "Menghapus perizinan: {$name}", ['permit_id' => $permit->id]);

        return back()->with('success', "Perizinan {$name} berhasil dihapus.");
    }

    public function approve(Permit $permit)
    {
        $permit->update([
            'permit_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        ActivityLogService::log('approve', 'permit', "Menyetujui perizinan: {$permit->permit_number}", ['permit_id' => $permit->id]);

        return back()->with('success', "Perizinan {$permit->permit_number} telah disetujui.");
    }

    public function reject(Request $request, Permit $permit)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $permit->update([
            'permit_status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        ActivityLogService::log('reject', 'permit', "Menolak perizinan: {$permit->permit_number} — {$request->rejection_reason}", ['permit_id' => $permit->id]);

        return back()->with('success', "Perizinan {$permit->permit_number} telah ditolak.");
    }
}
