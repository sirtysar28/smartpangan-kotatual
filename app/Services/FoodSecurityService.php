<?php

namespace App\Services;

use App\Models\Commodity;
use App\Models\CommodityPrice;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FoodSecurityService
{
    /**
     * Analisis status ketahanan pangan untuk semua komoditas
     * Mengacu pada standar Badan Pangan Nasional (BAPANAS)
     */
    public function analyzeAll(): array
    {
        $commodities = Commodity::with(['latestPrice', 'prices' => function ($q) {
            $q->where('price_date', '>=', now()->subDays(30))
              ->orderBy('price_date');
        }])->active()->get();

        $results = [];
        $summary = [
            'aman'     => 0,
            'cukup'    => 0,
            'rawan'    => 0,
            'lonjakan' => 0,
            'kritis'   => 0,
            'belum'    => 0,
            'total'    => 0,
        ];

        foreach ($commodities as $commodity) {
            $analysis = $this->analyzeCommodity($commodity);
            $results[] = $analysis;

            if (!$analysis['has_data']) {
                $summary['belum']++;
            } else {
                $summary[$analysis['status']]++;
            }
            $summary['total']++;
        }

        // Group by category
        $byCategory = collect($results)->filter(fn($r) => $r['has_data'])
            ->groupBy('category')
            ->map(fn($items) => [
                'count'  => $items->count(),
                'aman'   => $items->where('status', 'aman')->count(),
                'cukup'  => $items->where('status', 'cukup')->count(),
                'rawan'  => $items->where('status', 'rawan')->count(),
                'kritis' => $items->where('status', 'kritis')->count(),
                'avg_change' => round($items->avg('change_percent'), 2),
            ])->toArray();

        // Overall index (0-100, higher = more secure)
        $overallIndex = $this->calculateOverallIndex($summary);

        return [
            'commodities' => $results,
            'summary'     => $summary,
            'by_category' => $byCategory,
            'overall_index' => $overallIndex,
            'generated_at' => now()->toIso8601String(),
            'last_updated' => CommodityPrice::latest('price_date')->value('price_date'),
        ];
    }

    /**
     * Analisis detail per komoditas
     */
    public function analyzeCommodity(Commodity $commodity): array
    {
        $prices = $commodity->prices;
        $latestPrice = $commodity->latestPrice;

        if (!$latestPrice || $prices->count() === 0) {
            return $this->noDataResult($commodity);
        }

        // Sort prices ascending by date
        $sortedPrices = $prices->sortBy('price_date')->values();
        $priceValues = $sortedPrices->pluck('price')->map(fn($p) => (float) $p)->toArray();

        // Calculate metrics
        $currentPrice = (float) $latestPrice->price;
        $previousPrice = $latestPrice->price_previous ? (float) $latestPrice->price_previous : null;
        $changePercent = (float) ($latestPrice->change_percent ?? 0);

        // 7-day trend
        $prices7d = $sortedPrices->take(-7);
        $prices14d = $sortedPrices->take(-14);

        // Trend direction (linear regression slope)
        $trend7d = $this->calculateTrend($priceValues);
        $trend7dPercent = $priceValues ? round(($trend7d / max(1, $currentPrice)) * 100, 2) : 0;

        // Volatility (standard deviation / mean)
        $volatility = $this->calculateVolatility($priceValues);

        // Consecutive up/down days
        $consecutiveUp = $this->countConsecutiveChange($sortedPrices, 'up');
        $consecutiveDown = $this->countConsecutiveChange($sortedPrices, 'down');

        // Determine status
        $status = $this->determineStatus($changePercent, $trend7dPercent, $volatility, $consecutiveUp, count($priceValues));

        // Generate prediction
        $prediction = $this->generatePrediction(
            $currentPrice,
            $changePercent,
            $trend7dPercent,
            $volatility,
            $priceValues,
            $status
        );

        return [
            'id'            => $commodity->id,
            'name'          => $commodity->name,
            'unit'          => $commodity->unit,
            'category'      => $commodity->category,
            'image_url'     => $commodity->image_url,
            'het'           => $commodity->het ? (float) $commodity->het : null,
            'hap'           => $commodity->hap ? (float) $commodity->hap : null,
            'hpp'           => $commodity->hpp ? (float) $commodity->hpp : null,
            'current_price' => $currentPrice,
            'previous_price'=> $previousPrice,
            'change_percent'=> round($changePercent, 2),
            'trend_7d'      => round($trend7dPercent, 2),
            'volatility'    => round($volatility, 2),
            'consecutive_up'=> $consecutiveUp,
            'status'        => $status['code'],
            'status_label'  => $status['label'],
            'status_color'  => $status['color'],
            'status_icon'   => $status['icon'],
            'status_desc'   => $status['description'],
            'prediction'    => $prediction,
            'price_history' => $sortedPrices->map(fn($p) => [
                'date'   => $p->price_date->format('d/m'),
                'price'  => (float) $p->price,
            ])->toArray(),
            'has_data'      => true,
            'data_points'   => count($priceValues),
            'last_updated'  => $latestPrice->price_date->format('d/m/Y'),
        ];
    }

    /**
     * Tentukan status ketahanan pangan berdasarkan indikator
     * Standar EWS: Aman (tidak naik), Rawan (<5%), Lonjakan (>6%), Kritis (>10%)
     */
    private function determineStatus(
        float $changePercent,
        float $trendPercent,
        float $volatility,
        int $consecutiveUp,
        int $dataPoints
    ): array
    {
        // Direct EWS classification based on price change percentage
        $maxChange = max($changePercent, abs($trendPercent));

        if ($maxChange > 10) {
            return [
                'code'        => 'kritis',
                'label'       => 'Kritis',
                'color'       => '#ef4444',
                'bg_color'    => 'rgba(239,68,68,0.08)',
                'border_color'=> 'rgba(239,68,68,0.2)',
                'icon'        => 'shield-fill-x',
                'description' => '⚠️ Kenaikan harga >10%. Segera diperlukan intervensi pemerintah untuk stabilisasi harga pangan.',
            ];
        } elseif ($maxChange > 6) {
            return [
                'code'        => 'lonjakan',
                'label'       => 'Lonjakan',
                'color'       => '#f97316',
                'bg_color'    => 'rgba(249,115,22,0.08)',
                'border_color'=> 'rgba(249,115,22,0.2)',
                'icon'        => 'exclamation-diamond',
                'description' => '⚠️ Kenaikan harga >6%. Terdeteksi lonjakan harga. Perlu pemantauan ketat.',
            ];
        } elseif ($maxChange >= 0.01) {
            return [
                'code'        => 'rawan',
                'label'       => 'Rawan',
                'color'       => '#f59e0b',
                'bg_color'    => 'rgba(245,158,11,0.08)',
                'border_color'=> 'rgba(245,158,11,0.2)',
                'icon'        => 'exclamation-triangle-fill',
                'description' => '🔔 Kenaikan harga <5%. Potensi kerawanan pangan. Perlu pemantauan lanjutan.',
            ];
        } elseif ($maxChange < -5) {
            // Significant price drop also needs attention
            return [
                'code'        => 'cukup',
                'label'       => 'Cukup',
                'color'       => '#0ea5e9',
                'bg_color'    => 'rgba(14,165,233,0.08)',
                'border_color'=> 'rgba(14,165,233,0.2)',
                'icon'        => 'shield-fill-exclamation',
                'description' => 'Harga terpantau turun signifikan. Perlu pemantauan pasokan.',
            ];
        } else {
            return [
                'code'        => 'aman',
                'label'       => 'Aman',
                'color'       => '#10b981',
                'bg_color'    => 'rgba(16,185,129,0.08)',
                'border_color'=> 'rgba(16,185,129,0.2)',
                'icon'        => 'shield-check',
                'description' => '✅ Ketersediaan & harga pangan stabil. Tidak ada indikasi gangguan pasokan.',
            ];
        }
    }

    /**
     * Generate prediksi harga 7 hari ke depan
     */
    private function generatePrediction(
        float $currentPrice,
        float $changePercent,
        float $trendPercent,
        float $volatility,
        array $priceValues,
        array $status
    ): array
    {
        if (count($priceValues) < 2) {
            return [
                'direction'    => 'unknown',
                'direction_label' => 'Data Kurang',
                'predicted_min' => null,
                'predicted_max' => null,
                'confidence'   => 0,
                'note'         => 'Memerlukan minimal 2 data harga untuk prediksi.',
            ];
        }

        // Simple linear regression for prediction
        $n = count($priceValues);
        $xMean = ($n - 1) / 2;
        $yMean = array_sum($priceValues) / $n;

        $numerator = 0;
        $denominator = 0;
        for ($i = 0; $i < $n; $i++) {
            $numerator += ($i - $xMean) * ($priceValues[$i] - $yMean);
            $denominator += ($i - $xMean) ** 2;
        }

        $slope = $denominator != 0 ? $numerator / $denominator : 0;
        $intercept = $yMean - $slope * $xMean;

        // Predict 7 days ahead
        $predictedPrice = $intercept + $slope * ($n + 6);
        $predictedMin = $predictedPrice * (1 - $volatility / 200);
        $predictedMax = $predictedPrice * (1 + $volatility / 200);

        // Determine direction
        $priceDiffPercent = (($predictedPrice - $currentPrice) / $currentPrice) * 100;

        $direction = match (true) {
            $priceDiffPercent > 5    => 'up',
            $priceDiffPercent > 2    => 'up_slight',
            $priceDiffPercent < -5   => 'down',
            $priceDiffPercent < -2   => 'down_slight',
            default                  => 'stable',
        };

        $directionLabels = [
            'up'         => 'Berpotensi Naik',
            'up_slight'  => 'Berpotensi Naik Sedikit',
            'stable'     => 'Diperkirakan Stabil',
            'down_slight'=> 'Berpotensi Turun Sedikit',
            'down'       => 'Berpotensi Turun',
        ];

        // Confidence based on data points and volatility
        $dataConfidence = min(90, 30 + ($n * 5));
        $volatilityPenalty = min(30, $volatility * 1.5);
        $confidence = max(20, round($dataConfidence - $volatilityPenalty));

        // Generate contextual note
        $note = $this->generatePredictionNote($status['code'], $changePercent, $trendPercent, $direction);

        return [
            'direction'      => $direction,
            'direction_label'=> $directionLabels[$direction],
            'price_diff_pct' => round($priceDiffPercent, 2),
            'predicted_price'=> round($predictedPrice),
            'predicted_min'  => round($predictedMin),
            'predicted_max'  => round($predictedMax),
            'confidence'     => $confidence,
            'slope'          => round($slope, 2),
            'note'           => $note,
        ];
    }

    private function generatePredictionNote(string $status, float $changePercent, float $trendPercent, string $direction): string
    {
        $notes = [];

        if ($status === 'kritis') {
            $notes[] = '⚠️ Diperlukan perhatian serius. Pertimbangkan stabilisasi harga.';
        } elseif ($status === 'rawan') {
            $notes[] = '🔔 Perlu pemantauan ketat. Potensi kenaikan lanjutan.';
        } elseif ($status === 'aman') {
            $notes[] = '✅ Kondisi pangan terpantau aman.';
        }

        if ($trendPercent > 5) {
            $notes[] = 'Tren naik terdeteksi ' . abs(round($trendPercent, 1)) . '%/minggu.';
        } elseif ($trendPercent < -5) {
            $notes[] = 'Tren turun terdeteksi ' . abs(round($trendPercent, 1)) . '%/minggu.';
        }

        if ($direction === 'up' || $direction === 'up_slight') {
            $notes[] = 'Prediksi: harga berpotensi naik dalam 7 hari ke depan.';
        } elseif ($direction === 'down' || $direction === 'down_slight') {
            $notes[] = 'Prediksi: harga berpotensi turun dalam 7 hari ke depan.';
        } else {
            $notes[] = 'Prediksi: harga diperkirakan stabil dalam 7 hari ke depan.';
        }

        return implode(' ', $notes);
    }

    /**
     * Linear regression slope
     */
    private function calculateTrend(array $values): float
    {
        $n = count($values);
        if ($n < 2) return 0;

        $sumX = $sumY = $sumXY = $sumX2 = 0;
        for ($i = 0; $i < $n; $i++) {
            $sumX += $i;
            $sumY += $values[$i];
            $sumXY += $i * $values[$i];
            $sumX2 += $i * $i;
        }

        $denominator = $n * $sumX2 - $sumX * $sumX;
        return $denominator != 0 ? ($n * $sumXY - $sumX * $sumY) / $denominator : 0;
    }

    /**
     * Volatility: Coefficient of Variation (std_dev / mean * 100)
     */
    private function calculateVolatility(array $values): float
    {
        $n = count($values);
        if ($n < 2) return 0;

        $mean = array_sum($values) / $n;
        $sumSquaredDiff = 0;
        foreach ($values as $v) {
            $sumSquaredDiff += ($v - $mean) ** 2;
        }

        $stdDev = sqrt($sumSquaredDiff / $n);
        return $mean > 0 ? ($stdDev / $mean) * 100 : 0;
    }

    /**
     * Hitung hari berturut-turut naik/turun dari data terakhir
     */
    private function countConsecutiveChange($prices, string $direction): int
    {
        $count = 0;
        $reversed = $prices->reverse()->values();

        for ($i = 1; $i < $reversed->count(); $i++) {
            $diff = (float) $reversed[$i]->price - (float) $reversed[$i - 1]->price;

            if ($direction === 'up' && $diff > 0) {
                $count++;
            } elseif ($direction === 'down' && $diff < 0) {
                $count++;
            } else {
                break;
            }
        }

        return $count;
    }

    /**
     * Indeks Ketahanan Pangan Keseluruhan (0-100)
     */
    private function calculateOverallIndex(array $summary): float
    {
        $total = $summary['total'];
        if ($total === 0) return 0;

        $score = 0;
        $score += $summary['aman'] * 100;
        $score += $summary['cukup'] * 70;
        $score += $summary['rawan'] * 40;
        $score += $summary['lonjakan'] * 25;
        $score += $summary['kritis'] * 10;
        $score += $summary['belum'] * 50;

        return round($score / $total, 1);
    }

    private function noDataResult(Commodity $commodity): array
    {
        return [
            'id'            => $commodity->id,
            'name'          => $commodity->name,
            'unit'          => $commodity->unit,
            'category'      => $commodity->category,
            'image_url'     => $commodity->image_url,
            'current_price' => null,
            'previous_price'=> null,
            'change_percent'=> 0,
            'trend_7d'      => 0,
            'volatility'    => 0,
            'consecutive_up'=> 0,
            'status'        => 'belum',
            'status_label'  => 'Belum Ada Data',
            'status_color'  => '#64748b',
            'bg_color'      => 'rgba(100,116,139,0.08)',
            'border_color'  => 'rgba(100,116,139,0.2)',
            'status_icon'   => 'dash-circle',
            'status_desc'   => 'Data harga belum tersedia.',
            'prediction'    => [
                'direction' => 'unknown',
                'direction_label' => 'Data Kurang',
                'predicted_min' => null,
                'predicted_max' => null,
                'confidence' => 0,
                'note' => 'Memerlukan data harga untuk analisis.',
            ],
            'price_history' => [],
            'has_data'      => false,
            'data_points'   => 0,
            'last_updated'  => null,
        ];
    }
}
