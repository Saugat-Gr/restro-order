<?php

namespace App\Models;

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
        'is_in_stock' => 'boolean'
    ];

    public function menuItemCategory()
    {
        return $this->belongsTo(MenuItemCategory::class);
    }

}
