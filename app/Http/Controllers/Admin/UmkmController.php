<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\UserNotification;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with('user');
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('business_name', 'like', "%{$s}%")->orWhere('nib', 'like', "%{$s}%"));
        }
        $umkms = $query->latest()->paginate(15);
        $stats = [
            'total' => Umkm::count(),
            'verified' => Umkm::verified()->count(),
            'pending' => Umkm::pending()->count(),
        ];
        return view('admin.umkms.index', compact('umkms', 'stats'));
    }

    public function show(Umkm $umkm)
    {
        $umkm->load('user', 'products.category');
        ActivityLogService::log('view', 'umkm', "Melihat detail UMKM: {$umkm->business_name}", ['umkm_id' => $umkm->id]);
        return view('admin.umkms.show', compact('umkm'));
    }

    public function verify(Umkm $umkm)
    {
        $umkm->update(['status' => 'verified', 'verified_at' => now()]);
        $umkm->user->update(['role' => 'umkm']);
        UserNotification::create([
            'user_id' => $umkm->user_id,
            'title' => 'UMKM Terverifikasi!',
            'message' => "Selamat! {$umkm->business_name} telah terverifikasi. Anda sudah bisa mulai berjualan.",
            'type' => 'success',
        ]);
        ActivityLogService::log('verify', 'umkm', "Memverifikasi UMKM: {$umkm->business_name}", ['umkm_id' => $umkm->id]);
        return back()->with('success', 'UMKM berhasil diverifikasi.');
    }

    public function reject(Request $request, Umkm $umkm)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $umkm->update(['status' => 'rejected', 'rejection_reason' => $request->rejection_reason]);
        UserNotification::create([
            'user_id' => $umkm->user_id,
            'title' => 'Verifikasi Ditolak',
            'message' => "Verifikasi {$umkm->business_name} ditolak. Alasan: {$request->rejection_reason}",
            'type' => 'danger',
        ]);
        ActivityLogService::log('reject', 'umkm', "Menolak UMKM: {$umkm->business_name}. Alasan: {$request->rejection_reason}", ['umkm_id' => $umkm->id]);
        return back()->with('success', 'UMKM berhasil ditolak.');
    }

    public function suspend(Umkm $umkm)
    {
        $umkm->update(['status' => 'suspended']);
        ActivityLogService::log('suspend', 'umkm', "Menangguhkan UMKM: {$umkm->business_name}", ['umkm_id' => $umkm->id]);
        return back()->with('success', 'UMKM ditangguhkan.');
    }

    public function destroy(Umkm $umkm)
    {
        $name = $umkm->business_name;
        $umkm->delete();
        ActivityLogService::log('delete', 'umkm', "Menghapus UMKM: {$name}", ['deleted_umkm_id' => $umkm->id]);
        return redirect()->route('admin.umkms.index')->with('success', 'UMKM dihapus.');
    }
}
