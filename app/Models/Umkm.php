<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Umkm extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'business_name', 'slug', 'description', 'nib',
        'ktp_image', 'business_image', 'business_license', 'address',
        'city', 'province', 'postal_code', 'phone', 'whatsapp',
        'rating', 'total_sales', 'status', 'rejection_reason', 'verified_at',
    ];

    protected static function booted(): void
    {
        static::creating(function ($umkm) {
            $umkm->slug = $umkm->slug ?? Str::slug($umkm->business_name);
        });
    }

    protected function casts(): array
    {
        return ['verified_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function orders() { return $this->hasMany(Order::class); }

    public function isVerified(): bool { return $this->status === 'verified'; }
    public function scopeVerified($query) { return $query->where('status', 'verified'); }
    public function scopePending($query) { return $query->where('status', 'pending'); }
}
