<?php

namespace App\Http\Controllers\SuperAdmin;


use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\AnalyticsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function __construct(protected AnalyticsService $analyticsService)
    {
    }
    public function index(Request $request)
    {

        $months = (int) $request->get('months', 6);

        return Inertia::render('SuperAdmin/Analytics/Index', [
            'app' => [
                'title' => 'Revenue Analytics'
            ],
            'selected_months' => $months,
            'revenue_data' => $this->analyticsService->getRevenueAnalytics($months),
            'performance_data' => $this->analyticsService->getPerformanceAnalytics(),
            'new_restaurant_data' => $this->analyticsService->getNewRestaurantMetrics($months)
        ]);
    }

}