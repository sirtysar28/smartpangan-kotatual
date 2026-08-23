<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiInsight extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title', 'description', 'data', 'confidence_score', 'severity', 'is_active', 'expires_at'];

    protected function casts(): array
    {
        return ['data' => 'array', 'expires_at' => 'datetime'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }
}
