<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\TableStatus;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Table;
use App\Models\Transaction;           // ← new
use App\Repositories\Order\OrderInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Log;

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
            'staffs' => $this->repository->getStaffs()
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

            $menuItems = MenuItem::whereIn('id', collect($validated['items'])->pluck('menu_item_id'))
                ->get()->keyBy('id');

            $order = $this->repository->create([
                'restaurant_id' => auth()->user()->restaurant_id,
                'table_id' => $validated['table_id'] ?? null,
                'created_by' => auth()->id(),
                'status' => OrderStatus::IN_PROGRESS,
                'total_amount' => 0,
            ]);

            $table_id = $validated['table_id'];
            $staff_id = $validated['staff_id'] ?? auth()->user()->id;

            Table::findOrFail($table_id)->update([
                'status' => TableStatus::BOOKED,
                'assigned_staff_id' => $staff_id
            ]);

            $total = $this->syncOrderItems($order, $validated['items'], $menuItems);

            $this->repository->update($order, ['total_amount' => $total]);

            return $order->fresh(['orderItems', 'table', 'user']);
        });
    }

    public function updateOrder(Order $order, array $validated): Order
    {

        if ($order->status === OrderStatus::COMPLETED) {
            throw new \DomainException('Completed orders cannot be updated.');
        }

        return DB::transaction(function () use ($validated, $order) {

            $updates = [];

            // 1. Partial field updates (table + status)
            if (!empty($validated['table_id'])) {
                $updates['table_id'] = $validated['table_id'];
            }
            if (!empty($validated['status'])) {
                $updates['status'] = $validated['status'];
            }

            // 2. Rebuild items ONLY if 'items' key is explicitly sent (full edit form)
            $shouldRebuildItems = array_key_exists('items', $validated);

            if ($shouldRebuildItems) {
                $order->orderItems()->delete();   // safe because we're in transaction

                if (empty($validated['items'])) {
                    $updates['total_amount'] = 0;
                } else {
                    $menuItems = MenuItem::whereIn(
                        'id',
                        collect($validated['items'])->pluck('menu_item_id')
                    )->get()->keyBy('id');

                    $total = $this->syncOrderItems($order, $validated['items'], $menuItems);
                    $updates['total_amount'] = $total;
                }
            }

            // 3. CREATE TRANSACTION only when status becomes COMPLETED
            if (
                isset($validated['status']) &&
                $validated['status'] === OrderStatus::COMPLETED->value &&
                $order->status !== OrderStatus::COMPLETED
            ) {

                if ($order->table_id) {
                    $table = ($order->table_id);

                    Table::findOrFail($table)->update([
                        'status' => TableStatus::AVAILABLE,
                        'assigned_staff_id' => NULL,
                    ]);
                }

                $paymentMethod = $validated['payment_method'] ?? 'cash';

                Transaction::create([
                    'restaurant_id' => auth()->user()->restaurant_id,
                    'order_id' => $order->id,
                    'processed_by' => auth()->id(),
                    'amount' => $updates['total_amount'] ?? $order->total_amount,
                    'payment_method' => $paymentMethod,
                    'paid_at' => now(),
                ]);
            }

            // 4. Apply updates if any
            if (!empty($updates)) {
                $this->repository->update($order, $updates);
            }

            // return $order->fresh(['orderItems', 'table', 'user', 'transaction']);
            return $order->fresh(['orderItems', 'table', 'user']);

        });
    }

    /**
     * Extracted helper so create & update share the same logic
     */
    private function syncOrderItems(Order $order, array $itemsData, $menuItemsCollection): float
    {
        $total = 0;

        foreach ($itemsData as $item) {
            $menuItem = $menuItemsCollection[$item['menu_item_id']] ?? null;

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

        return $total;
    }
    public function searchOrders(?string $table = null, ?string $searchTerm = null, ?string $status = null)
    {
        return $this->repository->searchOrders($table, $searchTerm, $status);
    }
}