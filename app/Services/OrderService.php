<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\TableStatus;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Table;
use App\Repositories\Order\OrderInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderInterface $repository
    ) {
    }

    public function getRecentOrders(int $limit = 10)
    {
        return $this->repository->getRecentOrders($limit);
    }

    public function getDataForCreate(): array
    {
        return [
            'tables' => $this->repository->getAvailableTables(),
            'categories' => $this->repository->getActiveCategoriesWithMenuItems(),
        ];
    }

    public function getDataForEdit(Order $order): array
    {
        $order->load(['orderItems', 'table', 'user']);

        return [
            'order' => $order,
            'tables' => Table::where('status', TableStatus::AVAILABLE)
                ->orWhere('id', $order->table_id)
                ->get(),
            'categories' => $this->repository->getActiveCategoriesWithMenuItems(),
            'order_statuses' => OrderStatus::values(),
        ];
    }

    public function createOrder(array $validated): Order
    {
        return DB::transaction(function () use ($validated) {

            $menuItems = MenuItem::whereIn(
                'id',
                collect($validated['items'])->pluck('menu_item_id')
            )->get()->keyBy('id');

            $order = $this->repository->create([
                'restaurant_id' => auth()->user()->restaurant_id,
                'table_id' => $validated['table_id'],
                'created_by' => auth()->id(),
                'status' => OrderStatus::IN_PROGRESS,
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $menuItem = $menuItems[$item['menu_item_id']] ?? null;

                if (!$menuItem || !$menuItem->is_in_stock) {
                    throw new Exception("Item {$menuItem?->item_name} is out of stock");
                }

                $subtotal = $menuItem->price * $item['quantity'];

                $order->orderItems()->create([
                    'restaurant_id' => auth()->user()->restaurant_id,
                    'menu_item_id' => $menuItem->id,
                    'item_name' => $menuItem->item_name,
                    'item_price' => $menuItem->price,
                    'quantity' => $item['quantity'],
                ]);

                $total += $subtotal;
            }

            $this->repository->update($order, ['total_amount' => $total]);

            return $order;
        });
    }

    public function updateOrder(Order $order, array $validated): Order
    {
        if ($order->status !== OrderStatus::COMPLETED) {
            throw new \DomainException('Completed orders cannot be updated.');
        }

        return DB::transaction(function () use ($validated, $order) {

            $this->repository->update($order, [
                'table_id' => $validated['table_id'],
                'status' => $validated['status'],
            ]);

            // Remove old items
            $order->orderItems()->delete();

            if (empty($validated['items'])) {
                $order->update(['total_amount' => 0]);
                return $order->fresh(['orderItems', 'table', 'user']);
            }

            $menuItems = MenuItem::whereIn(
                'id',
                collect($validated['items'])->pluck('menu_item_id')
            )->get()->keyBy('id');

            $total = 0;

            foreach ($validated['items'] as $itemData) {
                $menuItem = $menuItems[$itemData['menu_item_id']] ?? null;

                if (!$menuItem || !$menuItem->is_in_stock) {
                    throw new Exception("Item {$menuItem?->item_name} is out of stock");
                }

                $subtotal = $menuItem->price * $itemData['quantity'];

                $order->orderItems()->create([
                    'restaurant_id' => auth()->user()->restaurant_id,
                    'menu_item_id' => $menuItem->id,
                    'item_name' => $menuItem->item_name,
                    'item_price' => $menuItem->price,
                    'quantity' => $itemData['quantity'],
                ]);

                $total += $subtotal;
            }

            $this->repository->update($order, ['total_amount' => $total]);

            return $order->fresh(['orderItems', 'table', 'user']);
        });


    }

    public function searchOrders(?string $table = null, ?string $searchTerm = null, ?string $status = null)
    {
        return $this->repository->searchOrders($table, $searchTerm, $status);
    }
}