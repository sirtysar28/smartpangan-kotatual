<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SitePage;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SitePageController extends Controller
{
    public function index()
    {
        $pages = SitePage::ordered()->get();
        return view('admin.site-pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.site-pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:site_pages,slug',
            'content' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $slug = $request->slug ?: Str::slug($request->title);

        // Handle image upload
        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('site-pages', 'public');
        }

        $maxSort = SitePage::max('sort_order') ?? 0;

        SitePage::create([
            'slug' => $slug,
            'title' => $request->title,
            'content' => $request->content,
            'icon' => $request->icon,
            'image' => $image,
            'color' => $request->color ?? '#6366f1',
            'is_active' => $request->has('is_active'),
            'sort_order' => $maxSort + 1,
        ]);

        ActivityLogService::log('create', 'page', "Membuat halaman baru: {$request->title}");

        return redirect()->route('admin.site-pages.index')->with('success', "Halaman \"{$request->title}\" berhasil dibuat.");
    }

    public function edit(SitePage $sitePage)
    {
        return view('admin.site-pages.edit', compact('sitePage'));
    }

    public function update(Request $request, SitePage $sitePage)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:site_pages,slug,' . $sitePage->id,
            'content' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $slug = $request->slug ?: Str::slug($request->title);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('site-pages', 'public');
            $sitePage->image = $image;
        }

        $sitePage->update([
            'slug' => $slug,
            'title' => $request->title,
            'content' => $request->content,
            'icon' => $request->icon,
            'color' => $request->color ?? $sitePage->color,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? $sitePage->sort_order,
        ]);

        ActivityLogService::log('update', 'page', "Mengupdate halaman: {$sitePage->title}", ['page_id' => $sitePage->id]);

        return back()->with('success', "Halaman \"{$sitePage->title}\" berhasil diperbarui.");
    }

    public function destroy(SitePage $sitePage)
    {
        $title = $sitePage->title;
        $sitePage->delete();

        ActivityLogService::log('delete', 'page', "Menghapus halaman: {$title}");

        return redirect()->route('admin.site-pages.index')->with('success', "Halaman \"{$title}\" berhasil dihapus.");
    }
}
