<?php

namespace Database\Seeders;

use App\Models\Table;
use Database\Factories\MenuItemFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Table::factory(40)->create();
    }
}
