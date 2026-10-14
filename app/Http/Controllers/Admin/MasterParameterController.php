<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestParameterMaster;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

/**
 * Master Parameter Uji (Revisi 14 Okt 2026)
 * Data master parameter uji laboratorium yang terhubung ke form uji lab.
 * Menunggu kiriman data master resmi dari pihak ketahanan pangan;
 * kelola daftar parameter utama (±8) dari halaman ini.
 */
class MasterParameterController extends Controller
{
    public function index()
    {
        $parameters = TestParameterMaster::ordered()->get();
        return view('admin.master-parameters.index', compact('parameters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|in:mikotoksin,logam_berat,mikrobiologi,residu_pestisida,lainnya',
            'unit' => 'nullable|string|max:50',
            'standard' => 'nullable|string|max:100',
            'method' => 'nullable|string|max:255',
        ]);
        $data['category'] = in_array($data['category'] ?? null, ['', 'lainnya'], true) ? null : ($data['category'] ?? null);

        $master = TestParameterMaster::create($data + ['is_active' => true]);
        ActivityLogService::log('create', 'master_parameter', "Tambah master parameter uji: {$master->name}");

        return back()->with('success', "Parameter \"{$master->name}\" ditambahkan ke data master.");
    }

    public function update(Request $request, TestParameterMaster $masterParameter)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|in:mikotoksin,logam_berat,mikrobiologi,residu_pestisida,lainnya',
            'unit' => 'nullable|string|max:50',
            'standard' => 'nullable|string|max:100',
            'method' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $data['category'] = in_array($data['category'] ?? null, ['', 'lainnya'], true) ? null : ($data['category'] ?? null);
        $data['is_active'] = $data['is_active'] ?? false;

        $masterParameter->update($data);
        ActivityLogService::log('update', 'master_parameter', "Update master parameter uji: {$masterParameter->name}");

        return back()->with('success', 'Parameter master diperbarui.');
    }

    public function toggle(TestParameterMaster $masterParameter)
    {
        $masterParameter->update(['is_active' => !$masterParameter->is_active]);
        ActivityLogService::log('update', 'master_parameter', ($masterParameter->is_active ? 'Aktifkan' : 'Nonaktifkan') . " master parameter: {$masterParameter->name}");
        return back()->with('success', 'Status parameter diperbarui.');
    }

    public function destroy(TestParameterMaster $masterParameter)
    {
        $name = $masterParameter->name;
        $masterParameter->delete();
        ActivityLogService::log('delete', 'master_parameter', "Hapus master parameter uji: {$name}");
        return back()->with('success', 'Parameter master dihapus.');
    }
}
