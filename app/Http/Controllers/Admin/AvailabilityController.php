<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commodity;
use App\Models\CommodityStock;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $commodities = Commodity::with(['latestPrice', 'latestStock', 'stocks' => function($q) {
            $q->latest('monitoring_date')->take(30);
        }])->active()->pangan()->get();

        // Calculate price status for each commodity
        $commodities->each(function($c) {
            $c->price_comparison = $c->getPriceComparison();
        });

        $stocks = CommodityStock::with('commodity')->latest('monitoring_date')->paginate(20);

        // Chart data
        $chartData = [];
        foreach ($commodities as $c) {
            $chartData[] = [
                'name' => $c->name,
                'stock' => $c->latestStock ? (float) $c->latestStock->stock_quantity : 0,
                'weekly_need' => $c->latestStock ? (float) $c->latestStock->weekly_need : 0,
                'status' => $c->latestStock ? $c->latestStock->stock_status : '-',
                'duration' => $c->latestStock ? $c->latestStock->stock_duration_days : 0,
            ];
        }

        $summary = [
            'aman' => $commodities->filter(fn($c) => $c->latestStock && $c->latestStock->stock_status === 'aman')->count(),
            'rawan' => $commodities->filter(fn($c) => $c->latestStock && $c->latestStock->stock_status === 'rawan')->count(),
            'kritis' => $commodities->filter(fn($c) => $c->latestStock && $c->latestStock->stock_status === 'kritis')->count(),
            'total' => $commodities->count(),
        ];

        return view('admin.availability.index', compact('commodities', 'stocks', 'chartData', 'summary'));
    }

    public function create()
    {
        $commodities = Commodity::active()->pangan()->orderBy('name')->pluck('name', 'id');
        return view('admin.availability.create', compact('commodities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'commodity_id' => 'required|exists:commodities,id',
            'monitoring_date' => 'required|date',
            'stock_quantity' => 'required|numeric|min:0',
            'stock_unit' => 'required|string|max:20',
            'weekly_need' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Auto-calculate stock duration days
        $stockQty = $data['stock_quantity'];
        $weeklyNeed = $data['weekly_need'];
        $durationDays = $weeklyNeed > 0 ? (int) round(($stockQty / $weeklyNeed) * 7) : 0;

        // Auto-determine status
        if ($weeklyNeed > 0) {
            $weeksAvailable = $stockQty / $weeklyNeed;
            if ($weeksAvailable >= 4) {
                $status = 'aman';
            } elseif ($weeksAvailable >= 2) {
                $status = 'rawan';
            } else {
                $status = 'kritis';
            }
        } else {
            $status = 'aman';
        }

        $commodity = Commodity::findOrFail($data['commodity_id']);

        CommodityStock::updateOrCreate(
            ['commodity_id' => $data['commodity_id'], 'monitoring_date' => $data['monitoring_date']],
            [
                'stock_quantity' => $stockQty,
                'stock_unit' => $data['stock_unit'],
                'stock_duration_days' => $durationDays,
                'weekly_need' => $weeklyNeed,
                'stock_status' => $status,
                'notes' => $data['notes'],
            ]
        );

        ActivityLogService::log('create', 'stock', "Input stok {$commodity->name}: {$stockQty} {$data['stock_unit']} ({$status})", ['commodity_id' => $commodity->id]);
        return redirect()->route('admin.availability.index')->with('success', 'Data stok tersimpan.');
    }

    public function edit(CommodityStock $stock)
    {
        $commodities = Commodity::active()->pangan()->orderBy('name')->pluck('name', 'id');
        return view('admin.availability.edit', compact('commodities', 'stock'));
    }

    public function update(Request $request, CommodityStock $stock)
    {
        $data = $request->validate([
            'monitoring_date' => 'required|date',
            'stock_quantity' => 'required|numeric|min:0',
            'stock_unit' => 'required|string|max:20',
            'weekly_need' => 'required|numeric|min:0',
            'stock_status' => 'required|in:aman,rawan,kritis',
            'notes' => 'nullable|string',
        ]);

        $stockQty = $data['stock_quantity'];
        $weeklyNeed = $data['weekly_need'];
        $durationDays = $weeklyNeed > 0 ? (int) round(($stockQty / $weeklyNeed) * 7) : 0;

        $stock->update([
            'monitoring_date' => $data['monitoring_date'],
            'stock_quantity' => $stockQty,
            'stock_unit' => $data['stock_unit'],
            'stock_duration_days' => $durationDays,
            'weekly_need' => $weeklyNeed,
            'stock_status' => $data['stock_status'],
            'notes' => $data['notes'],
        ]);

        ActivityLogService::log('update', 'stock', "Update stok {$stock->commodity->name}", ['stock_id' => $stock->id]);
        return redirect()->route('admin.availability.index')->with('success', 'Data stok diperbarui.');
    }

    public function destroy(CommodityStock $stock)
    {
        $stock->delete();
        return back()->with('success', 'Data stok dihapus.');
    }
}
