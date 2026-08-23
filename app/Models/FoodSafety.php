<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodSafety extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_date', 'food_source_type', 'food_name', 'commodity_id',
        'distributor_name', 'retailer_name', 'retailer_address',
        'food_condition',
        'packaging_type', 'packaging_netto', 'expiry_date',
        'bpom_number', 'is_halal', 'halal_number', 'is_pduk', 'pduk_number',
        'inspection_result', 'recommendation', 'recommendation_photo',
        'inspection_type', 'inspector_name', 'notes', 'created_by'
    ];

    protected function casts(): array
    {
        return [
            'check_date' => 'date',
            'expiry_date' => 'date',
            'is_halal' => 'boolean',
            'is_pduk' => 'boolean',
        ];
    }

    public function commodity() { return $this->belongsTo(Commodity::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }

    public function getSourceTypeLabelAttribute()
    {
        return match($this->food_source_type) {
            'tumbuhan' => 'Asal Tumbuhan',
            'ikan' => 'Asal Ikan',
            'hewan' => 'Asal Hewan',
            default => '-',
        };
    }

    public function getSourceColorAttribute()
    {
        return match($this->food_source_type) {
            'tumbuhan' => '#10b981',
            'ikan' => '#0ea5e9',
            'hewan' => '#f59e0b',
            default => '#64748b',
        };
    }

    public function getFoodConditionLabelAttribute()
    {
        return match($this->food_condition) {
            'segar' => 'Pangan Segar',
            'segar_olahan_awal' => 'Pangan Segar Olahan Awal',
            default => '-',
        };
    }

    public function getInspectionResultLabelAttribute()
    {
        return match($this->inspection_result) {
            'layak' => 'Layak',
            'tidak_layak' => 'Tidak Layak',
            'perlu_tindak_lanjut' => 'Perlu Tindak Lanjut',
            default => '-',
        };
    }

    public function getInspectionResultColorAttribute()
    {
        return match($this->inspection_result) {
            'layak' => '#10b981',
            'tidak_layak' => '#ef4444',
            'perlu_tindak_lanjut' => '#f59e0b',
            default => '#64748b',
        };
    }

    public function getRecommendationPhotoUrlAttribute()
    {
        return $this->recommendation_photo ? \Storage::url($this->recommendation_photo) : null;
    }
}
