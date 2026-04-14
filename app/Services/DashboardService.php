<?php

namespace App\Services;

use App\Enums\TableStatus;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Table;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getOwnerMetrics(int $restaurantId)
    {
        $now = Carbon::now();

        // KPI Calculations
        $revenueToday = Transaction::whereDate('paid_at', $now->toDateString())
            ->where('status', 'completed')
            ->sum('amount');

        $revenueThisMonth = Transaction::
            whereMonth('paid_at', $now->month)
            ->whereYear('paid_at', $now->year)
            ->where('status', 'completed')
            ->sum('amount');

        $ordersToday = Order::where('restaurant_id', $restaurantId)
            ->whereDate('created_at', $now->toDateString())
            ->count();

        $totalCompletedOrders = Order::
            where('status', 'completed')
            ->count();

        $avgOrderValue = $totalCompletedOrders > 0
            ? Transaction::where('status', 'completed')
                ->avg('amount')
            : 0;

        // Sales Trend - Last 30 Days
        $salesTrend = Transaction::selectRaw('DATE(paid_at) as date, SUM(amount) as revenue')
            ->where('status', 'completed')
            ->where('paid_at', '>=', $now->copy()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 5 Menu Items by Revenue (this month)
        $topItems = OrderItem::select(
            'menu_items.item_name',
            DB::raw('SUM(order_items.quantity * order_items.item_price) as revenue'),
            DB::raw('SUM(order_items.quantity) as total_qty')
        )
            ->join('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->whereMonth('order_items.created_at', $now->month)
            ->whereYear('order_items.created_at', $now->year)
            ->groupBy('menu_items.id', 'menu_items.item_name')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();

        // Recent Orders
        $recentOrders = Order::with(['orderItems.menuItem', 'table'])
            ->latest()
            ->take(10)
            ->get();

        // Table Utilization
        $totalTables = Table::count();
        $occupiedTables = Table::where('status', TableStatus::BOOKED)
            ->count();

        return [
            'kpis' => [
                'revenue_today' => round($revenueToday, 2),
                'revenue_month' => round($revenueThisMonth, 2),
                'orders_today' => $ordersToday,
                'avg_order_value' => round($avgOrderValue, 2),
            ],
            'sales_trend' => [
                'labels' => $salesTrend->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d')),
                'datasets' => [
                    [
                        'label' => 'Daily Revenue',
                        'data' => $salesTrend->pluck('revenue'),
                        'borderColor' => '#4f46e5',
                        'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                        'tension' => 0.4,
                    ]
                ]
            ],
            'top_items' => $topItems,
            'recent_orders' => $recentOrders,
            'table_stats' => [
                'total' => $totalTables,
                'occupied' => $occupiedTables,
                'available' => $totalTables - $occupiedTables,
                'occupancy_rate' => $totalTables > 0 ? round(($occupiedTables / $totalTables) * 100, 1) : 0,
            ]
        ];
    }
}