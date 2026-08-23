<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return view('frontend.cart.index', compact('cart','total'));
    }

    public function add(Request $request)
    {
        $request->validate(['product_id'=>'required|exists:products,id','quantity'=>'required|integer|min:1']);
        $product = Product::with('umkm')->findOrFail($request->product_id);
        if ($product->stock < $request->quantity) return back()->with('error','Stok tidak mencukupi.');

        $cart = Session::get('cart', []);
        $key = $product->id;
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                'product_id'=>$product->id, 'name'=>$product->name,
                'price'=>$product->discount_price ?? $product->price,
                'quantity'=>$request->quantity, 'image'=>$product->image,
                'umkm_id'=>$product->umkm_id, 'umkm_name'=>$product->umkm->business_name,
                'weight'=>$product->weight, 'stock'=>$product->stock,
            ];
        }
        if ($cart[$key]['quantity'] > $product->stock) return back()->with('error','Stok tidak mencukupi.');
        Session::put('cart', $cart);
        return back()->with('success','Ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $cart = Session::get('cart', []);
        $id = $request->product_id;
        if (isset($cart[$id])) { $cart[$id]['quantity'] = $request->quantity; Session::put('cart',$cart); }
        return back()->with('success','Keranjang diperbarui.');
    }

    public function remove($id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart',$cart);
        return back()->with('success','Produk dihapus.');
    }

    public function clear()
    {
        Session::forget('cart');
        return back()->with('success','Keranjang dikosongkan.');
    }
}
