<?php

namespace Database\Seeders;

use App\Enums\TransactionMethod;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\User;
use App\Models\Table;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatus;
use Carbon\Carbon;
use Str;

class OrderTransactionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $user = User::inRandomOrder()->first();
            $tables = Table::where('restaurant_id', $user->restaurant_id)->get();
            $menuItems = MenuItem::where('is_in_stock', true)->get();

            for ($i = 0; $i < 31; $i++) {

                $table = $tables->random()->first();

                // 👉 Random date within last 12 months
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

                // 2. Add items
                $total = 0;
                $items = $menuItems->random(rand(1, 5));

                foreach ($items as $item) {
                    $qty = rand(1, 3);
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

                // 3. Mark as completed (simulate your service logic)
                $completedAt = (clone $date)->addMinutes(rand(10, 120));

                $order->update([
                    'status' => OrderStatus::COMPLETED,
                    'updated_at' => $completedAt,
                ]);

                // free table (like your service)
                if ($order->table_id) {
                    $table->update([
                        'status' => \App\Enums\TableStatus::AVAILABLE,
                        'assigned_staff_id' => null,
                    ]);
                }

                // 4. Create transaction
                Transaction::create([
                    'restaurant_id' => $order->restaurant_id,
                    'order_id' => $order->id,
                    'processed_by' => $user->id,
                    'amount' => $total,
                    'payment_method' => fake()->randomElement(TransactionMethod::values()),
                    'paid_at' => $completedAt,
                    'created_at' => $completedAt,
                    'updated_at' => $completedAt,
                ]);
            }
        });
    }
}