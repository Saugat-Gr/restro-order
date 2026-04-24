<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\Status;
use App\Enums\TableStatus;
use App\Enums\TransactionMethod;
use App\Models\MenuItem;
use App\Models\MenuItemCategory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    private function randomTimestamp(Carbon $start, Carbon $end = null): Carbon
    {
        $end = $end ?? now();
        return Carbon::createFromTimestamp(mt_rand($start->timestamp, $end->timestamp));
    }

    private function getRandomImage(string $directory, string $default = null): ?string
    {
        $fullPath = storage_path("app/public/{$directory}");

        if (!is_dir($fullPath)) {
            return $default;
        }

        $files = array_filter(
            scandir($fullPath),
            fn($file) =>
            !in_array($file, ['.', '..']) && is_file($fullPath . '/' . $file)
        );

        if (empty($files)) {
            return $default;
        }

        $randomFile = $files[array_rand($files)];
        return "{$directory}/{$randomFile}";
    }

    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class
        ]);

        $this->command->info('Seeding started...');

        $startOfLastYear = now()->subYear()->startOfYear();

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'status' => Status::ACTIVE->value,
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subYears(2),
            ]
        );
        $superAdmin->assignRole('super-admin');

        $restaurantsData = [
            ['owner_name' => 'Bipin Ale', 'owner_email' => 'bipin@gmail.com', 'restaurant_name' => 'Spice Garden', 'restaurant_email' => 'spicegarden@gmail.com'],
            ['owner_name' => 'Love Sainju', 'owner_email' => 'lovesainju@gmail.com', 'restaurant_name' => 'Taco Topia', 'restaurant_email' => 'hello@tacotopia.com'],
            ['owner_name' => 'Ramesh Shrestha', 'owner_email' => 'ramesh@gmail.com', 'restaurant_name' => 'Himalayan Bites', 'restaurant_email' => 'himalayanbites@gmail.com'],
            ['owner_name' => 'Sita Gurung', 'owner_email' => 'sita.gurung@gmail.com', 'restaurant_name' => 'Kathmandu Kitchen', 'restaurant_email' => 'kathmandukitchen@gmail.com'],
        ];

        foreach ($restaurantsData as $data) {

            $owner = User::firstOrCreate(
                ['email' => $data['owner_email']],
                [
                    'name' => $data['owner_name'],
                    'password' => Hash::make('password'),
                    'status' => Status::ACTIVE->value,
                    'avatar' => $this->getRandomImage('user/avatars', 'user/avatars/default-avatar.jpg'),
                ]
            );

            $restaurant = Restaurant::firstOrCreate(
                ['email' => $data['restaurant_email']],
                [
                    'name' => $data['restaurant_name'],
                    'owner_id' => $owner->id,
                    'phone' => fake()->phoneNumber(),
                    'address' => fake()->address(),
                    'logo' => $this->getRandomImage('restaurant/logos', 'restaurant/logos/default-logo.jpg'),
                    'status' => Status::ACTIVE->value,
                ]
            );

            $owner->update(['restaurant_id' => $restaurant->id]);
            $owner->assignRole('owner');

            $staffMembers = [];
            for ($i = 1; $i <= rand(6, 8); $i++) {
                $staff = User::firstOrCreate(
                    [
                        'email' => strtolower(str_replace(' ', '', $data['restaurant_name'])) . $i . '@staff.com',
                    ],
                    [
                        'name' => fake()->name(),
                        'password' => Hash::make('password123'),
                        'status' => Status::ACTIVE->value,
                        'restaurant_id' => $restaurant->id,
                        'avatar' => $this->getRandomImage('user/avatars', 'user/avatars/default-avatar.jpg'),
                    ]
                );
                $staff->assignRole('staff');
                $staffMembers[] = $staff;
            }

            // ==================== Categories ====================
            $categoryNames = ['Appetizers', 'Main Courses', 'Desserts', 'Beverages'];
            $categories = [];

            foreach ($categoryNames as $name) {
                $categories[] = MenuItemCategory::create([
                    'restaurant_id' => $restaurant->id,
                    'name' => $name,
                ]);
            }

            $menuItems = [];

            $itemImages = [
                'Appetizers' => ['menu-items/appetizer1.jpg', 'menu-items/appetizer2.jpg'],
                'Main Courses' => ['menu-items/main1.jpg', 'menu-items/main2.jpg'],
                'Desserts' => ['menu-items/dessert1.jpg'],
                'Beverages' => ['menu-items/drink1.jpg', 'menu-items/drink2.jpg'],
            ];

            $menuNames = [
                'Appetizers' => ['Chicken Wings', 'Spring Rolls', 'Garlic Bread', 'French Fries'],
                'Main Courses' => ['Butter Chicken', 'Margherita Pizza', 'Pasta Carbonara', 'Grilled Salmon'],
                'Desserts' => ['Chocolate Cake', 'Vanilla Ice Cream', 'Cheesecake'],
                'Beverages' => ['Coca Cola', 'Fresh Orange Juice', 'Espresso Coffee', 'Lemon Tea'],
            ];

            for ($m = 0; $m < rand(25, 35); $m++) {
                $category = $categories[array_rand($categories)];
                $catName = $category->name;

                $names = $menuNames[$catName] ?? ['Special Item'];
                $randomName = $names[array_rand($names)];

                $imagePath = $this->getRandomImage(
                    "restaurant/items/images",
                    $itemImages[$catName][array_rand($itemImages[$catName] ?? [0])] ?? null
                );

                $menuItem = MenuItem::create([
                    'restaurant_id' => $restaurant->id,
                    'menu_item_category_id' => $category->id,
                    'item_name' => $randomName,
                    'description' => fake()->sentence(8),
                    'image' => $imagePath ?? 'restaurant/items/images/default.jpg',
                    'price' => fake()->randomFloat(2, 150, 1200), // NPR style
                    'status' => Status::ACTIVE->value,
                    'is_in_stock' => true,
                ]);

                $menuItems[] = $menuItem;
            }
            $tables = [];
            for ($t = 1; $t <= 10; $t++) {
                $tables[] = Table::firstOrCreate(
                    ['table_number' => "T{$t}",],
                    [
                        'capacity' => rand(2, 8),
                        'restaurant_id' => $restaurant->id,
                        'assigned_staff_id' => $staffMembers[array_rand($staffMembers)]->id,
                        'status' => fake()->randomElement(TableStatus::values()),
                    ]
                );
            }


            $this->command->info("{$data['restaurant_name']} seeded successfully");
        }

        $this->call([
            OrderSeeder::class,
            OrderTransactionSeeder::class,
            OrderCancelledSeeder::class,
        ]);
    }
}