<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'umkm_id', 'subtotal', 'shipping_cost',
        'discount_amount', 'total_amount', 'status', 'shipping_address',
        'shipping_courier', 'shipping_service', 'tracking_number',
        'payment_method', 'payment_type', 'payment_code',
        'midtrans_transaction_id', 'midtrans_order_id', 'midtrans_response',
        'paid_at', 'shipped_at', 'delivered_at', 'expired_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'midtrans_response' => 'array',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function umkm() { return $this->belongsTo(Umkm::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function reviews() { return $this->hasMany(Review::class); }

    public static function generateOrderNumber(): string
    {
        return 'TM-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'paid' => 'info',
            'processing' => 'primary',
            'shipped' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'expired' => 'secondary',
            default => 'secondary',
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Sedang Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Diterima',
            'cancelled' => 'Dibatalkan',
            'expired' => 'Kadaluarsa',
            default => ucfirst($this->status),
        };
    }
}
