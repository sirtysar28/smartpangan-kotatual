<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodSafety;
use App\Models\FoodSafetyActivity;
use App\Models\RapidTest;
use App\Models\LabTest;
use App\Models\Commodity;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class FoodSafetyController extends Controller
{
    // ==================== KEAMANAN PANGAN ====================

    public function index(Request $request)
    {
        $rapidTests = RapidTest::with('creator')
            ->when($request->rapid_search, fn($q, $s) => $q->where('commodity_name', 'like', "%{$s}%")
                ->orWhere('sample_location', 'like', "%{$s}%"))
            ->when($request->rapid_type, fn($q, $t) => $q->where('rapid_test_type', $t))
            ->latest('test_date')
            ->paginate(10, ['*'], 'rapid_page');

        $labTests = LabTest::with('parameters')->withCount('parameters')
            ->when($request->lab_search, fn($q, $s) => $q->where('commodity_name', 'like', "%{$s}%")
                ->orWhere('lab_name', 'like', "%{$s}%")
                ->orWhere('sample_location', 'like', "%{$s}%"))
            ->latest('test_date')
            ->paginate(10, ['*'], 'lab_page');

        $summary = [
            'total_rapid' => RapidTest::count(),
            'rapid_positif' => RapidTest::where('result', 'positif')->count(),
            'rapid_negatif' => RapidTest::where('result', 'negatif')->count(),
            'total_lab' => LabTest::count(),
            'lab_tms' => LabTest::whereHas('parameters', fn($q) => $q->where('status', 'TMS'))->count(),
        ];

        return view('admin.food-safety.index', compact('rapidTests', 'labTests', 'summary'));
    }

    public function createCheck()
    {
        $commodities = Commodity::active()->pangan()->orderBy('name')->pluck('name', 'id');
        return view('admin.food-safety.create-check', compact('commodities'));
    }

    public function storeCheck(Request $request)
    {
        $data = $request->validate([
            'check_date' => 'required|date',
            'food_source_type' => 'required|in:tumbuhan,ikan,hewan',
            'food_name' => 'required|string|max:255',
            'commodity_id' => 'nullable|exists:commodities,id',
            'distributor_name' => 'nullable|string|max:255',
            'retailer_name' => 'nullable|string|max:255',
            'retailer_address' => 'nullable|string|max:255',
            'food_condition' => 'required|in:segar,segar_olahan_awal',
            'packaging_type' => 'nullable|string|max:255',
            'packaging_netto' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
            'bpom_number' => 'nullable|string|max:255',
            'is_halal' => 'nullable|boolean',
            'halal_number' => 'nullable|string|max:255',
            'is_pduk' => 'nullable|boolean',
            'pduk_number' => 'nullable|string|max:255',
            'inspection_result' => 'required|in:layak,tidak_layak,perlu_tindak_lanjut',
            'recommendation' => 'nullable|string',
            'recommendation_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'inspection_type' => 'required|in:rutin,insidentil,follow_up',
            'inspector_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $data['is_halal'] = $data['is_halal'] ?? false;
        $data['is_pduk'] = $data['is_pduk'] ?? false;
        $data['created_by'] = auth()->id();

        if ($request->hasFile('recommendation_photo')) {
            $data['recommendation_photo'] = $request->file('recommendation_photo')->store('food-safety/recommendations', 'public');
        }

        FoodSafety::create($data);
        ActivityLogService::log('create', 'food_safety', "Pemeriksaan keamanan pangan: {$data['food_name']}");
        return redirect()->route('admin.food-safety.index')->with('success', 'Data pemeriksaan keamanan pangan tersimpan.');
    }

    public function editCheck(FoodSafety $foodSafety)
    {
        $commodities = Commodity::active()->pangan()->orderBy('name')->pluck('name', 'id');
        return view('admin.food-safety.edit-check', compact('commodities', 'foodSafety'));
    }

    public function updateCheck(Request $request, FoodSafety $foodSafety)
    {
        $data = $request->validate([
            'check_date' => 'required|date',
            'food_source_type' => 'required|in:tumbuhan,ikan,hewan',
            'food_name' => 'required|string|max:255',
            'commodity_id' => 'nullable|exists:commodities,id',
            'distributor_name' => 'nullable|string|max:255',
            'retailer_name' => 'nullable|string|max:255',
            'retailer_address' => 'nullable|string|max:255',
            'food_condition' => 'required|in:segar,segar_olahan_awal',
            'packaging_type' => 'nullable|string|max:255',
            'packaging_netto' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
            'bpom_number' => 'nullable|string|max:255',
            'is_halal' => 'nullable|boolean',
            'halal_number' => 'nullable|string|max:255',
            'is_pduk' => 'nullable|boolean',
            'pduk_number' => 'nullable|string|max:255',
            'inspection_result' => 'required|in:layak,tidak_layak,perlu_tindak_lanjut',
            'recommendation' => 'nullable|string',
            'recommendation_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'inspection_type' => 'required|in:rutin,insidentil,follow_up',
            'inspector_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $data['is_halal'] = $data['is_halal'] ?? false;
        $data['is_pduk'] = $data['is_pduk'] ?? false;

        if ($request->hasFile('recommendation_photo')) {
            if ($foodSafety->recommendation_photo) {
                \Storage::delete($foodSafety->recommendation_photo);
            }
            $data['recommendation_photo'] = $request->file('recommendation_photo')->store('food-safety/recommendations', 'public');
        }

        $foodSafety->update($data);
        ActivityLogService::log('update', 'food_safety', "Update pemeriksaan: {$data['food_name']}");
        return redirect()->route('admin.food-safety.index')->with('success', 'Data pemeriksaan diperbarui.');
    }

    public function destroyCheck(FoodSafety $foodSafety)
    {
        $foodSafety->delete();
        return back()->with('success', 'Data pemeriksaan keamanan pangan dihapus.');
    }

    // ==================== KEGIATAN KEAMANAN PANGAN ====================

    public function createActivity()
    {
        return view('admin.food-safety.create-activity');
    }

    public function storeActivity(Request $request)
    {
        $data = $request->validate([
            'activity_date' => 'required|date',
            'title' => 'required|string|max:255',
            'activity_type' => 'required|in:pengawasan,sosialisasi,pelatihan,pengambilan_sampel,penyuluhan,sidak,rapat_koordinasi,lainnya',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'participants_count' => 'nullable|integer|min:0',
            'evidence_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'evidence_photo_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'evidence_photo_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'outcome' => 'nullable|string',
            'status' => 'nullable|in:draft,published',
        ]);

        $data['participants_count'] = $data['participants_count'] ?? 0;
        $data['status'] = $data['status'] ?? 'published';
        $data['created_by'] = auth()->id();

        for ($i = 1; $i <= 3; $i++) {
            $key = 'evidence_photo' . ($i > 1 ? '_' . $i : '');
            if ($request->hasFile($key)) {
                $data[$key] = $request->file($key)->store('food-safety/activities', 'public');
            }
        }

        if ($request->hasFile('document_file')) {
            $data['document_file'] = $request->file('document_file')->store('food-safety/documents', 'public');
        }

        FoodSafetyActivity::create($data);
        ActivityLogService::log('create', 'food_safety_activity', "Kegiatan: {$data['title']}");
        return redirect()->route('admin.food-safety.index')->with('success', 'Kegiatan keamanan pangan tersimpan.');
    }

    public function editActivity(FoodSafetyActivity $activity)
    {
        return view('admin.food-safety.edit-activity', compact('activity'));
    }

    public function updateActivity(Request $request, FoodSafetyActivity $activity)
    {
        $data = $request->validate([
            'activity_date' => 'required|date',
            'title' => 'required|string|max:255',
            'activity_type' => 'required|in:pengawasan,sosialisasi,pelatihan,pengambilan_sampel,penyuluhan,sidak,rapat_koordinasi,lainnya',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'participants_count' => 'nullable|integer|min:0',
            'evidence_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'evidence_photo_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'evidence_photo_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'outcome' => 'nullable|string',
            'status' => 'nullable|in:draft,published',
        ]);

        $data['participants_count'] = $data['participants_count'] ?? 0;
        $data['status'] = $data['status'] ?? 'published';

        for ($i = 1; $i <= 3; $i++) {
            $key = 'evidence_photo' . ($i > 1 ? '_' . $i : '');
            if ($request->hasFile($key)) {
                if ($activity->$key) \Storage::delete($activity->$key);
                $data[$key] = $request->file($key)->store('food-safety/activities', 'public');
            }
        }

        if ($request->hasFile('document_file')) {
            if ($activity->document_file) \Storage::delete($activity->document_file);
            $data['document_file'] = $request->file('document_file')->store('food-safety/documents', 'public');
        }

        $activity->update($data);
        ActivityLogService::log('update', 'food_safety_activity', "Update kegiatan: {$data['title']}");
        return redirect()->route('admin.food-safety.index')->with('success', 'Kegiatan keamanan pangan diperbarui.');
    }

    public function destroyActivity(FoodSafetyActivity $activity)
    {
        $activity->delete();
        return back()->with('success', 'Kegiatan keamanan pangan dihapus.');
    }
}
