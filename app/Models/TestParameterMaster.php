<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Master Parameter Uji (Revisi 14 Okt 2026)
 * Data master parameter uji laboratorium — terhubung ke form uji lab.
 * Menunggu kiriman data master resmi dari pihak ketahanan pangan;
 * sementara diisi ±8 parameter utama yang dipakai.
 */
class TestParameterMaster extends Model
{
    protected $fillable = ['name', 'category', 'unit', 'standard', 'method', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }

    public function getCategoryLabelAttribute(): string
    {
        return LabTestParameter::CATEGORIES[$this->category]['label']
            ?? match ($this->category) {
                'lainnya' => 'Lainnya',
                null, '' => 'Tanpa Kelompok (Opsional)',
                default => ucfirst(str_replace('_', ' ', $this->category)),
            };
    }

    public function getCategoryColorAttribute(): string
    {
        return LabTestParameter::CATEGORIES[$this->category]['color'] ?? '#64748b';
    }
}
