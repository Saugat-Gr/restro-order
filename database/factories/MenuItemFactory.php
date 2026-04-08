<?php

namespace Database\Factories;

use App\Models\MenuItemCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'image' => 'storage/restaurant/items/images/sCyIHSlYHXlihU8VdGWWYtPYhPmTmTjYQ73n6umS.jpg',
            'price' => fake()->numberBetween(100, 200),
            'is_in_stock' => fake()->boolean(),
            'menu_item_category_id' => MenuItemCategory::inRandomOrder()->first()->id,
            'restaurant_id' => 7,

        ];
    }
}
