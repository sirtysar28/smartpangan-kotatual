<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\RapidTest;
use App\Models\LabTest;
use App\Models\Umkm;
use App\Services\ActivityLogService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() { return view('admin.reports.index'); }

    /**
     * Revisi 14 Okt 2026:
     * - Laporan Penjualan, UMKM & Produk Terlaris disembunyikan dari menu
     *   (tidak relevan — fungsional tetap dipertahankan untuk kebutuhan lain).
     * - Laporan Panel Harga & Ketersediaan Pangan dipisah, default periode 1 tahun.
     */

    /**
     * Ambil & normalisasi rentang tanggal dari request (nullable).
     * Revisi 14 Okt 2026: jika kosong, default periode 1 tahun terakhir.
     */
    private function dateRange(Request $request, bool $defaultOneYear = false): array
    {
        $data = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $start = $data['start_date'] ?? null;
        $end = $data['end_date'] ?? null;

        if ($defaultOneYear && (!$start || !$end)) {
            $end = $end ?? now()->toDateString();
            $start = $start ?? now()->subYear()->toDateString();
        }

        return [$start, $end];
    }

    /**
     * Label periode untuk header PDF.
     */
    private function periodeLabel(?string $start, ?string $end): string
    {
        $fmt = fn($d) => \Illuminate\Support\Carbon::parse($d)->translatedFormat('d F Y');

        if ($start && $end) return "Periode {$fmt($start)} s/d {$fmt($end)}";
        if ($start) return "Periode {$fmt($start)} s/d Sekarang";
        if ($end) return "Periode s/d {$fmt($end)}";
        return 'Semua Periode';
    }

    public function salesReport(Request $request)
    {
        $request->validate(['start_date' => 'required|date', 'end_date' => 'required|date']);
        $orders = Order::with(['user','umkm','items.product'])->where('status','!=','cancelled')
            ->whereBetween('created_at', [$request->start_date, $request->end_date.' 23:59:59'])->get();
        $summary = ['total_orders' => $orders->count(), 'total_revenue' => $orders->sum('total_amount'), 'start_date' => $request->start_date, 'end_date' => $request->end_date];
        ActivityLogService::log('export', 'report', "Download laporan penjualan: {$request->start_date} s/d {$request->end_date} ({$orders->count()} pesanan)");
        $pdf = Pdf::loadView('admin.reports.sales-pdf', compact('orders','summary'));
        return $pdf->download('laporan-penjualan-'.$request->start_date.'.pdf');
    }

    public function umkmReport(Request $request)
    {
        [$start, $end] = $this->dateRange($request);

        $umkms = Umkm::with(['user','products','orders'])->verified()
            ->when($start, fn($q) => $q->whereDate('created_at', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('created_at', '<=', $end))
            ->get();

        $periode = $this->periodeLabel($start, $end);
        ActivityLogService::log('export', 'report', "Download laporan UMKM ({$umkms->count()} UMKM) — {$periode}");
        $pdf = Pdf::loadView('admin.reports.umkm-pdf', compact('umkms','periode'));
        return $pdf->download('laporan-umkm-'.now()->format('Y-m-d').'.pdf');
    }

    public function commodityReport(Request $request)
    {
        // Revisi 14 Okt 2026: default periode 1 tahun (panel harga dibuat untuk 1 tahun)
        [$start, $end] = $this->dateRange($request, defaultOneYear: true);

        $commodities = \App\Models\Commodity::with(['latestPrice', 'prices' => fn($q) => $q
                ->when($start, fn($q) => $q->whereDate('price_date', '>=', $start))
                ->when($end, fn($q) => $q->whereDate('price_date', '<=', $end))
            ])
            ->active()->pangan()
            ->when($start, function ($q) use ($start, $end) {
                $q->whereHas('prices', fn($p) => $p->whereDate('price_date', '>=', $start)
                    ->when($end, fn($p) => $p->whereDate('price_date', '<=', $end)));
            })
            ->get();

        // Perbandingan harga: minggu ke minggu, bulan lalu, tahun lalu (riwayat & tren kenaikan/penurunan)
        $commodities->each(fn($c) => $c->price_comparison = $c->getPriceComparison());

        $periode = $this->periodeLabel($start, $end);
        ActivityLogService::log('export', 'report', "Download laporan panel harga ({$commodities->count()} item) — {$periode}");
        $pdf = Pdf::loadView('admin.reports.commodity-pdf', compact('commodities','periode'));
        return $pdf->download('laporan-panel-harga-'.now()->format('Y-m-d').'.pdf');
    }

    // ==================== LAPORAN KETERSEDIAAN PANGAN (STOK) — TERPISAH DARI PANEL HARGA (REVISI 14 OKT 2026) ====================

    public function availabilityReport(Request $request)
    {
        // Laporan ketersediaan dipisah dari laporan harga; default periode 1 tahun
        [$start, $end] = $this->dateRange($request, defaultOneYear: true);

        $stocks = \App\Models\CommodityStock::with('commodity')
            ->when($start, fn($q) => $q->whereDate('monitoring_date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('monitoring_date', '<=', $end))
            ->orderBy('monitoring_date')->get()->groupBy('commodity_id');

        // Ringkasan per komoditas: stok terakhir + riwayat dalam periode
        $summaries = \App\Models\Commodity::with(['latestStock'])
            ->whereHas('stocks', fn($s) => $s
                ->when($start, fn($s) => $s->whereDate('monitoring_date', '>=', $start))
                ->when($end, fn($s) => $s->whereDate('monitoring_date', '<=', $end)))
            ->active()->pangan()->get()
            ->map(function ($c) use ($stocks) {
                $history = $stocks->get($c->id, collect());
                $withDuration = $history->whereNotNull('stock_duration_days');
                return [
                    'commodity' => $c,
                    'latest' => $history->sortByDesc('monitoring_date')->first(),
                    'avg_stock' => $history->count() ? round($history->avg('stock_quantity'), 2) : null,
                    'avg_duration' => $withDuration->count() ? round($withDuration->avg('stock_duration_days')) : null,
                    'records' => $history->count(),
                    'history' => $history->sortByDesc('monitoring_date')->take(12)->values(),
                ];
            });

        $periode = $this->periodeLabel($start, $end);
        ActivityLogService::log('export', 'report', "Download laporan ketersediaan pangan ({$summaries->count()} komoditas) — {$periode}");
        $pdf = Pdf::loadView('admin.reports.availability-pdf', compact('summaries','periode'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('laporan-ketersediaan-pangan-'.now()->format('Y-m-d').'.pdf');
    }

    public function topProductsReport(Request $request)
    {
        [$start, $end] = $this->dateRange($request);

        $products = Product::with(['umkm','category'])->active()->popular()
            ->when($start, fn($q) => $q->whereHas('orderItems', fn($oi) => $oi->whereHas('order', fn($o) => $o
                ->where('status', '!=', 'cancelled')
                ->whereDate('created_at', '>=', $start)
                ->when($end, fn($o) => $o->whereDate('created_at', '<=', $end)))))
            ->take(50)->get();

        $periode = $this->periodeLabel($start, $end);
        ActivityLogService::log('export', 'report', "Download laporan produk terlaris ({$products->count()} produk) — {$periode}");
        $pdf = Pdf::loadView('admin.reports.top-products-pdf', compact('products','periode'));
        return $pdf->download('laporan-produk-terlaris-'.now()->format('Y-m-d').'.pdf');
    }

    // ==================== LAPORAN KEAMANAN PANGAN (RAPID TEST & UJI LAB) ====================

    public function foodSafetyReport(Request $request)
    {
        $data = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'report_type' => 'nullable|in:all,rapid,lab',
        ]);

        $type = $data['report_type'] ?? 'all';
        $startDate = $data['start_date'] ?? null;
        $endDate = $data['end_date'] ?? null;

        $dateRange = fn($q) => $q
            ->when($startDate, fn($q) => $q->whereDate('test_date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('test_date', '<=', $endDate));

        $rapidTests = in_array($type, ['all', 'rapid'])
            ? $dateRange(RapidTest::query())->with('creator')->orderBy('test_date')->get()
            : collect();

        $labTests = in_array($type, ['all', 'lab'])
            ? $dateRange(LabTest::query())->with('parameters')->orderBy('test_date')->get()
            : collect();

        $summary = [
            'total_rapid' => $rapidTests->count(),
            'rapid_positif' => $rapidTests->where('result', 'positif')->count(),
            'rapid_negatif' => $rapidTests->where('result', 'negatif')->count(),
            'total_lab' => $labTests->count(),
            'lab_tms' => $labTests->filter(fn($l) => $l->parameters->contains(fn($p) => $p->status === 'TMS'))->count(),
            'lab_ms' => $labTests->filter(fn($l) => $l->parameters->count() > 0 && $l->parameters->every(fn($p) => $p->status !== 'TMS'))->count(),
            'total_parameters' => $labTests->sum(fn($l) => $l->parameters->count()),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'report_type' => $type,
        ];

        ActivityLogService::log('export', 'report', "Download laporan keamanan pangan ({$summary['total_rapid']} rapid test, {$summary['total_lab']} uji lab)");

        $periode = $this->periodeLabel($startDate, $endDate);

        $pdf = Pdf::loadView('admin.reports.food-safety-pdf', compact('rapidTests', 'labTests', 'summary', 'periode'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('laporan-keamanan-pangan-'.now()->format('Y-m-d').'.pdf');
    }
}
