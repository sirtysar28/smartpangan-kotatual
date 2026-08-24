<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabTest extends Model
{
    use HasFactory;

    /** Pilihan jenis PSAT */
    public const PSAT_TYPES = [
        'psat_sayuran' => 'PSAT - Sayuran',
        'psat_buah' => 'PSAT - Buah-buahan',
        'psat_umbi' => 'PSAT - Umbi-umbian',
        'psat_biji' => 'PSAT - Biji-bijian & Kacang-kacangan',
        'psat_rempah' => 'PSAT - Rempah & Tanaman Aromatik',
        'psah' => 'PSAH - Pangan Segar Asal Hewan (Daging, Susu, Telur)',
        'psai' => 'PSAI - Pangan Segar Asal Ikan',
        'olahan' => 'Pangan Olahan',
    ];

    protected $fillable = [
        'test_date', 'sample_location', 'lab_name', 'commodity_name',
        'psat_type', 'method', 'certificate_file', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'test_date' => 'date',
        ];
    }

    public function parameters(): HasMany
    {
        return $this->hasMany(LabTestParameter::class);
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }

    public function getPsatTypeLabelAttribute()
    {
        return self::PSAT_TYPES[$this->psat_type] ?? ucfirst(str_replace('_', ' ', $this->psat_type));
    }

    public function getCertificateFileUrlAttribute()
    {
        return $this->certificate_file ? \Storage::url($this->certificate_file) : null;
    }

    /** Status keseluruhan: TMS jika ada satu saja parameter TMS */
    public function getOverallStatusAttribute(): string
    {
        return $this->parameters->contains(fn($p) => $p->status === 'TMS') ? 'TMS' : 'MS';
    }

    public function getOverallStatusLabelAttribute(): string
    {
        return $this->overall_status === 'TMS' ? 'Tidak Memenuhi Syarat (TMS)' : 'Memenuhi Syarat (MS)';
    }

    public function getOverallStatusColorAttribute(): string
    {
        return $this->overall_status === 'TMS' ? '#ef4444' : '#10b981';
    }
}
