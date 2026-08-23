<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'umkm_id', 'category_id', 'name', 'slug', 'description',
        'price', 'discount_price', 'stock', 'weight', 'unit',
        'image', 'is_featured', 'is_active', 'rating',
        'total_sold', 'total_views', 'meta_data',
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            $product->slug = $product->slug ?? Str::slug($product->name) . '-' . Str::random(6);
        });
    }

    protected function casts(): array
    {
        return ['meta_data' => 'array'];
    }

    public function umkm() { return $this->belongsTo(Umkm::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(ProductImage::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function reviews() { return $this->hasMany(Review::class); }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return \Storage::url($this->image);
        }
        return null;
    }

    public function getEffectivePriceAttribute() { return $this->discount_price ?? $this->price; }
    public function getDiscountPercentAttribute()
    {
        if ($this->discount_price && $this->price > 0) {
            return round(($this->price - $this->discount_price) / $this->price * 100);
        }
        return 0;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }
    public function scopePopular($query) { return $query->orderBy('total_sold', 'desc'); }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }
}
