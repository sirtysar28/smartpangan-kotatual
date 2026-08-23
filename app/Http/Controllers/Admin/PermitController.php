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
        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('business_name', 'like', $s)
                  ->orWhere('applicant_name', 'like', $s)
                  ->orWhere('permit_number', 'like', $s);
            });
        }

        $permits = $query->latest()->paginate(15);

        $stats = [
            'total' => Permit::count(),
            'pending' => Permit::where('permit_status', 'pending')->count(),
            'approved' => Permit::where('permit_status', 'approved')->count(),
            'rejected' => Permit::where('permit_status', 'rejected')->count(),
        ];

        return view('admin.permits.index', compact('permits', 'stats'));
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
            'business_category' => 'nullable|string|max:255',
            'food_products' => 'nullable|string',
            'permit_type' => 'required|in:pduk,bpom,halal,sp_irt,lainnya',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'notes' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('document_file')) {
            $data['document_file'] = $request->file('document_file')->store('permits', 'public');
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
            'business_category' => 'nullable|string|max:255',
            'food_products' => 'nullable|string',
            'permit_type' => 'required|in:pduk,bpom,halal,sp_irt,lainnya',
            'permit_status' => 'nullable|in:pending,approved,rejected,expired',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'notes' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('document_file')) {
            if ($permit->document_file) {
                \Storage::delete($permit->document_file);
            }
            $data['document_file'] = $request->file('document_file')->store('permits', 'public');
        }

        $permit->update($data);

        ActivityLogService::log('update', 'permit', "Update perizinan: {$permit->permit_number} — {$permit->business_name}", ['permit_id' => $permit->id]);

        return redirect()->route('admin.permits.index')->with('success', "Perizinan {$permit->permit_number} berhasil diperbarui.");
    }

    public function destroy(Permit $permit)
    {
        $name = $permit->permit_number;
        if ($permit->document_file) {
            \Storage::delete($permit->document_file);
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
