<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'purchasable_id', 'purchasable_type',
        'name', 'price', 'quantity', 'size', 'color', 'line_total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function purchasable()
    {
        return $this->morphTo();
    }
}
