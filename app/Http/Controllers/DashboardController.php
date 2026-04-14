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
    }

    public function index()
    {
        $user = Auth::user();

        // For now we are building Owner only
        if ($user->hasRole('owner') && $user->restaurant_id) {
            $data = $this->dashboardService->getOwnerMetrics($user->restaurant_id);
            return Inertia::render('Dashboard/Owner', $data);
        }

        // Fallback (you can expand later for Super Admin / Staff)
        return Inertia::render('Dashboard/SuperAdmin');
    }
}