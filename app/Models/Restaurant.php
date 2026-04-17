<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'address',
    'phone',
    'owner_id',
    'email',
    'logo',
    'status'
  ];


  public function user()
  {
    return $this->hasOne(User::class, 'restaurant_id');
  }

  public function orders(){
     return $this->hasMany(Order::class);
  }

  public function transactions()
{
    return $this->hasManyThrough(Transaction::class, Order::class);
}

}
