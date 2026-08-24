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

    public function umkmReport()
    {
        $umkms = Umkm::with(['user','products','orders'])->verified()->get();
        ActivityLogService::log('export', 'report', "Download laporan UMKM ({$umkms->count()} UMKM)");
        $pdf = Pdf::loadView('admin.reports.umkm-pdf', compact('umkms'));
        return $pdf->download('laporan-umkm-'.now()->format('Y-m-d').'.pdf');
    }

    public function commodityReport()
    {
        $commodities = \App\Models\Commodity::with('latestPrice')->active()->pangan()->get();
        ActivityLogService::log('export', 'report', "Download laporan komoditas ({$commodities->count()} item)");
        $pdf = Pdf::loadView('admin.reports.commodity-pdf', compact('commodities'));
        return $pdf->download('laporan-komoditas-'.now()->format('Y-m-d').'.pdf');
    }

    public function topProductsReport()
    {
        $products = Product::with(['umkm','category'])->active()->popular()->take(50)->get();
        ActivityLogService::log('export', 'report', "Download laporan produk terlaris ({$products->count()} produk)");
        $pdf = Pdf::loadView('admin.reports.top-products-pdf', compact('products'));
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

        $fmt = fn($d) => \Illuminate\Support\Carbon::parse($d)->translatedFormat('d F Y');
        $periode = $startDate || $endDate
            ? 'Periode ' . ($startDate ? $fmt($startDate) : 'awal') . ' s/d ' . ($endDate ? $fmt($endDate) : 'sekarang')
            : 'Semua Periode';

        $pdf = Pdf::loadView('admin.reports.food-safety-pdf', compact('rapidTests', 'labTests', 'summary', 'periode'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('laporan-keamanan-pangan-'.now()->format('Y-m-d').'.pdf');
    }
}
