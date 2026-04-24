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

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $user = User::inRandomOrder()->first();
            $tables = Table::where('restaurant_id', $user->restaurant_id)->get();
            $menuItems = MenuItem::where('is_in_stock', true)->get();

            if ($menuItems->isEmpty()) {
                throw new \Exception('No menu items found');
            }

            // create 20 orders
            for ($i = 0; $i < 5; $i++) {

                $table = $tables->random()->first();

                $order = Order::create([
                    'restaurant_id' => $user->restaurant_id,
                    'table_id' => $table->id,
                    'created_by' => $user->id,
                    'status' => OrderStatus::IN_PROGRESS,
                    'total_amount' => 0,
                ]);

                $total = 0;

                // each order gets 1–5 items
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
                    ]);

                    $total += $subtotal;
                }

                $order->update([
                    'total_amount' => $total
                ]);
            }
        });
    }
}