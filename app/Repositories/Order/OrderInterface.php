<?php

namespace App\Repositories\Order;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderInterface
{
    public function getRecentOrders(int $limit = 10): Collection;

    public function findById(int $id): ?Order;

    public function create(array $data): Order;

    public function update(Order $order, array $data): Order;

    public function getAvailableTables();

    public function getActiveCategoriesWithMenuItems();

    public function searchOrders(?string $tableNumber = null, ?string $searchTerm = null, ?string $status = null): Collection;
}