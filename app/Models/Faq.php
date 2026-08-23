<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'category', 'question', 'answer', 'items',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'items'     => 'array',
        'is_active' => 'boolean',
    ];

    // ===== Scopes =====
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeNib($q) { return $q->where('category', 'nib'); }
    public function scopePsat($q) { return $q->where('category', 'psat_pduk'); }
    public function scopeOrdered($q) { return $q->orderBy('sort_order')->orderBy('id'); }

    // ===== Labels =====
    public function getCategoryLabelAttribute()
    {
        return match ($this->category) {
            'nib'       => 'NIB',
            'psat_pduk' => 'PSAT-PDUK',
            default     => ucfirst($this->category),
        };
    }

    public function getCategoryColorAttribute()
    {
        return match ($this->category) {
            'nib'       => '#8b5cf6',
            'psat_pduk' => '#0ea5e9',
            default     => '#64748b',
        };
    }

    public function getCategoryIconAttribute()
    {
        return match ($this->category) {
            'nib'       => 'card-checklist',
            'psat_pduk' => 'leaf',
            default     => 'patch-question',
        };
    }
}
