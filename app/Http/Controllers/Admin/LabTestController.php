<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\LabTestParameter;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabTestController extends Controller
{
    public function create()
    {
        return view('admin.food-safety.lab-test.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('certificate_file')) {
            $data['certificate_file'] = $request->file('certificate_file')->store('food-safety/lab-certificates', 'public');
        }

        DB::transaction(function () use ($data, $request) {
            $labTest = LabTest::create($data);
            $this->saveParameters($labTest, $request);
            ActivityLogService::log('create', 'lab_test', "Input uji laboratorium: {$data['commodity_name']}");
        });

        return redirect()->route('admin.food-safety.index', ['tab' => 'lab'])
            ->with('success', 'Data uji laboratorium tersimpan.');
    }

    public function edit(LabTest $labTest)
    {
        $labTest->load('parameters');
        return view('admin.food-safety.lab-test.edit', compact('labTest'));
    }

    public function update(Request $request, LabTest $labTest)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('certificate_file')) {
            if ($labTest->certificate_file) {
                \Storage::disk('public')->delete($labTest->certificate_file);
            }
            $data['certificate_file'] = $request->file('certificate_file')->store('food-safety/lab-certificates', 'public');
        }

        DB::transaction(function () use ($labTest, $data, $request) {
            $labTest->update($data);
            $labTest->parameters()->delete();
            $this->saveParameters($labTest, $request);
            ActivityLogService::log('update', 'lab_test', "Update uji laboratorium: {$data['commodity_name']}");
        });

        return redirect()->route('admin.food-safety.index', ['tab' => 'lab'])
            ->with('success', 'Data uji laboratorium diperbarui.');
    }

    public function destroy(LabTest $labTest)
    {
        if ($labTest->certificate_file) {
            \Storage::disk('public')->delete($labTest->certificate_file);
        }
        $labTest->delete();
        ActivityLogService::log('delete', 'lab_test', "Hapus uji laboratorium: {$labTest->commodity_name}");
        return back()->with('success', 'Data uji laboratorium dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'test_date' => 'required|date',
            'sample_location' => 'required|string|max:255',
            'lab_name' => 'required|string|max:255',
            'commodity_name' => 'required|string|max:255',
            'psat_type' => 'required|string|max:255',
            'method' => 'nullable|string|max:255',
            'certificate_file' => 'nullable|file|mimes:pdf|max:10240',
            'notes' => 'nullable|string',
            'parameters' => 'nullable|array',
            'parameters.*.test_category' => 'required_with:parameters|in:mikotoksin,logam_berat,mikrobiologi,residu_pestisida',
            'parameters.*.parameter' => 'required_with:parameters|string|max:255',
            'parameters.*.result' => 'nullable|in:terdeteksi,tidak_terdeteksi',
            'parameters.*.value' => 'nullable|string|max:100',
            'parameters.*.unit' => 'nullable|string|max:50',
            'parameters.*.standard' => 'nullable|string|max:100',
            'parameters.*.lod' => 'nullable|string|max:100',
            'parameters.*.loq' => 'nullable|string|max:100',
            'parameters.*.status' => 'nullable|in:MS,TMS',
            'parameters.*.method' => 'nullable|string|max:255',
        ]);
    }

    private function saveParameters(LabTest $labTest, Request $request): void
    {
        foreach ((array) $request->input('parameters', []) as $row) {
            if (empty($row['parameter'])) {
                continue;
            }
            $labTest->parameters()->create([
                'test_category' => $row['test_category'],
                'parameter' => $row['parameter'],
                'result' => $row['result'] ?? 'tidak_terdeteksi',
                'value' => $row['value'] ?? null,
                'unit' => $row['unit'] ?? null,
                'standard' => $row['standard'] ?? null,
                'lod' => $row['lod'] ?? null,
                'loq' => $row['loq'] ?? null,
                'status' => $row['status'] ?? null,
                'method' => $row['method'] ?? null,
            ]);
        }
    }
}
