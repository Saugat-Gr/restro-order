<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use App\Services\AnalyticsService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalyticsController extends Controller
{

    public function __construct(protected AnalyticsService $analyticsService)
    {
        $this->middleware('permission:view-owner-analytics',['only' => ['index']]);
    }

    public function index(Request $request)
    {

        $months = $request->input('month', 6);

        return Inertia::render('Restaurant/Analytics/Index', [
            "app" => [
                "title" => "Analytics"
            ],
            "revenue_data" => $this->analyticsService->getRevenueAnalytics($request),
            'orders_data' => $this->analyticsService->getOrderAnalytics($request),
            "months" => $months
        ]);
    }
}
