<?php

namespace App\Models;

use App\Enums\Status;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'description',
        'image',
        'status',
        'price',
        'menu_item_category_id',
        'restaurant_id',
        'is_in_stock'
    ];

    protected $casts = [
        'is_in_stock' => 'boolean',
        'status' => Status::class
    ];

    public function menuItemCategory()
    {
        return $this->belongsTo(MenuItemCategory::class);
    }

    public static function  booted()
    {
        static::addGlobalScope(new TenantScope());
    }

}
