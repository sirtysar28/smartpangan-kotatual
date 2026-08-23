<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommodityPrice extends Model
{
    use HasFactory;

    protected $fillable = ['commodity_id', 'price', 'price_previous', 'change_percent', 'source', 'price_date'];

    protected function casts(): array
    {
        return [
            'price_date' => 'date',
            'price' => 'decimal:2',
            'price_previous' => 'decimal:2',
            'change_percent' => 'decimal:2',
        ];
    }

    public function commodity() { return $this->belongsTo(Commodity::class); }
}
