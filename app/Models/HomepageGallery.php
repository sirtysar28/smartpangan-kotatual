<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Galeri Kegiatan Beranda (Revisi 14 Okt 2026)
 * Foto kegiatan / dokumentasi yang tampil di beranda (frontend).
 * Dikelola dari Admin → Tampilan Beranda.
 */
class HomepageGallery extends Model
{
    protected $fillable = ['title', 'image', 'description', 'activity_date', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('activity_date')->orderByDesc('created_at');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? \Storage::url($this->image) : null;
    }
}
