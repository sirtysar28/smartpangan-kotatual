<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'unit', 'het', 'hap', 'hpp', 'category', 'image', 'description', 'is_active', 'sertifikasi_type', 'sertifikasi_issuer', 'sertifikasi_number', 'sertifikasi_date', 'sertifikasi_note'];

    protected function casts(): array
    {
        return [
            'het' => 'decimal:2',
            'hap' => 'decimal:2',
            'hpp' => 'decimal:2',
        ];
    }

    public function prices() { return $this->hasMany(CommodityPrice::class); }
    public function latestPrice() { return $this->hasOne(CommodityPrice::class)->latestOfMany('price_date'); }
    public function stocks() { return $this->hasMany(CommodityStock::class); }
    public function latestStock() { return $this->hasOne(CommodityStock::class)->latestOfMany('monitoring_date'); }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopePangan($query) { return $query->where('category', 'pangan'); }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return \Storage::url($this->image);
        }
        return null;
    }

    /**
     * Get price comparison: today, last week, last month, last year
     * (Revisi 14 Okt 2026: tambah perbandingan minggu ke minggu)
     */
    public function getPriceComparison(): array
    {
        $today = now()->startOfDay();
        $lastWeek = now()->subWeek()->startOfDay();
        $lastMonth = now()->subMonth()->startOfDay();
        $lastYear = now()->subYear()->startOfDay();

        $todayPrice = $this->prices()->where('price_date', $today)->value('price')
                     ?? $this->prices()->latest('price_date')->value('price');

        // Today price float (used for HET evaluation)
        $todayPriceFloat = $todayPrice ? (float) $todayPrice : null;

        // ===== Minggu lalu (week-to-week) =====
        $lastWeekPrice = $this->prices()->where('price_date', $lastWeek)->value('price');
        if (!$lastWeekPrice) {
            $lastWeekPrice = $this->prices()
                ->where('price_date', '>=', $lastWeek->copy()->subDays(3))
                ->where('price_date', '<=', $lastWeek->copy()->addDays(3))
                ->orderBy('price_date')
                ->value('price');
        }

        $lastMonthPrice = $this->prices()->where('price_date', $lastMonth)->value('price');

        // If no exact date match, get closest price within 7 days
        if (!$lastMonthPrice) {
            $lastMonthPrice = $this->prices()
                ->where('price_date', '>=', $lastMonth->subDays(7))
                ->where('price_date', '<=', $lastMonth->addDays(14))
                ->orderBy('price_date')
                ->value('price');
        }

        $lastYearPrice = $this->prices()->where('price_date', $lastYear)->value('price');

        if (!$lastYearPrice) {
            $lastYearPrice = $this->prices()
                ->where('price_date', '>=', $lastYear->subDays(7))
                ->where('price_date', '<=', $lastYear->addDays(14))
                ->orderBy('price_date')
                ->value('price');
        }

        // MTM percentage
        $mtmPercent = null;
        if ($todayPrice && $lastMonthPrice && $lastMonthPrice != 0) {
            $mtmPercent = round((($todayPrice - $lastMonthPrice) / $lastMonthPrice) * 100, 2);
        }

        // WTW (week-to-week) percentage
        $wtwPercent = null;
        if ($todayPrice && $lastWeekPrice && $lastWeekPrice != 0) {
            $wtwPercent = round((($todayPrice - $lastWeekPrice) / $lastWeekPrice) * 100, 2);
        }

        // YOY percentage
        $yoyPercent = null;
        if ($todayPrice && $lastYearPrice && $lastYearPrice != 0) {
            $yoyPercent = round((($todayPrice - $lastYearPrice) / $lastYearPrice) * 100, 2);
        }

        // Status pangan based on kenaikan harga (MTM/YOY)
        $priceChange = max($mtmPercent ?? 0, $yoyPercent ?? 0);
        $changeStatus = $this->determineFoodStatus($priceChange);

        // Status berdasarkan batas HET (Harga Eceran Tertinggi)
        // Jika harga hari ini sudah melewati HET → status rawan / lonjakan / kritis
        $hetStatus = $this->determineHetStatus($todayPriceFloat);

        // Ambil status yang paling "buruk" (prioritas lebih tinggi)
        $foodStatus = $this->pickWorstStatus([$changeStatus, $hetStatus]);

        return [
            'today_price' => $todayPriceFloat,
            'last_week_price' => $lastWeekPrice ? (float) $lastWeekPrice : null,
            'last_month_price' => $lastMonthPrice ? (float) $lastMonthPrice : null,
            'last_year_price' => $lastYearPrice ? (float) $lastYearPrice : null,
            'wtw_percent' => $wtwPercent,
            'mtm_percent' => $mtmPercent,
            'yoy_percent' => $yoyPercent,
            'wtw_direction' => $wtwPercent > 0 ? 'up' : ($wtwPercent < 0 ? 'down' : 'stable'),
            'mtm_direction' => $mtmPercent > 0 ? 'up' : ($mtmPercent < 0 ? 'down' : 'stable'),
            'yoy_direction' => $yoyPercent > 0 ? 'up' : ($yoyPercent < 0 ? 'down' : 'stable'),
            'food_status' => $foodStatus,
            'het_exceeded' => $this->het !== null && $todayPriceFloat !== null && $todayPriceFloat > (float) $this->het,
            'het_over_percent' => ($this->het !== null && $todayPriceFloat !== null && (float) $this->het > 0)
                ? round((($todayPriceFloat - (float) $this->het) / (float) $this->het) * 100, 2)
                : null,
        ];
    }

    /**
     * Status pangan berdasarkan batas HET (Harga Eceran Tertinggi).
     * Harga di atas HET menandakan potensi rawan/krisis harga.
     */
    private function determineHetStatus(?float $price): array
    {
        $het = $this->het !== null ? (float) $this->het : null;
        if ($het === null || $het <= 0 || $price === null) {
            return ['code' => 'aman', 'label' => 'Aman', 'color' => '#10b981', 'icon' => 'shield-check', 'rank' => 0];
        }

        // Harga masih di bawah / sama dengan HET → aman
        if ($price <= $het) {
            return ['code' => 'aman', 'label' => 'Aman', 'color' => '#10b981', 'icon' => 'shield-check', 'rank' => 0];
        }

        $overPercent = (($price - $het) / $het) * 100;

        if ($overPercent > 10) {
            return ['code' => 'kritis', 'label' => 'Kritis (Di atas HET)', 'color' => '#ef4444', 'icon' => 'shield-fill-x', 'rank' => 4];
        } elseif ($overPercent >= 5) {
            return ['code' => 'lonjakan', 'label' => 'Lonjakan (Di atas HET)', 'color' => '#f97316', 'icon' => 'exclamation-diamond', 'rank' => 3];
        } else {
            return ['code' => 'rawan', 'label' => 'Rawan (Di atas HET)', 'color' => '#f59e0b', 'icon' => 'exclamation-triangle', 'rank' => 2];
        }
    }

    /**
     * Pilih status dengan rank tertinggi (paling buruk).
     */
    private function pickWorstStatus(array $statuses): array
    {
        $worst = ['code' => 'aman', 'label' => 'Aman', 'color' => '#10b981', 'icon' => 'shield-check', 'rank' => 0];
        foreach ($statuses as $s) {
            if (($s['rank'] ?? 0) > ($worst['rank'] ?? 0)) {
                $worst = $s;
            }
        }
        // Hilangkan key rank dari hasil akhir
        unset($worst['rank']);
        return $worst;
    }

    /**
     * Status pangan berdasarkan kenaikan harga: Aman (tidak naik), Rawan (<5%), Lonjakan (>6%), Kritis (>10%)
     */
    private function determineFoodStatus(?float $changePercent): array
    {
        if ($changePercent === null || $changePercent == 0) {
            return [
                'code' => 'aman',
                'label' => 'Aman',
                'color' => '#10b981',
                'icon' => 'shield-check',
                'rank' => 0,
            ];
        }

        if ($changePercent > 10) {
            return ['code' => 'kritis', 'label' => 'Kritis', 'color' => '#ef4444', 'icon' => 'shield-fill-x', 'rank' => 4];
        } elseif ($changePercent >= 6) {
            return ['code' => 'lonjakan', 'label' => 'Lonjakan', 'color' => '#f97316', 'icon' => 'exclamation-diamond', 'rank' => 3];
        } elseif ($changePercent >= 0.01) {
            return ['code' => 'rawan', 'label' => 'Rawan', 'color' => '#f59e0b', 'icon' => 'exclamation-triangle', 'rank' => 2];
        } else {
            return ['code' => 'aman', 'label' => 'Aman', 'color' => '#10b981', 'icon' => 'shield-check', 'rank' => 0];
        }
    }
}
