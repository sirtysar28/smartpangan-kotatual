<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'umkm', 'items.product']);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('order_number', 'like', "%{$s}%")->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%")));
        }
        $orders = $query->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'umkm', 'items.product', 'reviews']);
        ActivityLogService::log('view', 'order', "Melihat detail pesanan: {$order->order_number}", ['order_id' => $order->id]);
        return view('admin.orders.show', compact('order'));
    }
}
