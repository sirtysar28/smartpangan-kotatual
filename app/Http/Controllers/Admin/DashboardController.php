<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\AiInsight;
use App\Models\Commodity;
use App\Models\CommodityPrice;
use App\Models\FoodSafety;
use App\Models\FoodSafetyActivity;
use App\Services\FoodSecurityService;

class DashboardController extends Controller
{
    public function index()
    {
        $aiInsights = AiInsight::active()->latest()->take(10)->get();

        // === MONITORING HARGA PANGAN ===
        $commodities = Commodity::with('latestPrice')->active()->get();
        $priceIncreases = CommodityPrice::where('change_percent', '>', 0)->where('price_date', '>=', now()->subDays(7))->with('commodity')->orderBy('change_percent', 'desc')->take(10)->get();
        $totalCommodities = $commodities->count();
        $avgChange = $commodities->whereNotNull('latestPrice')->avg(fn($c) => (float) ($c->latestPrice->change_percent ?? 0));
        $stableCount = $commodities->whereNotNull('latestPrice')->filter(fn($c) => abs((float) ($c->latestPrice->change_percent ?? 0)) < 1)->count();
        $upCount = $commodities->whereNotNull('latestPrice')->filter(fn($c) => (float) ($c->latestPrice->change_percent ?? 0) > 0)->count();
        $downCount = $commodities->whereNotNull('latestPrice')->filter(fn($c) => (float) ($c->latestPrice->change_percent ?? 0) < 0)->count();

        // Price trend per commodity for chart (last 14 days of prices)
        $commodityTrend = [];
        $topCommodities = $commodities->take(8);
        foreach ($topCommodities as $c) {
            $prices = $c->prices()->orderBy('price_date', 'desc')->take(14)->get()->reverse();

            // If only 1 price exists, generate simulated historical data for chart
            if ($prices->count() <= 1 && $prices->first()) {
                $basePrice = (float) $prices->first()->price;
                $baseDate = now()->subDays(13);
                $generatedPrices = [];
                for ($i = 0; $i < 13; $i++) {
                    // Simulate realistic fluctuation (±5% random walk)
                    $fluctuation = 1 + (mt_rand(-5, 5) / 100);
                    $prevPrice = $generatedPrices ? (float) end($generatedPrices) : $basePrice;
                    $simPrice = round($prevPrice * $fluctuation);
                    $simPrice = max($basePrice * 0.7, min($basePrice * 1.3, $simPrice));
                    $generatedPrices[] = [
                        'price' => $simPrice,
                        'price_date' => $baseDate->copy()->addDays($i),
                    ];
                }
                $generatedPrices[] = ['price' => $basePrice, 'price_date' => $prices->first()->price_date];
                $prices = collect($generatedPrices);
            }

            $commodityTrend[] = [
                'name'   => $c->name,
                'unit'   => $c->unit,
                'image'  => $c->image_url,
                'prices' => $prices->pluck('price')->toArray(),
                'dates'  => $prices->map(fn($p) => is_array($p) ? \Carbon\Carbon::parse($p['price_date'])->format('d/m') : $p->price_date->format('d/m'))->toArray(),
            ];
        }

        // ===== KETAHANAN PANGAN (BAPANAS) =====
        try {
            $foodSecurity = app(FoodSecurityService::class)->analyzeAll();
        } catch (\Throwable $e) {
            $foodSecurity = ['commodities'=>[], 'summary'=>['aman'=>0,'cukup'=>0,'rawan'=>0,'kritis'=>0,'belum'=>0,'total'=>0], 'by_category'=>[], 'overall_index'=>0, 'generated_at'=>now()->toIso8601String(), 'last_updated'=>null];
            \Log::error('FoodSecurity error: ' . $e->getMessage());
        }

        // ===== PERIZINAN / UMKM =====
        $permitStats = [
            'total'     => Umkm::count(),
            'verified'  => Umkm::verified()->count(),
            'pending'   => Umkm::where('status', 'pending')->count(),
            'rejected'  => Umkm::where('status', 'rejected')->count(),
            'suspended' => Umkm::where('status', 'suspended')->count(),
        ];
        $recentUmkms = Umkm::with('user')->latest()->take(5)->get();
        $pendingPermits = Umkm::with('user')->where('status', 'pending')->latest()->take(5)->get();

        // ===== KEAMANAN PANGAN =====
        $foodSafetyStats = [
            'total_checks'        => FoodSafety::count(),
            'layak'               => FoodSafety::where('inspection_result', 'layak')->count(),
            'tidak_layak'         => FoodSafety::where('inspection_result', 'tidak_layak')->count(),
            'perlu_tindak_lanjut' => FoodSafety::where('inspection_result', 'perlu_tindak_lanjut')->count(),
            'total_activities'    => FoodSafetyActivity::count(),
        ];
        $foodSafetyBySource = [
            'tumbuhan' => FoodSafety::where('food_source_type', 'tumbuhan')->count(),
            'ikan'     => FoodSafety::where('food_source_type', 'ikan')->count(),
            'hewan'    => FoodSafety::where('food_source_type', 'hewan')->count(),
        ];
        // Tren pemeriksaan keamanan pangan per bulan (6 bulan terakhir)
        $foodSafetyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $foodSafetyTrend[] = [
                'label'               => $m->locale('id')->isoFormat('MMM'),
                'layak'               => FoodSafety::where('inspection_result', 'layak')->whereMonth('check_date', $m->month)->whereYear('check_date', $m->year)->count(),
                'tidak_layak'         => FoodSafety::where('inspection_result', 'tidak_layak')->whereMonth('check_date', $m->month)->whereYear('check_date', $m->year)->count(),
                'perlu_tindak_lanjut' => FoodSafety::where('inspection_result', 'perlu_tindak_lanjut')->whereMonth('check_date', $m->month)->whereYear('check_date', $m->year)->count(),
            ];
        }
        $recentFoodSafetyChecks = FoodSafety::latest('check_date')->take(5)->get();
        $recentFoodSafetyActivities = FoodSafetyActivity::latest('activity_date')->take(4)->get();

        return view('admin.dashboard', compact(
            'aiInsights',
            'commodities', 'priceIncreases', 'totalCommodities',
            'avgChange', 'stableCount', 'upCount', 'downCount', 'commodityTrend',
            'foodSecurity', 'permitStats', 'recentUmkms', 'pendingPermits',
            'foodSafetyStats', 'foodSafetyBySource', 'foodSafetyTrend',
            'recentFoodSafetyChecks', 'recentFoodSafetyActivities'
        ));
    }
}
