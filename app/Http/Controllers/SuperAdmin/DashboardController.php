<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

   protected DashboardService $dashboardService;

   public function __construct(DashboardService $dashboardService)
   {
      $this->dashboardService = $dashboardService;
   }

   public function index()
   {
      $this->dashboardService->getMetrics();
   }
}
