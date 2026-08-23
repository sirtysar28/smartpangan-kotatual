<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create() { return view('admin.categories.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);
        $data['slug'] = Str::slug($request->name);
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('categories', 'public');
        $category = Category::create($data);
        ActivityLogService::log('create', 'category', "Menambah kategori: {$category->name}", ['category_id' => $category->id]);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(Category $category) { return view('admin.categories.edit', compact('category')); }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'description' => 'nullable|string', 'is_active' => 'boolean']);
        $data['slug'] = Str::slug($request->name);
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('categories', 'public');
        $category->update($data);
        ActivityLogService::log('update', 'category', "Mengupdate kategori: {$category->name}", ['category_id' => $category->id]);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        $name = $category->name;
        $category->delete();
        ActivityLogService::log('delete', 'category', "Menghapus kategori: {$name}", ['deleted_category_id' => $category->id]);
        return back()->with('success', 'Kategori dihapus.');
    }
}
