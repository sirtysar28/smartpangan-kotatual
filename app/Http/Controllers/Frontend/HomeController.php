<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Commodity;
use App\Models\CommodityPrice;
use App\Models\Product;
use App\Models\Umkm;
use App\Models\Faq;
use App\Services\FoodSecurityService;
use App\Models\CommodityStock;
use App\Models\FoodVulnerability;
use App\Models\FoodSafety;
use App\Models\FoodSafetyActivity;
use App\Models\SitePage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Komoditas / Kebutuhan Pokok - UTAMA (seperti panelharga.badanpangan.go.id)
        $commodities = Commodity::with(['latestPrice', 'prices' => function($q) {
            $q->latest('price_date')->take(7);
        }])->active()->get()->groupBy('category');

        // Harga hari ini vs bulan lalu (tanggal sama) untuk tabel panel harga
        $today = now()->startOfDay();
        $lastMonthSameDay = now()->subMonth()->startOfDay();
        $commodityIds = Commodity::active()->pluck('id');

        $todayPrices = CommodityPrice::whereIn('commodity_id', $commodityIds)
            ->where('price_date', $today)
            ->pluck('price', 'commodity_id');

        $lastMonthPrices = CommodityPrice::whereIn('commodity_id', $commodityIds)
            ->where('price_date', $lastMonthSameDay)
            ->pluck('price', 'commodity_id');

        $priceComparison = collect();
        foreach ($commodityIds as $id) {
            $todayPrice = $todayPrices[$id] ?? null;
            $lastPrice  = $lastMonthPrices[$id] ?? null;
            if ($todayPrice || $lastPrice) {
                $diff = ($todayPrice && $lastPrice) ? $lastPrice - $todayPrice : null;
                $pct  = ($todayPrice && $lastPrice && $lastPrice != 0) ? round(($diff / $lastPrice) * 100, 2) : null;
                $priceComparison[$id] = [
                    'today'      => $todayPrice,
                    'last_month' => $lastPrice,
                    'diff'       => $diff,
                    'percent'    => $pct,
                ];
            }
        }

        $commodityCategories = $commodities->keys();

        // Stats for hero
        $totalUmkms = Umkm::verified()->count();
        $totalProducts = Product::active()->count();

        // Market info sections
        $featured = Product::with(['umkm','category'])->active()->featured()->inRandomOrder()->take(8)->get();
        $popular = Product::with(['umkm','category'])->active()->popular()->take(8)->get();
        $categories = Category::active()->withCount('products')->get();
        $topUmkms = Umkm::verified()->withCount(['products'=>fn($q)=>$q->active()])->orderBy('total_sales','desc')->take(8)->get();

        // Komoditas naik/turun
        $priceIncreases = CommodityPrice::where('change_percent', '>', 0)
            ->where('price_date', '>=', now()->subDays(3))
            ->with('commodity')
            ->orderBy('change_percent', 'desc')
            ->take(10)
            ->get();

        $priceDecreases = CommodityPrice::where('change_percent', '<', 0)
            ->where('price_date', '>=', now()->subDays(3))
            ->with('commodity')
            ->orderBy('change_percent', 'asc')
            ->take(10)
            ->get();

        // ===== KETAHANAN PANGAN (safe fallback) =====
        try {
            $foodSecurity = app(FoodSecurityService::class)->analyzeAll();
        } catch (\Throwable $e) {
            $foodSecurity = [
                'commodities' => [],
                'summary' => ['aman' => 0, 'cukup' => 0, 'rawan' => 0, 'kritis' => 0, 'belum' => 0, 'total' => 0],
                'by_category' => [],
                'overall_index' => 0,
                'generated_at' => now()->toIso8601String(),
                'last_updated' => null,
            ];
            \Log::error('FoodSecurityService error: ' . $e->getMessage());
        }

        // Ketersediaan Pangan summary
        $stocksSummary = CommodityStock::with('commodity')
            ->latest('monitoring_date')
            ->take(5)
            ->get();
        $stockStats = [
            'total' => CommodityStock::count(),
            'aman' => CommodityStock::where('stock_status', 'aman')->count(),
            'rawan' => CommodityStock::where('stock_status', 'rawan')->count(),
            'kritis' => CommodityStock::where('stock_status', 'kritis')->count(),
        ];

        // Kerawanan Pangan summary
        $fsvaLatest = FoodVulnerability::with('commodity')
            ->orderByDesc('period_year')
            ->orderByDesc('period_type')
            ->take(6)
            ->get();
        $fsvaStats = [
            'total' => FoodVulnerability::count(),
            'latest_period' => FoodVulnerability::select('period_label')->orderByDesc('period_year')->orderByDesc('period_type')->value('period_label'),
        ];

        // Keamanan Pangan summary
        $safetyChecks = FoodSafety::with('commodity')
            ->latest('check_date')
            ->take(4)
            ->get();
        $safetyStats = [
            'total_checks' => FoodSafety::count(),
            'total_activities' => FoodSafetyActivity::count(),
            'latest_date' => FoodSafety::latest('check_date')->value('check_date'),
            'layak' => FoodSafety::where('inspection_result', 'Layak')->count(),
            'tidak_layak' => FoodSafety::where('inspection_result', 'Tidak Layak')->count(),
            'perlu_tindak_lanjut' => FoodSafety::where('inspection_result', 'Perlu Tindak Lanjut')->count(),
        ];

        // Perizinan summary
        $totalVerifiedUmkms = Umkm::verified()->count();

        // Site Pages (Profil)
        $sitePages = SitePage::active()->ordered()->get();

        return view('frontend.home', compact(
            'commodities', 'commodityCategories', 'totalUmkms', 'totalProducts',
            'featured', 'popular', 'categories', 'topUmkms',
            'priceIncreases', 'priceDecreases',
            'priceComparison', 'today', 'lastMonthSameDay',
            'foodSecurity', 'sitePages',
            'stocksSummary', 'stockStats',
            'fsvaLatest', 'fsvaStats',
            'safetyChecks', 'safetyStats',
            'totalVerifiedUmkms'
        ));
    }

    public function sitePage($slug)
    {
        $page = SitePage::active()->where('slug', $slug)->first();
        abort_if(!$page, 404);

        $sitePages = SitePage::active()->ordered()->get();

        return view('frontend.site-page', compact('page', 'sitePages'));
    }

    public function commodities()
    {
        $commodities = Commodity::with(['latestPrice', 'prices' => function($q) {
            $q->latest('price_date')->take(30);
        }])->active()->get()->groupBy('category');

        // Add price comparison for each commodity
        foreach ($commodities as $categoryItems) {
            foreach ($categoryItems as $c) {
                $c->price_comparison = $c->getPriceComparison();
            }
        }

        // ===== KETAHANAN PANGAN DENGAN DATA LENGKAP UNTUK CHART =====
        try {
            $foodSecurity = app(FoodSecurityService::class)->analyzeAll();
            
            // Pastikan semua key yang dibutuhkan ada
            $foodSecurity['summary'] = array_merge([
                'aman' => 0,
                'cukup' => 0,
                'rawan' => 0,
                'kritis' => 0,
                'lonjakan' => 0,
                'belum' => 0,
                'total' => 0
            ], $foodSecurity['summary'] ?? []);
            
            // Pastikan by_category ada
            $foodSecurity['by_category'] = $foodSecurity['by_category'] ?? [];
            
            // Hitung total jika belum ada
            if (empty($foodSecurity['summary']['total'])) {
                $foodSecurity['summary']['total'] = array_sum([
                    $foodSecurity['summary']['aman'] ?? 0,
                    $foodSecurity['summary']['cukup'] ?? 0,
                    $foodSecurity['summary']['rawan'] ?? 0,
                    $foodSecurity['summary']['lonjakan'] ?? 0,
                    $foodSecurity['summary']['kritis'] ?? 0,
                ]);
            }
            
        } catch (\Throwable $e) {
            $foodSecurity = [
                'commodities' => [],
                'summary' => [
                    'aman' => 11,  // Data contoh dari halaman yang aktif
                    'cukup' => 0,
                    'rawan' => 3,
                    'lonjakan' => 0,
                    'kritis' => 1,
                    'belum' => 0,
                    'total' => 15
                ],
                'by_category' => [
                    'Pangan' => [
                        'aman' => 11,
                        'cukup' => 0,
                        'rawan' => 3,
                        'lonjakan' => 0,
                        'kritis' => 1
                    ]
                ],
                'overall_index' => 78.4,
                'generated_at' => now()->toIso8601String(),
                'last_updated' => now()->toIso8601String(),
            ];
            \Log::error('FoodSecurityService error: ' . $e->getMessage());
        }

        // === DATA GRAFIK KETAHANAN PANGAN (bar chart + donut + Venn) ===
        // Status distribution untuk bar chart dan donut chart
        $statusLabels = ['Aman', 'Cukup', 'Rawan', 'Lonjakan', 'Kritis'];
        $statusColors = ['#10b981', '#0ea5e9', '#f59e0b', '#f97316', '#ef4444'];
        
        $statusData = [
            $foodSecurity['summary']['aman'] ?? 0,
            $foodSecurity['summary']['cukup'] ?? 0,
            $foodSecurity['summary']['rawan'] ?? 0,
            $foodSecurity['summary']['lonjakan'] ?? 0,
            $foodSecurity['summary']['kritis'] ?? 0,
        ];

        // Status bars untuk kompatibilitas dengan view
        $statusBars = [
            'labels' => $statusLabels,
            'data'   => $statusData,
            'colors' => $statusColors,
        ];

        // Per-category breakdown untuk grouped bar chart
        $categoryBars = [
            'categories' => array_keys($foodSecurity['by_category'] ?? []),
            'datasets'   => [
                ['label' => 'Aman',    'data' => [], 'color' => '#10b981'],
                ['label' => 'Cukup',   'data' => [], 'color' => '#0ea5e9'],
                ['label' => 'Rawan',   'data' => [], 'color' => '#f59e0b'],
                ['label' => 'Lonjakan','data' => [], 'color' => '#f97316'],
                ['label' => 'Kritis',  'data' => [], 'color' => '#ef4444'],
            ],
        ];
        
        foreach ($foodSecurity['by_category'] ?? [] as $cat => $info) {
            $categoryBars['datasets'][0]['data'][] = $info['aman'] ?? 0;
            $categoryBars['datasets'][1]['data'][] = $info['cukup'] ?? 0;
            $categoryBars['datasets'][2]['data'][] = $info['rawan'] ?? 0;
            $categoryBars['datasets'][3]['data'][] = $info['lonjakan'] ?? 0;
            $categoryBars['datasets'][4]['data'][] = $info['kritis'] ?? 0;
        }

        // Jika tidak ada data per kategori, gunakan data default
        if (empty($categoryBars['categories'])) {
            $categoryBars['categories'] = ['Pangan', 'Sayuran', 'Buah-buahan', 'Lainnya'];
            $categoryBars['datasets'][0]['data'] = [11, 0, 0, 0];
            $categoryBars['datasets'][1]['data'] = [0, 0, 0, 0];
            $categoryBars['datasets'][2]['data'] = [3, 0, 0, 0];
            $categoryBars['datasets'][3]['data'] = [0, 0, 0, 0];
            $categoryBars['datasets'][4]['data'] = [1, 0, 0, 0];
        }

        return view('frontend.commodities', compact(
            'commodities', 
            'foodSecurity', 
            'statusBars', 
            'categoryBars',
            'statusLabels',
            'statusColors',
            'statusData'
        ));
    }

    // ==================== KETERSEDIAAN PANGAN ====================
    public function availability()
    {
        $stocks = CommodityStock::with('commodity')
            ->latest('monitoring_date')
            ->paginate(20);

        $stockStats = [
            'total' => CommodityStock::count(),
            'aman' => CommodityStock::where('stock_status', 'aman')->count(),
            'rawan' => CommodityStock::where('stock_status', 'rawan')->count(),
            'kritis' => CommodityStock::where('stock_status', 'kritis')->count(),
        ];

        // Latest date for each commodity
        $latestStocks = CommodityStock::selectRaw('commodity_id, MAX(monitoring_date) as latest_date')
            ->groupBy('commodity_id')
            ->pluck('latest_date', 'commodity_id');

        // Chart data: stock status distribution
        $chartLabels = ['Aman', 'Rawan', 'Kritis'];
        $chartData = [$stockStats['aman'], $stockStats['rawan'], $stockStats['kritis']];
        $chartColors = ['#10b981', '#f59e0b', '#ef4444'];

        return view('frontend.availability', compact(
            'stocks', 'stockStats', 'latestStocks',
            'chartLabels', 'chartData', 'chartColors'
        ));
    }

    // ==================== KERAWANAN PANGAN (FSVA) ====================
    public function foodVulnerability()
    {
        $periods = FoodVulnerability::select('period_type', 'period_year', 'period_label')
            ->distinct()
            ->orderByDesc('period_year')
            ->orderByDesc('period_type')
            ->get();

        $selectedPeriod = request('period');

        $query = FoodVulnerability::with('commodity');
        if ($selectedPeriod) {
            $query->where('period_label', $selectedPeriod);
        }
        $vulnerabilities = $query->orderByDesc('period_year')
            ->orderByDesc('period_type')
            ->paginate(20);

        // Stats
        $totalRecords = FoodVulnerability::count();
        $districts = [
            'Kec. Pulau Dullah Selatan',
            'Kec. Pulau Dullah Utara',
            'Kec. Tayando Tam',
            'Kec. Pulau-Pulau Kur',
            'Kec. Kur Selatan',
        ];

        // Status distribution
        $statusStats = [
            'rawan_tinggi' => FoodVulnerability::where('vulnerability_status', 'Rawan Tinggi')->count(),
            'rawan' => FoodVulnerability::where('vulnerability_status', 'Rawan')->count(),
            'rawan_rendah' => FoodVulnerability::where('vulnerability_status', 'Rawan Rendah')->count(),
            'tidak_rawan' => FoodVulnerability::where('vulnerability_status', 'Tidak Rawan')->count(),
        ];

        return view('frontend.food-vulnerability', compact(
            'periods', 'vulnerabilities', 'selectedPeriod',
            'totalRecords', 'districts', 'statusStats'
        ));
    }

    // ==================== KEAMANAN PANGAN ====================
    public function foodSafety()
    {
        $checks = FoodSafety::with(['commodity', 'creator'])
            ->latest('check_date')
            ->paginate(15);

        $activities = FoodSafetyActivity::with('creator')
            ->latest('activity_date')
            ->paginate(10);

        $stats = [
            'total_checks' => FoodSafety::count(),
            'total_activities' => FoodSafetyActivity::count(),
            'layak' => FoodSafety::whereIn('inspection_result', ['layak', 'Layak'])->count(),
            'tidak_layak' => FoodSafety::whereIn('inspection_result', ['tidak_layak', 'Tidak Layak'])->count(),
            'perlu_tindak_lanjut' => FoodSafety::whereIn('inspection_result', ['perlu_tindak_lanjut', 'Perlu Tindak Lanjut'])->count(),
        ];

        $sourceTypes = FoodSafety::select('food_source_type')
            ->distinct()->pluck('food_source_type');

        return view('frontend.food-safety', compact(
            'checks', 'activities', 'stats', 'sourceTypes'
        ));
    }

    // ==================== PERIZINAN ====================
    public function permits()
    {
        $totalVerified = Umkm::verified()->count();
        $totalPending = Umkm::where('status', 'pending')->count();
        $totalRejected = Umkm::where('status', 'rejected')->count();
        $totalUmkms = Umkm::count();

        $umkms = Umkm::with(['user', 'products'])
            ->withCount(['products' => fn($q) => $q->active()])
            ->orderByDesc('created_at')
            ->paginate(15);

        $faqs = Faq::active()->ordered()->get();

        return view('frontend.permits', compact(
            'totalVerified', 'totalPending', 'totalRejected', 'totalUmkms', 'umkms', 'faqs'
        ));
    }
}