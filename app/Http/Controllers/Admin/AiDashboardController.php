<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiInsight;
use App\Models\Commodity;
use App\Models\CommodityPrice;
use App\Services\ActivityLogService;

class AiDashboardController extends Controller
{
    /**
     * AI Market Intelligence (revisi 15 Okt 2026):
     * Tidak lagi membaca produk UMKM — fokus ke prediksi kenaikan
     * harga pangan & proyeksi harga 7 hari ke depan.
     */
    public function index()
    {
        $forecasts = $this->buildForecasts();
        $allInsights = AiInsight::active()->latest()->paginate(20);

        return view('admin.ai-dashboard.index', compact('forecasts', 'allInsights'));
    }

    public function inflation()
    {
        $commodities = Commodity::with('latestPrice')->active()->pangan()->get();
        $priceIncreases = CommodityPrice::where('change_percent', '>', 0)->where('price_date', '>=', now()->subDays(7))->with('commodity')->orderBy('change_percent','desc')->get();
        $inflationPredictions = AiInsight::active()->where('type', 'inflation_prediction')->latest()->take(5)->get();
        return view('admin.ai-dashboard.inflation', compact('commodities', 'priceIncreases', 'inflationPredictions'));
    }

    /**
     * Generate AI Insights: prediksi kenaikan harga pangan 7 hari ke depan.
     */
    public function generateInsights()
    {
        // Nonaktifkan insight prediksi lama agar tidak menumpuk
        AiInsight::where('type', 'price_prediction')->update(['is_active' => false]);

        $forecasts = $this->buildForecasts();
        $rising = $forecasts->filter(fn ($f) => $f['predicted_change_pct'] >= 0.5);

        foreach ($rising as $f) {
            $aboveHet = $f['het'] !== null && $f['predicted_price'] > $f['het'];

            AiInsight::create([
                'type' => 'price_prediction',
                'title' => ($f['predicted_change_pct'] > 5 ? 'Prediksi Lonjakan Harga: ' : 'Prediksi Kenaikan Harga: ').$f['name'],
                'description' => sprintf(
                    'Harga %s diprediksi %s %s%% dalam 7 hari ke depan, dari Rp %s menjadi ± Rp %s per %s. Tren harian: %s%%/hari. Keyakinan: %s%%.',
                    $f['name'],
                    $f['predicted_change_pct'] > 5 ? 'melonjak' : 'naik',
                    number_format($f['predicted_change_pct'], 1, ',', '.'),
                    number_format($f['current_price'], 0, ',', '.'),
                    number_format($f['predicted_price'], 0, ',', '.'),
                    $f['unit'],
                    number_format($f['daily_trend_pct'], 2, ',', '.'),
                    number_format($f['confidence'], 0, ',', '.')
                ).($aboveHet ? ' Prediksi ini melewati HET (Rp '.number_format($f['het'], 0, ',', '.').' — perlu pemantauan ketat).' : ''),
                'data' => [
                    'commodity_id' => $f['id'],
                    'current_price' => $f['current_price'],
                    'predicted_price' => $f['predicted_price'],
                    'predicted_change_pct' => $f['predicted_change_pct'],
                    'daily_trend_pct' => $f['daily_trend_pct'],
                    'horizon_days' => 7,
                ],
                'confidence_score' => round($f['confidence'], 1),
                'severity' => ($f['predicted_change_pct'] > 5 || $aboveHet) ? 'critical' : ($f['predicted_change_pct'] > 2 ? 'warning' : 'info'),
                'expires_at' => now()->addDays(7),
            ]);
        }

        ActivityLogService::log('generate', 'ai', 'Generate AI Insights: prediksi harga pangan 7 hari ke depan ('.$rising->count().' komoditas berpotensi naik)');

        return redirect()->route('admin.ai-dashboard.index')->with('success', 'AI Insights prediksi harga pangan berhasil di-generate.');
    }

    /**
     * Bangun prediksi untuk semua komoditas pangan aktif
     * berdasarkan riwayat harga 60 hari terakhir.
     */
    private function buildForecasts()
    {
        $commodities = Commodity::active()->pangan()
            ->with(['latestPrice', 'prices' => fn ($q) => $q->where('price_date', '>=', now()->subDays(60)->startOfDay())->orderBy('price_date')])
            ->get();

        return $commodities
            ->map(fn ($c) => $this->forecastCommodity($c))
            ->filter(fn ($f) => $f['current_price'] !== null && $f['current_price'] > 0)
            ->sortByDesc('predicted_change_pct')
            ->values();
    }

    /**
     * Prediksi satu komoditas: regresi linear pada ln(harga) untuk
     * mendapat laju tren harian, di-damping lalu diekstrapolasi 7 hari.
     */
    private function forecastCommodity(Commodity $c): array
    {
        // Satu harga per tanggal (ambil terbaru bila duplikat), urut lama → baru
        $series = $c->prices
            ->groupBy(fn ($p) => $p->price_date->format('Y-m-d'))
            ->map(fn ($g) => (float) $g->sortByDesc('price_date')->first()->price)
            ->sortKeys();

        $current = (float) ($c->latestPrice?->price ?? $series->last() ?? 0);

        $result = [
            'id' => $c->id,
            'name' => $c->name,
            'unit' => $c->unit,
            'het' => $c->het !== null ? (float) $c->het : null,
            'current_price' => $current > 0 ? $current : null,
            'history' => $series->take(-14)->map(fn ($price, $date) => ['date' => $date, 'price' => $price])->values()->all(),
            'data_points' => $series->count(),
        ];

        // Data terlalu sedikit → tidak bisa memprediksi, anggap stabil
        if ($series->count() < 3 || $current <= 0) {
            return array_merge($result, [
                'predicted_price' => $current,
                'predicted_change_pct' => 0.0,
                'daily_trend_pct' => 0.0,
                'volatility' => 0.0,
                'confidence' => 40.0,
                'risk' => 'stabil',
                'forecast_points' => [],
            ]);
        }

        $values = array_values($series->all());
        $n = count($values);

        // Regresi linear pada ln(harga) → laju pertumbuhan harian (ln/hari)
        $sumX = $sumY = $sumXY = $sumX2 = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $y = log(max($values[$i], 0.0001));
            $sumX += $i;
            $sumY += $y;
            $sumXY += $i * $y;
            $sumX2 += $i * $i;
        }
        $denom = ($n * $sumX2) - ($sumX * $sumX);
        $dailyRate = $denom != 0 ? (($n * $sumXY) - ($sumX * $sumY)) / $denom : 0.0;

        // Volatilitas: simpangan baku perubahan harian (%)
        $changes = [];
        for ($i = 1; $i < $n; $i++) {
            if ($values[$i - 1] > 0) {
                $changes[] = (($values[$i] - $values[$i - 1]) / $values[$i - 1]) * 100;
            }
        }
        $volatility = $this->stddev($changes);

        // Damping 65% agar ekstrapolasi tidak eksplosif
        $effectiveRate = $dailyRate * 0.65;

        // Prediksi hari ke-7 (dibatasi ±20% dari harga saat ini)
        $predicted = $current * exp($effectiveRate * 7);
        $predicted = max($current * 0.8, min($current * 1.2, $predicted));
        $changePct = (($predicted - $current) / $current) * 100;

        // Titik prediksi harian untuk grafik
        $forecastPoints = [];
        for ($d = 1; $d <= 7; $d++) {
            $forecastPoints[] = round($current * exp($effectiveRate * $d), 2);
        }

        // Keyakinan: makin banyak data & makin kecil volatilitas → makin tinggi
        $confidence = max(40, min(95, 55 + ($n * 1.2) - ($volatility * 6)));

        $risk = 'stabil';
        if ($changePct >= 3) {
            $risk = 'tinggi';
        } elseif ($changePct >= 0.5) {
            $risk = 'waspada';
        } elseif ($changePct <= -0.5) {
            $risk = 'turun';
        }

        return array_merge($result, [
            'predicted_price' => round($predicted, 2),
            'predicted_change_pct' => round($changePct, 2),
            'daily_trend_pct' => round($dailyRate * 100, 3),
            'volatility' => round($volatility, 2),
            'confidence' => round($confidence, 1),
            'risk' => $risk,
            'forecast_points' => $forecastPoints,
        ]);
    }

    private function stddev(array $values): float
    {
        $n = count($values);
        if ($n < 2) {
            return 0.0;
        }
        $mean = array_sum($values) / $n;

        return sqrt(array_sum(array_map(fn ($v) => ($v - $mean) ** 2, $values)) / ($n - 1));
    }
}
