<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommodityStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'commodity_id', 'monitoring_date', 'stock_quantity', 'stock_unit',
        'stock_duration_days', 'weekly_need', 'stock_status', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'monitoring_date' => 'date',
            'stock_quantity' => 'decimal:2',
            'weekly_need' => 'decimal:2',
        ];
    }

    public function commodity() { return $this->belongsTo(Commodity::class); }

    public function scopeLatest($query) {
        return $query->orderBy('monitoring_date', 'desc');
    }

    public function getStatusColorAttribute()
    {
        return match($this->stock_status) {
            'aman' => '#10b981',
            'rawan' => '#f59e0b',
            'kritis' => '#ef4444',
            default => '#64748b',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->stock_status) {
            'aman' => 'Aman',
            'rawan' => 'Rawan',
            'kritis' => 'Kritis',
            default => '-',
        };
    }
}
