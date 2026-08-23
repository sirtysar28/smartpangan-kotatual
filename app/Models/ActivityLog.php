<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'module', 'description',
        'ip_address', 'user_agent', 'properties',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }

    public function scopeLatestFirst($query) { return $query->orderBy('created_at', 'desc'); }
}
