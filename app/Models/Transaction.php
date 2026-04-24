<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'amount',
        'processed_by',
        'transaction_number',
        'paid_at',
        'order_id',
        'payment_method',
        'status',
    ];

    public static function booted()
    {
        static::addGlobalScope(TenantScope::class);

        static::creating(function ($model) {
            if (!$model->transaction_number) {
                $model->transaction_number = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}


