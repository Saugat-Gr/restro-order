<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\User;
use App\Models\Table;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatus;
use App\Enums\TableStatus;
use Carbon\Carbon;

class OrderCancelledSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $user = User::inRandomOrder()->first();
            $tables = Table::where('restaurant_id', $user->restaurant_id)->get();
            $menuItems = MenuItem::where('is_in_stock', true)->get();

            for ($i = 0; $i < 12; $i++) {

                $table = $tables->random()->first();

                // Random date in last 12 months
                $date = Carbon::now()->subDays(rand(0, 365));

                // 1. Create order
                $order = Order::create([
                    'restaurant_id' => $user->restaurant_id,
                    'table_id' => $table->id,
                    'created_by' => $user->id,
                    'status' => OrderStatus::IN_PROGRESS,
                    'total_amount' => 0,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // simulate table booking (like createOrder)
                $table->update([
                    'status' => TableStatus::BOOKED,
                    'assigned_staff_id' => $user->id,
                ]);

                // 2. Add items
                $total = 0;
                $items = $menuItems->random(rand(1, 4));

                foreach ($items as $item) {

                    $qty = rand(1, 2);
                    $subtotal = $item->price * $qty;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'restaurant_id' => $order->restaurant_id,
                        'menu_item_id' => $item->id,
                        'item_name' => $item->item_name,
                        'item_price' => $item->price,
                        'quantity' => $qty,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    $total += $subtotal;
                }

                $order->update(['total_amount' => $total]);

                // 3. Cancel order
                $cancelledAt = (clone $date)->addMinutes(rand(5, 60));

                $order->update([
                    'status' => OrderStatus::CANCELLED,
                    'updated_at' => $cancelledAt,
                ]);

                // release table (like your service)
                if ($order->table_id) {
                    $table->update([
                        'status' => TableStatus::AVAILABLE,
                        'assigned_staff_id' => null,
                    ]);
                }

                // ❌ NO transaction created (important)
            }
        });
    }
}