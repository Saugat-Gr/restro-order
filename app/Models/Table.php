<?php

namespace App\Models;

use App\Enums\TableStatus;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'capacity',
        'restaurant_id',
        'assigned_staff_id',
        'status'
    ];

    protected $casts = [
        'status' => TableStatus::class,
    ];

    public static function booted()
    {
        static::addGlobalScope(new TenantScope());
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', TableStatus::AVAILABLE->value);
    }
}
