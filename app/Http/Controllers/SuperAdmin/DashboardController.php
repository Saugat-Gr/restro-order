<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{

   protected DashboardService $dashboardService;

   public function __construct(DashboardService $dashboardService)
   {
      $this->dashboardService = $dashboardService;
       $this->middleware('permission:view-super-admin-dashboard', ['only' => ['index']]);

   }

   public function index()
   {
       $data = $this->dashboardService->getMetrics();

      return Inertia::render('SuperAdmin/Dashboard/Index', 
      [
         ...$data,
          "app" => [
            "title" => "Super Dashboard"
          ]
      ]);
   }
}
