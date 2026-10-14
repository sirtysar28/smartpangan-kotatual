<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_number', 'nib', 'umkm_id', 'business_name', 'applicant_name', 'applicant_phone',
        'business_address', 'kecamatan', 'desa', 'business_category', 'food_products',
        'permit_type', 'permit_status', 'issue_date', 'expiry_date',
        'notes', 'document_file', 'nib_document', 'approved_by', 'approved_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function umkm() { return $this->belongsTo(Umkm::class); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }

    public function getPermitStatusLabelAttribute()
    {
        return match($this->permit_status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'expired' => 'Kadaluarsa',
            default => ucfirst($this->permit_status ?? '-'),
        };
    }

    public function getPermitStatusColorAttribute()
    {
        return match($this->permit_status) {
            'pending' => '#f59e0b',
            'approved' => '#10b981',
            'rejected' => '#ef4444',
            'expired' => '#64748b',
            default => '#64748b',
        };
    }

    public function getPermitStatusIconAttribute()
    {
        return match($this->permit_status) {
            'pending' => 'hourglass-split',
            'approved' => 'patch-check-fill',
            'rejected' => 'x-circle-fill',
            'expired' => 'clock-history',
            default => 'dash-circle',
        };
    }

    public function getPermitTypeLabelAttribute()
    {
        return match($this->permit_type) {
            'pduk' => 'PDUK (Perizinan Usaha Pangan)',
            'bpom' => 'BPOM (Badan POM)',
            'halal' => 'Halal (BPJPH)',
            'sp_irt' => 'SP-IRT (Izin Rumah Tangga)',
            'lainnya' => 'Lainnya',
            default => ucfirst($this->permit_type ?? '-'),
        };
    }

    public function getDocumentFileUrlAttribute()
    {
        return $this->document_file ? \Storage::url($this->document_file) : null;
    }

    public function getNibDocumentUrlAttribute()
    {
        return $this->nib_document ? \Storage::url($this->nib_document) : null;
    }

    public function scopePending($query) { return $query->where('permit_status', 'pending'); }
    public function scopeApproved($query) { return $query->where('permit_status', 'approved'); }
    public function scopeRejected($query) { return $query->where('permit_status', 'rejected'); }
}
