<?php

namespace App\Http\Controllers\UmkmPanel;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $umkm = Auth::user()->umkm;
        if (!$umkm || !$umkm->isVerified()) return view('umkm.pending', compact('umkm'));

        $totalProducts = $umkm->products()->active()->count();
        $totalOrders = $umkm->orders()->count();
        $pendingOrders = $umkm->orders()->where('status','paid')->count();
        $todayRevenue = $umkm->orders()->where('status','!=','cancelled')->whereDate('created_at',today())->sum('total_amount');
        $monthRevenue = $umkm->orders()->where('status','!=','cancelled')->whereMonth('created_at',now()->month)->whereYear('created_at',now()->year)->sum('total_amount');
        $recentOrders = $umkm->orders()->with(['user','items'])->latest()->take(10)->get();
        $lowStock = $umkm->products()->active()->where('stock','<=',20)->orderBy('stock')->take(5)->get();
        $topProducts = $umkm->products()->active()->popular()->take(5)->get();

        return view('umkm.dashboard', compact('umkm','totalProducts','totalOrders','pendingOrders','todayRevenue','monthRevenue','recentOrders','lowStock','topProducts'));
    }
}
