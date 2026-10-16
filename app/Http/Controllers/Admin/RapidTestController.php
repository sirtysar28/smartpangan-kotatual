<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RapidTest;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class RapidTestController extends Controller
{
    /**
     * Daftar parameter yang pernah diinput admin (Revisi 15 Okt 2026)
     * — dipakai sebagai saran pilihan di form; admin juga bisa ketik parameter baru.
     */
    private function usedParameters(): array
    {
        return RapidTest::query()
            ->whereNotNull('parameter')
            ->where('parameter', '!=', 'manual')
            ->distinct()
            ->orderBy('parameter')
            ->pluck('parameter')
            ->map(fn ($p) => RapidTest::parameterLabel($p))
            ->unique()
            ->values()
            ->all();
    }

    public function create()
    {
        $usedParameters = $this->usedParameters();
        return view('admin.food-safety.rapid-test.create', compact('usedParameters'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['created_by'] = auth()->id();

        RapidTest::create($data);
        ActivityLogService::log('create', 'rapid_test', "Input uji rapid test: {$data['commodity_name']}");

        return redirect()->route('admin.food-safety.index', ['tab' => 'rapid'])
            ->with('success', 'Data uji rapid test tersimpan.');
    }

    public function edit(RapidTest $rapidTest)
    {
        $usedParameters = $this->usedParameters();
        return view('admin.food-safety.rapid-test.edit', compact('rapidTest', 'usedParameters'));
    }

    public function update(Request $request, RapidTest $rapidTest)
    {
        $data = $this->validateData($request);

        $rapidTest->update($data);
        ActivityLogService::log('update', 'rapid_test', "Update uji rapid test: {$data['commodity_name']}");

        return redirect()->route('admin.food-safety.index', ['tab' => 'rapid'])
            ->with('success', 'Data uji rapid test diperbarui.');
    }

    public function destroy(RapidTest $rapidTest)
    {
        $rapidTest->delete();
        ActivityLogService::log('delete', 'rapid_test', "Hapus uji rapid test: {$rapidTest->commodity_name}");
        return back()->with('success', 'Data uji rapid test dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'test_date' => 'required|date',
            'sample_location' => 'required|string|max:255',
            'commodity_name' => 'required|string|max:255',
            'commodity_type' => 'nullable|string|max:255',
            'rapid_test_type' => 'required|string|max:255',
            'parameter' => 'nullable|string|max:255',
            'result' => 'required|in:positif,negatif',
            'notes' => 'nullable|string',
        ]);
    }
}
