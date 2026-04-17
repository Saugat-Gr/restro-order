<?php

namespace App\Services\SuperAdmin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;

class AnalyticsService
{

    public function getRevenueAnalytics($months = 6)
    {
        $now = Carbon::now();

        $total_revenue = Transaction::sum('amount');

        // Calculate start date
        $start = $now->copy()->subMonths($months - 1)->startOfMonth(); // Better for full months

        // Monthly Revenue (Last X months)
        $monthly = Transaction::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_key'),
            DB::raw('MONTHNAME(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(amount) as total')
        )
            ->where('created_at', '>=', $start)
            ->groupBy('month_key', 'month', 'year')
            ->orderBy('month_key')
            ->get();

        // Format labels nicely: "Jan 2025", "Feb 2025", etc.
        $monthlyLabels = $monthly->map(function ($item) {
            return $item->month . ' ' . $item->year;
        });

        // Weekly Revenue (Current week)
        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY); // Ensure Monday start
        $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);

        $weekRaw = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(fn($t) => $t->created_at->format('l'))
            ->map(fn($g) => $g->sum('amount'));

        $days = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);

        $weekly = $days->mapWithKeys(fn($day) => [
            $day => $weekRaw->get($day, 0)
        ]);

        return [
            'revenues' => [
                'revenue_value' => $total_revenue,

                'weekly' => [
                    'labels' => $weekly->keys()->values(),
                    'datasets' => [
                        [
                            'label' => 'This Week Revenue',
                            'data' => $weekly->values(),
                            'borderColor' => '#22c55e',
                            'backgroundColor' => 'rgba(34,197,94,0.2)',
                            'borderWidth' => 2,
                        ]
                    ]
                ],

                'monthly' => [
                    'labels' => $monthlyLabels,
                    'datasets' => [
                        [
                            'label' => "Last {$months} Months Revenue",
                            'data' => $monthly->pluck('total'),
                            'borderColor' => '#3b82f6',
                            'backgroundColor' => 'rgba(59,130,246,0.2)',
                            'borderWidth' => 2,
                        ]
                    ]
                ]
            ]
        ];
    }
    public function getPerformanceAnalytics()
    {
        $restaurants = Restaurant::select('id', 'name')->get();

        $completedMap = Order::where('status', OrderStatus::COMPLETED)
            ->select('restaurant_id', DB::raw('COUNT(*) as total'))
            ->groupBy('restaurant_id')
            ->pluck('total', 'restaurant_id');

        $cancelledMap = Order::where('status', OrderStatus::CANCELLED)
            ->select('restaurant_id', DB::raw('COUNT(*) as total'))
            ->groupBy('restaurant_id')
            ->pluck('total', 'restaurant_id');

        $labels = $restaurants->pluck('name');

        $completedData = $restaurants->map(fn($r) => $completedMap[$r->id] ?? 0);
        $cancelledData = $restaurants->map(fn($r) => $cancelledMap[$r->id] ?? 0);


        return [
            'performance' => [
                'labels' => $labels,
                'completed' => [
                    'datasets' => [
                        [
                            'label' => 'Completed Orders',
                            'data' => $completedData,
                            'backgroundColor' => '#22c55e',
                        ]
                    ]
                ],
                'cancelled' => [
                    'datasets' => [
                        [
                            'label' => 'Cancelled Orders',
                            'data' => $cancelledData,
                            'backgroundColor' => '#ef4444',
                        ]
                    ]
                ]
            ]
        ];
    }

    public function getNewRestaurantMetrics($months = 6)
    {
        $now = Carbon::now();

        // total restaurants ever created
        $total_restaurants = Restaurant::count();

        // start date for monthly stats
        $start = $now->copy()->subMonths($months - 1)->startOfMonth();

        // Monthly new restaurants
        $monthly = Restaurant::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_key'),
            DB::raw('MONTHNAME(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', $start)
            ->groupBy('month_key', 'month', 'year')
            ->orderBy('month_key')
            ->get();

        $monthlyLabels = $monthly->map(function ($item) {
            return $item->month . ' ' . $item->year;
        });

        // Weekly (current week)
        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);

        $weekRaw = Restaurant::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(fn($r) => $r->created_at->format('l'))
            ->map(fn($g) => $g->count());

        $days = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);

        $weekly = $days->mapWithKeys(fn($day) => [
            $day => $weekRaw->get($day, 0)
        ]);

        return [
            'new_restaurants' => [
                'total' => $total_restaurants,

                'weekly' => [
                    'labels' => $weekly->keys()->values(),
                    'datasets' => [
                        [
                            'label' => 'This Week New Restaurants',
                            'data' => $weekly->values(),
                            'borderColor' => '#f59e0b',
                            'backgroundColor' => 'rgba(245,158,11,0.2)',
                            'borderWidth' => 2,
                        ]
                    ]
                ],

                'monthly' => [
                    'labels' => $monthlyLabels,
                    'datasets' => [
                        [
                            'label' => "Last {$months} Months New Restaurants",
                            'data' => $monthly->pluck('total'),
                            'borderColor' => '#8b5cf6',
                            'backgroundColor' => 'rgba(139,92,246,0.2)',
                            'borderWidth' => 2,
                        ]
                    ]
                ]
            ]
        ];
    }

}