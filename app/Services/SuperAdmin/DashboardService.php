<?php

namespace App\Services\SuperAdmin;

use Carbon\Carbon;


class DashboardService{

   public function getMetrics(){

      $now = Carbon::now();

      dd($now);
      
   }

}