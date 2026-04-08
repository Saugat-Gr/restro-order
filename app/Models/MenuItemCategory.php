<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'restaurant_id'];

    public static function  booted()
    {
        static::addGlobalScope(new TenantScope());
    }

    public function menuItems(){
         return $this->hasMany(MenuItem::class);
    }
}
