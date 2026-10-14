<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageGallery;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

/**
 * Tampilan Beranda (Revisi 14 Okt 2026)
 * Kelola konten visual beranda: foto struktur organisasi & galeri kegiatan.
 * Input dikelola di dashboard admin; tampilan depan menampilkan yang diperlukan saja.
 */
class HomepageController extends Controller
{
    public function index()
    {
        $strukturImage = Setting::get('homepage_struktur_organisasi');
        $galleries = HomepageGallery::ordered()->get();
        return view('admin.homepage.index', compact('strukturImage', 'galleries'));
    }

    /** Upload gambar struktur organisasi */
    public function updateStruktur(Request $request)
    {
        $request->validate([
            'struktur_organisasi' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $old = Setting::get('homepage_struktur_organisasi');
        if ($old) {
            \Storage::delete($old);
        }

        $path = $request->file('struktur_organisasi')->store('homepage', 'public');
        Setting::set('homepage_struktur_organisasi', $path);
        ActivityLogService::log('update', 'homepage', 'Upload gambar struktur organisasi beranda');

        return back()->with('success', 'Struktur organisasi diperbarui dan akan tampil di beranda.');
    }

    public function destroyStruktur()
    {
        $old = Setting::get('homepage_struktur_organisasi');
        if ($old) {
            \Storage::delete($old);
        }
        Setting::set('homepage_struktur_organisasi', '');
        ActivityLogService::log('delete', 'homepage', 'Hapus gambar struktur organisasi beranda');
        return back()->with('success', 'Gambar struktur organisasi dihapus.');
    }

    /** Tambah foto kegiatan */
    public function storeGallery(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'description' => 'nullable|string',
            'activity_date' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['image'] = $request->file('image')->store('homepage/galeri', 'public');
        $data['is_active'] = true;
        $gallery = HomepageGallery::create($data);

        ActivityLogService::log('create', 'homepage', "Tambah foto kegiatan beranda: {$gallery->title}");
        return back()->with('success', "Foto kegiatan \"{$gallery->title}\" ditambahkan.");
    }

    public function toggleGallery(HomepageGallery $gallery)
    {
        $gallery->update(['is_active' => !$gallery->is_active]);
        ActivityLogService::log('update', 'homepage', ($gallery->is_active ? 'Tampilkan' : 'Sembunyikan') . " foto kegiatan: {$gallery->title}");
        return back()->with('success', 'Status foto kegiatan diperbarui.');
    }

    public function destroyGallery(HomepageGallery $gallery)
    {
        if ($gallery->image) {
            \Storage::disk('public')->delete($gallery->image);
        }
        $title = $gallery->title;
        $gallery->delete();
        ActivityLogService::log('delete', 'homepage', "Hapus foto kegiatan beranda: {$title}");
        return back()->with('success', 'Foto kegiatan dihapus.');
    }
}
