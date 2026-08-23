<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiInsight;
use App\Models\Product;
use App\Models\Order;
use App\Models\Umkm;
use App\Models\Commodity;
use App\Models\CommodityPrice;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;

class AiDashboardController extends Controller
{
    public function index()
    {
        $trendingProducts = Product::with(['umkm','category'])->active()->popular()->take(20)->get();
        $stockAlerts = Product::with('umkm')->active()->where('stock', '<=', 20)->orderBy('stock')->take(20)->get();
        $allInsights = AiInsight::active()->latest()->paginate(20);
        return view('admin.ai-dashboard.index', compact('trendingProducts', 'stockAlerts', 'allInsights'));
    }

    public function inflation()
    {
        $commodities = Commodity::with('latestPrice')->active()->pangan()->get();
        $priceIncreases = CommodityPrice::where('change_percent', '>', 0)->where('price_date', '>=', now()->subDays(7))->with('commodity')->orderBy('change_percent','desc')->get();
        $inflationPredictions = AiInsight::active()->where('type', 'inflation_prediction')->latest()->take(5)->get();
        return view('admin.ai-dashboard.inflation', compact('commodities', 'priceIncreases', 'inflationPredictions'));
    }

    public function economy()
    {
        $todayTx = Order::where('status','!=','cancelled')->whereDate('created_at', today())->sum('total_amount');
        $monthTx = Order::where('status','!=','cancelled')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_amount');
        $totalActiveUmkm = Umkm::verified()->count();
        $umkmTransacting = Umkm::whereHas('orders', fn($q) => $q->where('status','!=','cancelled')->whereMonth('created_at', now()->month))->count();

        $topCategories = DB::table('categories')
            ->select('categories.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('products', 'categories.id','=','products.category_id')
            ->join('order_items','products.id','=','order_items.product_id')
            ->join('orders','order_items.order_id','=','orders.id')
            ->where('orders.status','!=','cancelled')
            ->groupBy('categories.id','categories.name')->orderByDesc('total_sold')->take(10)->get();

        return view('admin.ai-dashboard.economy', compact('todayTx','monthTx','totalActiveUmkm','umkmTransacting','topCategories'));
    }

    public function generateInsights()
    {
        // Stock alerts
        Product::active()->where('stock', '<=', 20)->each(function ($p) {
            $days = $p->total_sold > 0 ? intval($p->stock / max(1, $p->total_sold / 30)) : 99;
            AiInsight::create([
                'type' => 'stock_alert',
                'title' => 'Stok Menipis!',
                'description' => "Stok {$p->name} ({$p->umkm->business_name}) tersisa {$p->stock} pcs. Prediksi habis: {$days} hari.",
                'data' => ['product_id' => $p->id, 'stock' => $p->stock, 'days_left' => $days],
                'confidence_score' => 85.5,
                'severity' => $p->stock <= 5 ? 'critical' : 'warning',
                'expires_at' => now()->addDays(7),
            ]);
        });

        // Trending
        Product::active()->popular()->take(5)->each(function ($p, $i) {
            AiInsight::create([
                'type' => 'trending_product',
                'title' => 'Produk Terlaris #'.($i+1),
                'description' => "{$p->name} terjual {$p->total_sold} unit.",
                'data' => ['product_id' => $p->id, 'total_sold' => $p->total_sold],
                'confidence_score' => 92.0,
                'severity' => 'info',
                'expires_at' => now()->addDays(3),
            ]);
        });

        ActivityLogService::log('generate', 'ai', 'Generate AI Insights baru (stok alert & trending products)');
        return redirect()->route('admin.ai-dashboard.index')->with('success', 'AI Insights berhasil di-generate.');
    }
}
