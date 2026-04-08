<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Database\Factories\MenuItemFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuItem::factory(10)->create();
    }
}
