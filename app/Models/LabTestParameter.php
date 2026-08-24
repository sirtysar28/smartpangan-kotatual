<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabTestParameter extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'mikotoksin' => ['label' => 'Uji Mikotoksin', 'icon' => 'bi-fungus', 'color' => '#a855f7'],
        'logam_berat' => ['label' => 'Uji Logam Berat', 'icon' => 'bi-hexagon-fill', 'color' => '#f59e0b'],
        'mikrobiologi' => ['label' => 'Uji Mikrobiologi', 'icon' => 'bi-virus', 'color' => '#ef4444'],
        'residu_pestisida' => ['label' => 'Uji Residu Pestisida', 'icon' => 'bi-droplet-half', 'color' => '#0ea5e9'],
    ];

    /** Parameter umum per kelompok uji (saran input) */
    public const PRESETS = [
        'mikotoksin' => ['Aflatoksin B1', 'Aflatoksin Total (B1+B2+G1+G2)', 'Ochratoxin A', 'Deoxynivalenol (DON)', 'Fumonisin', 'Zearalenone'],
        'logam_berat' => ['Timbal (Pb)', 'Kadmium (Cd)', 'Merkuri (Hg)', 'Arsen (As)', 'Timah (Sn)'],
        'mikrobiologi' => ['Angka Lempeng Total (ALT)', 'Escherichia coli', 'Salmonella spp.', 'Staphylococcus aureus', 'Bacillus cereus', 'Vibrio parahaemolyticus', 'Listeria monocytogenes', 'Kapang & Khamir'],
        'residu_pestisida' => [],
    ];

    protected $fillable = [
        'lab_test_id', 'test_category', 'parameter', 'result', 'value',
        'unit', 'standard', 'lod', 'loq', 'status', 'method',
    ];

    public function labTest(): BelongsTo
    {
        return $this->belongsTo(LabTest::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->test_category]['label'] ?? ucfirst(str_replace('_', ' ', $this->test_category));
    }

    public function getCategoryIconAttribute(): string
    {
        return self::CATEGORIES[$this->test_category]['icon'] ?? 'bi-clipboard-data';
    }

    public function getCategoryColorAttribute(): string
    {
        return self::CATEGORIES[$this->test_category]['color'] ?? '#64748b';
    }

    public function getResultLabelAttribute(): string
    {
        return $this->result === 'terdeteksi' ? 'Terdeteksi' : 'Tidak Terdeteksi';
    }

    public function getResultColorAttribute(): string
    {
        return $this->result === 'terdeteksi' ? '#ef4444' : '#10b981';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'MS' => 'MS (Memenuhi Syarat)',
            'TMS' => 'TMS (Tidak Memenuhi Syarat)',
            default => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'MS' => '#10b981',
            'TMS' => '#ef4444',
            default => '#64748b',
        };
    }
}
