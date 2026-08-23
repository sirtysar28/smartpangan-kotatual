<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['umkm','category'])->active();
        if ($request->filled('search')) $query->search($request->search);
        if ($request->filled('category')) $query->where('category_id',$request->category);
        if ($request->filled('sort')) {
            match($request->sort) {
                'price_low' => $query->orderBy('price','asc'),
                'price_high' => $query->orderBy('price','desc'),
                'popular' => $query->popular(),
                'newest' => $query->latest(),
                default => $query->latest(),
            };
        } else $query->latest();
        $products = $query->paginate(20);
        $categories = Category::active()->withCount('products')->get();
        return view('frontend.products.index', compact('products','categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['umkm.user','category','images','reviews.user'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();
        $product->increment('total_views');
        $related = Product::active()->where('category_id',$product->category_id)->where('id','!=',$product->id)->inRandomOrder()->take(8)->get();
        return view('frontend.products.show', compact('product','related'));
    }
}
