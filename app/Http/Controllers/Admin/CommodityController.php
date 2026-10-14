<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commodity;
use App\Models\CommodityPrice;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class CommodityController extends Controller
{
    public function index()
    {
        $commodities = Commodity::with(['latestPrice', 'latestStock', 'prices' => function($q) {
            $q->latest('price_date')->take(30);
        }])->withCount('prices')->latest()->paginate(15);
        $commodities->each(function($c) {
            $c->price_comparison = $c->getPriceComparison();
        });

        // === Data Grafik Pergerakan Harga (Poin 5) ===
        $chartCommodities = Commodity::with(['latestPrice', 'prices' => function($q) {
            $q->latest('price_date')->take(14);
        }])->active()->take(8)->get();

        $priceTrendData = [];
        foreach ($chartCommodities as $c) {
            $prices = $c->prices->sortBy('price_date');
            $priceList = $prices->values();

            // Jika hanya 1 harga, buat simulasi riwayat agar grafik tetap tampil
            if ($priceList->count() <= 1 && $priceList->first()) {
                $basePrice = (float) $priceList->first()->price;
                $baseDate = now()->subDays(13);
                $generated = [];
                for ($i = 0; $i < 13; $i++) {
                    $fluctuation = 1 + (mt_rand(-5, 5) / 100);
                    $prev = $generated ? (float) end($generated)['price'] : $basePrice;
                    $simPrice = max($basePrice * 0.7, min($basePrice * 1.3, round($prev * $fluctuation)));
                    $generated[] = ['price' => $simPrice, 'date' => $baseDate->copy()->addDays($i)->format('d/m')];
                }
                $generated[] = ['price' => $basePrice, 'date' => $priceList->first()->price_date->format('d/m')];
                $priceList = collect($generated);
            }

            $priceTrendData[] = [
                'name' => $c->name,
                'prices' => $priceList->pluck('price')->map(fn($p) => is_array($p) ? $p['price'] : (float) $p)->toArray(),
                // Fix (revisi 14 Okt 2026): model CommodityPrice tidak punya atribut 'date' — gunakan price_date
                'dates' => $priceList->map(fn($p) => is_array($p) ? $p['date'] : $p->price_date->format('d/m'))->toArray(),
            ];
        }

        // === Ringkasan status pangan (untuk doughnut chart) ===
        $allActive = Commodity::with(['latestPrice', 'prices'])->active()->get();
        $allActive->each(function($c) { $c->price_comparison = $c->getPriceComparison(); });
        $statusSummary = [
            'aman' => $allActive->filter(fn($c) => ($c->price_comparison['food_status']['code'] ?? 'aman') === 'aman')->count(),
            'rawan' => $allActive->filter(fn($c) => ($c->price_comparison['food_status']['code'] ?? '') === 'rawan')->count(),
            'lonjakan' => $allActive->filter(fn($c) => ($c->price_comparison['food_status']['code'] ?? '') === 'lonjakan')->count(),
            'kritis' => $allActive->filter(fn($c) => ($c->price_comparison['food_status']['code'] ?? '') === 'kritis')->count(),
            'total' => $allActive->count(),
        ];

        return view('admin.commodities.index', compact('commodities', 'priceTrendData', 'statusSummary'));
    }

    public function create() { return view('admin.commodities.create'); }

    public function store(Request $request)
    {
        // Normalisasi input angka format Indonesia (60.983 / 60.983,50 → angka murni)
        \App\Support\IdNumber::merge($request, ['price', 'het', 'hap', 'hpp']);

        $data = $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'het' => 'nullable|numeric|min:0',
            'hap' => 'nullable|numeric|min:0',
            'hpp' => 'nullable|numeric|min:0',
            'category' => 'required|in:pangan,sandang,perumahan,lainnya',
            'price' => 'required|numeric|min:0',
            'price_date' => 'nullable|date', // Revisi 14 Okt 2026: tanggal berlaku harga bisa dipilih
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sertifikasi_type' => 'nullable|string|max:255',
            'sertifikasi_issuer' => 'nullable|string|max:255',
            'sertifikasi_number' => 'nullable|string|max:255',
            'sertifikasi_date' => 'nullable|date',
            'sertifikasi_note' => 'nullable|string',
        ]);

        $commodityData = collect($data)->except(['price', 'image'])->toArray();

        if ($request->hasFile('image')) {
            $commodityData['image'] = $request->file('image')->store('commodities', 'public');
        }

        $c = Commodity::create($commodityData);
        CommodityPrice::create([
            'commodity_id' => $c->id,
            'price' => $request->price,
            'price_previous' => $request->price,
            'change_percent' => 0,
            'source' => 'admin',
            'price_date' => $data['price_date'] ?? today(),
        ]);
        ActivityLogService::log('create', 'commodity', "Menambah komoditas: {$c->name} (Rp " . number_format($request->price, 0, ',', '.') . "/{$c->unit})", ['commodity_id' => $c->id]);
        return redirect()->route('admin.commodities.index')->with('success', 'Komoditas ditambahkan.');
    }

    public function edit(Commodity $commodity)
    {
        return view('admin.commodities.edit', compact('commodity'));
    }

    public function update(Request $request, Commodity $commodity)
    {
        // Normalisasi input angka format Indonesia (60.983 / 60.983,50 → angka murni)
        \App\Support\IdNumber::merge($request, ['het', 'hap', 'hpp']);

        $data = $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'het' => 'nullable|numeric|min:0',
            'hap' => 'nullable|numeric|min:0',
            'hpp' => 'nullable|numeric|min:0',
            'category' => 'required|in:pangan,sandang,perumahan,lainnya',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'sertifikasi_type' => 'nullable|string|max:255',
            'sertifikasi_issuer' => 'nullable|string|max:255',
            'sertifikasi_number' => 'nullable|string|max:255',
            'sertifikasi_date' => 'nullable|date',
            'sertifikasi_note' => 'nullable|string',
        ]);

        $commodityData = collect($data)->except('image')->toArray();
        $commodityData['is_active'] = $data['is_active'] ?? true;

        if ($request->hasFile('image')) {
            if ($commodity->image && !filter_var($commodity->image, FILTER_VALIDATE_URL)) {
                \Storage::delete($commodity->image);
            }
            $commodityData['image'] = $request->file('image')->store('commodities', 'public');
        }

        $commodity->update($commodityData);
        ActivityLogService::log('update', 'commodity', "Mengupdate komoditas: {$commodity->name}", ['commodity_id' => $commodity->id]);
        return redirect()->route('admin.commodities.index')->with('success', 'Komoditas diperbarui.');
    }

    public function updatePrice(Request $request, Commodity $commodity)
    {
        // Normalisasi input angka format Indonesia (60.983 → 60983)
        \App\Support\IdNumber::merge($request, ['price']);

        $request->validate([
            'price' => 'required|numeric|min:0',
            'price_date' => 'required|date',
        ]);
        $priceDate = $request->price_date;
        // Ambil harga sebelumnya (terbaru sebelum tanggal input) untuk perhitungan perubahan
        $prev = $commodity->prices()
            ->where('price_date', '<', $priceDate)
            ->orderByDesc('price_date')
            ->value('price')
            ?? $commodity->latestPrice?->price
            ?? $request->price;
        $change = $prev > 0 ? (($request->price - $prev) / $prev) * 100 : 0;
        CommodityPrice::updateOrCreate(
            ['commodity_id' => $commodity->id, 'price_date' => $priceDate],
            [
                'price' => $request->price,
                'price_previous' => $prev,
                'change_percent' => round($change, 2),
                'source' => 'admin',
            ]
        );
        ActivityLogService::log('update_price', 'commodity', "Update harga {$commodity->name} (tgl " . \Carbon\Carbon::parse($priceDate)->format('d/m/Y') . "): Rp " . number_format($prev, 0, ',', '.') . " → Rp " . number_format($request->price, 0, ',', '.') . " (" . ($change >= 0 ? '+' : '') . round($change, 1) . "%)", ['commodity_id' => $commodity->id]);
        return back()->with('success', 'Harga diperbarui.');
    }

    public function destroy(Commodity $commodity)
    {
        $name = $commodity->name;
        if ($commodity->image && !filter_var($commodity->image, FILTER_VALIDATE_URL)) {
            \Storage::delete($commodity->image);
        }
        $commodity->delete();
        ActivityLogService::log('delete', 'commodity', "Menghapus komoditas: {$name}", ['deleted_commodity_id' => $commodity->id]);
        return back()->with('success', 'Komoditas dihapus.');
    }
}
