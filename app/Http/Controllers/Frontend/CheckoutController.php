<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index')->with('error','Keranjang kosong.');
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return view('frontend.checkout.index', compact('cart','total'));
    }

    public function process(Request $request)
    {
        $request->validate(['shipping_address'=>'required','shipping_courier'=>'required','phone'=>'required']);
        $cart = Session::get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        $grouped = collect($cart)->groupBy('umkm_id');
        $orders = [];

        DB::beginTransaction();
        try {
            foreach ($grouped as $umkmId => $items) {
                $subtotal = $items->sum(fn($i) => $i['price'] * $i['quantity']);
                $shipping = ['jne'=>15000,'jnt'=>12000,'sicepat'=>10000,'pos'=>8000][$request->shipping_courier] ?? 15000;
                $order = Order::create([
                    'order_number'=>Order::generateOrderNumber(), 'user_id'=>Auth::id(), 'umkm_id'=>$umkmId,
                    'subtotal'=>$subtotal, 'shipping_cost'=>$shipping, 'total_amount'=>$subtotal+$shipping,
                    'shipping_address'=>$request->shipping_address, 'shipping_courier'=>$request->shipping_courier,
                    'shipping_service'=>$request->shipping_service??'REG', 'expired_at'=>now()->addHours(24),
                ]);
                foreach ($items as $item) {
                    OrderItem::create(['order_id'=>$order->id,'product_id'=>$item['product_id'],'product_name'=>$item['name'],'product_image'=>$item['image'],'quantity'=>$item['quantity'],'price'=>$item['price'],'subtotal'=>$item['price']*$item['quantity']]);
                    Product::where('id',$item['product_id'])->decrement('stock',$item['quantity']);
                }
                $orders[] = $order;
            }
            DB::commit();
            Session::forget('cart');
            ActivityLogService::log('checkout', 'order', "Checkout berhasil: " . count($orders) . " pesanan dibuat", ['order_ids' => collect($orders)->pluck('id')->toArray()]);
            if (count($orders)===1) return redirect()->route('payment.select',$orders[0]->id);
            return redirect()->route('orders.index')->with('success','Pesanan dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error','Error: '.$e->getMessage());
        }
    }
}
