<?php

namespace App\Http\Controllers\UmkmPanel;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\UserNotification;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $umkm = Auth::user()->umkm;
        $query = $umkm->orders()->with(['user','items.product']);
        if ($request->filled('status')) $query->where('status',$request->status);
        $orders = $query->latest()->paginate(15);
        return view('umkm.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->umkm_id !== Auth::user()->umkm->id) abort(403);
        $order->load(['user','items.product']);
        return view('umkm.orders.show', compact('order'));
    }

    public function process(Order $order)
    {
        if ($order->umkm_id !== Auth::user()->umkm->id) abort(403);
        if ($order->status !== 'paid') return back()->with('error','Pesanan tidak dapat diproses.');
        $order->update(['status'=>'processing']);
        ActivityLogService::log('process', 'order', "UMKM memproses pesanan {$order->order_number}", ['order_id' => $order->id]);
        return back()->with('success','Pesanan sedang diproses.');
    }

    public function ship(Request $request, Order $order)
    {
        if ($order->umkm_id !== Auth::user()->umkm->id) abort(403);
        $request->validate(['tracking_number'=>'required']);
        $order->update(['status'=>'shipped','tracking_number'=>$request->tracking_number,'shipped_at'=>now()]);
        UserNotification::create(['user_id'=>$order->user_id,'title'=>'Pesanan Dikirim','message'=>"Pesanan #{$order->order_number} dikirim. No. Resi: {$request->tracking_number}",'type'=>'success']);
        ActivityLogService::log('ship', 'order', "UMKM mengirim pesanan {$order->order_number}. Resi: {$request->tracking_number}", ['order_id' => $order->id]);
        return back()->with('success','Pesanan dikirim.');
    }
}
