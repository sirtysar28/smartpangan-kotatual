<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapidTest extends Model
{
    use HasFactory;

    /** Pilihan jenis rapid test */
    public const TYPES = [
        'boraks' => 'Boraks',
        'formalin' => 'Formalin (Formaldehida)',
        'rhodamin_b' => 'Rhodamin B',
        'methanil_yellow' => 'Methanil Yellow',
        'karbit' => 'Karbit (Kalsium Karbida)',
        'pemutih_klorin' => 'Pemutih / Klorin (Bleaching Agent)',
        'pewarna_tekstil' => 'Pewarna Tekstil',
        'antibiotik' => 'Residu Antibiotik',
        'pestisida' => 'Residu Pestisida (Cepat)',
        'lainnya' => 'Lainnya',
    ];

    protected $fillable = [
        'test_date', 'sample_location', 'commodity_name', 'commodity_type',
        'rapid_test_type', 'result', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'test_date' => 'date',
        ];
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }

    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->rapid_test_type] ?? ucfirst(str_replace('_', ' ', $this->rapid_test_type));
    }

    public function getResultLabelAttribute()
    {
        return $this->result === 'positif' ? 'Positif (Terkontaminasi)' : 'Negatif (Aman)';
    }

    public function getResultColorAttribute()
    {
        return $this->result === 'positif' ? '#ef4444' : '#10b981';
    }
}
