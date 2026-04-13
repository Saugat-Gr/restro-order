<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "restaurant_id",
        "table_id",
        "created_by",
        "status",
        "total_amount",
    ];

    protected $casts = [
        "status" => OrderStatus::class,
    ];

    protected $appends = ['status_color'];
    public static function booted()
    {
        static::addGlobalScope(TenantScope::class);
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            OrderStatus::PENDING => 'warning',
            OrderStatus::IN_PROGRESS => 'info',
            OrderStatus::READY => 'primary',
            OrderStatus::SERVED => 'secondary',
            OrderStatus::COMPLETED => 'success',
            default => 'danger',
        };
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';                    // You can make this configurable
        $date = now()->format('Ymd');       // 20260410

        // Get the last order number for today
        $lastOrder = self::where('order_number', 'like', "{$prefix}-{$date}-%")
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            // Extract the last 4-digit sequence and increment
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Pad with zeros → ORD-20260410-0001
        return "{$prefix}-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

}
