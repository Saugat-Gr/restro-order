<?php

namespace App\Facade;

use App\Services\SuperAdmin\RestaurantService;
use Illuminate\Support\Facades\Facade;

class SuperAdminRestaurantFacade extends Facade{

  protected static function getFacadeAccessor(){
     return RestaurantService::class;
  }

}