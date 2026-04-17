<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        $this->middleware('permission:view-dashboard', ['only' => ['index']]);
    }

    public function index()
    {
        $user = Auth::user();

        // For now we are building Owner only
        if ($user->hasRole('owner') && $user->restaurant_id) {
            $data = $this->dashboardService->getOwnerMetrics($user->restaurant_id);
            return Inertia::render('Dashboard/Owner', [
                ...$data,

                "app" => [
                    "title" => 'Dashboard'
                ]

            ]);
        }
        return Inertia::render('Restaurant/Orders/Create');
    }
}