<?php

namespace App\Services\SuperAdmin;

use App\Models\Restaurant;
use Carbon\Carbon;


class DashboardService{

   public function getMetrics(){

    
      $total_restaurants = Restaurant::count();    

      
   }

}