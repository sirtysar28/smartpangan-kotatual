<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $groups = Setting::orderBy('group')->orderBy('label')->get()->groupBy('group');

        return view('admin.settings.index', compact('groups'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        $changes = [];
        foreach ($request->settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                // Keep password fields masked if empty submitted
                if ($setting->type === 'password' && ($value === '' || $value === '••••••••••••••••')) {
                    continue;
                }
                $oldValue = $setting->value;
                $setting->value = $value;
                $setting->save();
                if ($oldValue !== $value) {
                    $changes[] = "{$setting->label}: diubah";
                }
            }
        }

        $desc = count($changes) > 0 ? implode('; ', $changes) : 'Tidak ada perubahan';
        ActivityLogService::log('update', 'settings', "Memperbarui pengaturan sistem. {$desc}");

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
