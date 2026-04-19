<?php


namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class AnalyticsService
{
    public function getOrderAnalytics(Request $request)
    {
        $now = Carbon::now();
        $months = $request->input('month', 6);

        $total_orders = Order::count();

        $start = $now->copy()->subMonths($months - 1)->startOfMonth();

        // Monthly Orders
        $monthly = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_key'),
            DB::raw('MONTHNAME(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', $start)
            ->groupBy('month_key', 'month', 'year')
            ->orderBy('month_key')
            ->get();

        $monthlyLabels = $monthly->map(fn($m) => $m->month . ' ' . $m->year);

        // Weekly Orders (DB optimized)
        $weekRaw = Order::select(
            DB::raw('DAYNAME(created_at) as day'),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek()
            ])
            ->groupBy('day')
            ->pluck('total', 'day');

        $days = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);

        $weekly = $days->map(fn($day) => $weekRaw[$day] ?? 0);

        return [
            'orders' => [
                'total' => $total_orders,

                'weekly' => [
                    'labels' => $days,
                    'datasets' => [
                        [
                            'label' => 'Orders This Week',
                            'data' => $weekly,
                            'borderColor' => '#22c55e',
                        ]
                    ]
                ],

                'monthly' => [
                    'labels' => $monthlyLabels,
                    'datasets' => [
                        [
                            'label' => "Last {$months} Months Orders",
                            'data' => $monthly->pluck('total'),
                            'borderColor' => '#3b82f6',
                        ]
                    ]
                ]
            ]
        ];
    }
    public function getRevenueAnalytics(Request $request)
    {
        $now = Carbon::now();
        $months = $request->input('month', 6);

        $total_revenue = Transaction::sum('amount');

        $start = $now->copy()->subMonths($months - 1)->startOfMonth();

        // Monthly Revenue
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

        $monthlyLabels = $monthly->map(fn($m) => $m->month . ' ' . $m->year);

        // Weekly Revenue (DB optimized)
        $weekRaw = Transaction::select(
            DB::raw('DAYNAME(created_at) as day'),
            DB::raw('SUM(amount) as total')
        )
            ->whereBetween('created_at', [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek()
            ])
            ->groupBy('day')
            ->pluck('total', 'day');

        $days = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);

        $weekly = $days->map(fn($day) => $weekRaw[$day] ?? 0);

        return [
            'revenue' => [
                'total' => $total_revenue,

                'weekly' => [
                    'labels' => $days,
                    'datasets' => [
                        [
                            'label' => 'Revenue This Week',
                            'data' => $weekly,
                            'borderColor' => '#22c55e',
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
                        ]
                    ]
                ]
            ]
        ];
    }
}