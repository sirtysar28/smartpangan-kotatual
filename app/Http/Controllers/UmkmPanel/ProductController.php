<?php

namespace App\Http\Controllers\UmkmPanel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $umkm = Auth::user()->umkm;
        $query = $umkm->products()->with('category');
        if ($request->filled('search')) $query->where('name','like',"%{$request->search}%");
        $products = $query->latest()->paginate(15);
        $categories = Category::active()->get();
        return view('umkm.products.index', compact('products','categories'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('umkm.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:1',
            'unit' => 'required|string',
            'image' => 'required|image|max:2048',
        ]);
        $data['umkm_id'] = Auth::user()->umkm->id;
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('products','public');
        $product = Product::create($data);
        ActivityLogService::log('create', 'product', "UMKM menambah produk: {$product->name} (Rp " . number_format($product->price, 0, ',', '.') . ")", ['product_id' => $product->id]);
        return redirect()->route('umkm.products.index')->with('success','Produk ditambahkan.');
    }

    public function edit(Product $product)
    {
        if ($product->umkm_id !== Auth::user()->umkm->id) abort(403);
        $categories = Category::active()->get();
        return view('umkm.products.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->umkm_id !== Auth::user()->umkm->id) abort(403);
        $data = $request->validate([
            'name' => 'required','category_id' => 'required','description' => 'required',
            'price' => 'required|numeric','stock' => 'required|integer','weight' => 'required|integer',
        ]);
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('products','public');
        $product->update($data);
        ActivityLogService::log('update', 'product', "UMKM mengupdate produk: {$product->name}", ['product_id' => $product->id]);
        return redirect()->route('umkm.products.index')->with('success','Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->umkm_id !== Auth::user()->umkm->id) abort(403);
        $name = $product->name;
        $product->delete();
        ActivityLogService::log('delete', 'product', "UMKM menghapus produk: {$name}", ['deleted_product_id' => $product->id]);
        return back()->with('success','Produk dihapus.');
    }
}
