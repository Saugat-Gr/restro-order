<?php

namespace App\Services\SuperAdmin;

use App\Enums\OrderStatus;
use App\Enums\Status;
use App\Enums\TransactionStatus;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Transaction;
use Carbon\Carbon;


class DashboardService
{

   public function getMetrics()
   {

     $now = Carbon::now();

        $totalRestaurants = Restaurant::count();

        $activeRestaurants = Restaurant::where('status', Status::ACTIVE->value)->count();

        $totalRevenue = Transaction::where('transactions.status', TransactionStatus::COMPLETED)  
            ->sum('amount');

        $revenueThisMonth = Transaction::where('transactions.status', TransactionStatus::COMPLETED) 
            ->whereMonth('paid_at', $now->month)
            ->whereYear('paid_at', $now->year)
            ->sum('amount');

        $totalCompletedOrders = Order::
            where('orders.status', OrderStatus::COMPLETED)                 
            ->count();

        $newRestaurantsThisMonth = Restaurant::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $avgRevenuePerRestaurant = $totalRestaurants > 0 
            ? round($totalRevenue / $totalRestaurants, 2) 
            : 0;

        $revenueTrend = Transaction::
            selectRaw('DATE(paid_at) as date, SUM(amount) as revenue')
            ->where('transactions.status', TransactionStatus::COMPLETED)           
            ->where('paid_at', '>=', $now->copy()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $newRestaurantsTrend = Restaurant::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', $now->copy()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topRestaurants = Restaurant::select('id', 'name')
            ->withSum(['transactions' => function ($query) {
                $query->where('transactions.status', TransactionStatus::COMPLETED); 
            }], 'amount')
            ->orderByDesc('transactions_sum_amount')
            ->limit(10)
            ->get();

        $recentOrders = Order::
            with(['restaurant', 'table'])
            ->where('orders.status', OrderStatus::COMPLETED)                 
            ->latest()
            ->take(8)
            ->get();

        return [
            'kpis' => [
                'total_restaurants'          => $totalRestaurants,
                'active_restaurants'         => $activeRestaurants,
                'total_revenue'              => round($totalRevenue, 2),
                'revenue_this_month'         => round($revenueThisMonth, 2),
                'total_completed_orders'     => $totalCompletedOrders,
                'new_restaurants_this_month' => $newRestaurantsThisMonth,
                'avg_revenue_per_restaurant' => $avgRevenuePerRestaurant,
            ],

            'revenue_trend' => [
                'labels' => $revenueTrend->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d')),
                'datasets' => [
                    [
                        'label' => 'Platform Revenue',
                        'data' => $revenueTrend->pluck('revenue'),
                        'borderColor' => '#4f46e5',
                        'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                        'tension' => 0.4,
                    ]
                ]
            ],

            'new_restaurants_trend' => [
                'labels' => $newRestaurantsTrend->pluck('month'),
                'datasets' => [
                    [
                        'label' => 'New Restaurants',
                        'data' => $newRestaurantsTrend->pluck('count'),
                        'backgroundColor' => '#22c55e',
                    ]
                ]
            ],

            'top_restaurants' => $topRestaurants,
            'recent_orders'   => $recentOrders,
        ];
   }
}
