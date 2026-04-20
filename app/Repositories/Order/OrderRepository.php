<?php

namespace App\Repositories\Order;

use App\Enums\Status;
use App\Enums\TableStatus;
use App\Models\MenuItemCategory;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderInterface
{
    public function getRecentOrders(int $limit = 10): Collection
    {
        return Order::with(['user', 'table'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function findById(int $id): ?Order
    {
        return Order::with(['orderItems', 'table', 'user'])->find($id);
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data): Order
    {
        $order->update($data);
        return $order->fresh();
    }

    public function getAvailableTables()
    {
        return Table::where('status', TableStatus::AVAILABLE)->get();
    }

    public function getActiveCategoriesWithMenuItems()
    {
        return MenuItemCategory::query()
            ->with([
                'menuItems' => function ($query) {
                    $query->select('id', 'menu_item_category_id', 'item_name', 'price', 'image', 'description', 'is_in_stock', 'status')
                        ->where('is_in_stock', true)
                        ->where('status', Status::ACTIVE);
                }
            ])
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    public function getStaffs(){
          return User::role('staff')->where('restaurant_id', auth()->user()->restaurant_id)->get();
    }

    public function searchOrders(?string $tableNumber = null, ?string $searchTerm = null, ?string $status = null)
    {
        return Order::with('table')
            ->when($tableNumber, function ($q) use ($tableNumber) {
                $q->whereHas('table', fn($q2) => $q2->where('table_number', $tableNumber));
            })
            ->when($searchTerm, function ($q) use ($searchTerm) {
                $q->where('order_number', 'LIKE', "%{$searchTerm}%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(15);
    }
}