<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address', 'avatar', 'status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function umkm() { return $this->hasOne(Umkm::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function notifications() { return $this->hasMany(UserNotification::class); }

    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isOpd(): bool { return $this->role === 'opd'; }
    public function isUmkm(): bool { return $this->role === 'umkm'; }
    public function isBuyer(): bool { return $this->role === 'buyer'; }
    public function isAdminLevel(): bool { return in_array($this->role, ['super_admin', 'opd']); }
}
