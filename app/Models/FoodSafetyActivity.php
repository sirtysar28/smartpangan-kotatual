<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodSafetyActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_date', 'title', 'activity_type', 'description',
        'location', 'participants_count',
        'evidence_photo', 'evidence_photo_2', 'evidence_photo_3',
        'document_file', 'outcome', 'status', 'created_by'
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
        ];
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by');

    }

    public function getActivityTypeLabelAttribute()
    {
        $labels = [
            'pengawasan' => 'Pengawasan',
            'sosialisasi' => 'Sosialisasi',
            'pelatihan' => 'Pelatihan',
            'pengambilan_sampel' => 'Pengambilan Sampel',
            'penyuluhan' => 'Penyuluhan',
            'sidak' => 'SIDAK',
            'rapat_koordinasi' => 'Rapat Koordinasi',
            'lainnya' => 'Lainnya',
        ];
        return $labels[$this->activity_type] ?? '-';
    }

    public function getActivityTypeIconAttribute()
    {
        $icons = [
            'pengawasan' => 'shield-check',
            'sosialisasi' => 'megaphone',
            'pelatihan' => 'mortarboard',
            'pengambilan_sampel' => 'droplet',
            'penyuluhan' => 'book',
            'sidak' => 'search',
            'rapat_koordinasi' => 'people',
            'lainnya' => 'three-dots',
        ];
        return $icons[$this->activity_type] ?? 'activity';
    }

    public function getEvidencePhotoUrlAttribute()
    {
        return $this->evidence_photo ? \Storage::url($this->evidence_photo) : null;
    }
}
