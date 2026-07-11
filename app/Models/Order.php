<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'recipient_name', 'address', 'phone',
        'courier', 'payment_method', 'subtotal', 'shipping_cost', 'total',
        'status', 'tracking_number', 'paid_at', 'shipped_at', 'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function (Order $order) {
            $order->order_number ??= 'MH-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public const STATUSES = ['pending', 'paid', 'process', 'shipped', 'delivered', 'cancelled'];
}
