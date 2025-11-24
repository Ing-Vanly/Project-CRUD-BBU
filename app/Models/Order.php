<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'processing', 'completed', 'cancelled'];

    protected $fillable = [
        'order_number',
        'product_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'quantity',
        'unit_price',
        'total_amount',
        'status',
        'ordered_at',
        'notes',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });

        static::saving(function (Order $order) {
            if ($order->unit_price !== null && $order->quantity !== null) {
                $order->total_amount = round($order->unit_price * $order->quantity, 2);
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-' . now()->format('Ymd');
        $countForDay = self::whereDate('created_at', now()->toDateString())->count() + 1;

        do {
            $number = sprintf('%s-%04d', $prefix, $countForDay);
            $countForDay++;
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
