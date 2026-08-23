<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['umkm','items'])->where('user_id',Auth::id())->latest()->paginate(15);
        return view('frontend.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ((int)$order->user_id !== (int)Auth::id()) abort(403);
        $order->load(['umkm','items.product','reviews']);
        return view('frontend.orders.show', compact('order'));
    }

    public function receive(Order $order)
    {
        if ((int)$order->user_id !== (int)Auth::id()) abort(403);
        if ($order->status !== 'shipped') return back()->with('error','Tidak dapat dikonfirmasi.');
        $order->update(['status'=>'delivered','delivered_at'=>now()]);
        ActivityLogService::log('receive', 'order', "Pembeli menerima pesanan {$order->order_number}", ['order_id' => $order->id]);
        return back()->with('success','Pesanan diterima.');
    }

    public function cancel(Order $order)
    {
        if ((int)$order->user_id !== (int)Auth::id()) abort(403);
        if (!in_array($order->status,['pending','paid'])) return back()->with('error','Tidak dapat dibatalkan.');
        foreach ($order->items as $item) Product::where('id',$item->product_id)->increment('stock',$item->quantity);
        $order->update(['status'=>'cancelled']);
        ActivityLogService::log('cancel', 'order', "Pembeli membatalkan pesanan {$order->order_number}", ['order_id' => $order->id]);
        return back()->with('success','Pesanan dibatalkan.');
    }

    public function review(Request $request, Order $order)
    {
        if ((int)$order->user_id !== (int)Auth::id()) abort(403);
        $request->validate(['product_id'=>'required|exists:products,id','rating'=>'required|integer|min:1|max:5','comment'=>'nullable|string']);
        Review::create(['user_id'=>Auth::id(),'product_id'=>$request->product_id,'order_id'=>$order->id,'rating'=>$request->rating,'comment'=>$request->comment]);
        $product = Product::find($request->product_id);
        $product->update(['rating'=>round($product->reviews()->visible()->avg('rating'),2)]);
        ActivityLogService::log('review', 'order', "Memberi ulasan {$request->rating} bintang untuk {$product->name}", ['order_id' => $order->id, 'product_id' => $request->product_id]);
        return back()->with('success','Ulasan ditambahkan.');
    }
}
