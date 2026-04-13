<?php

namespace Database\Factories;
use App\Enums\TableStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use League\CommonMark\Extension\Table\TableSection;


class TableFactory extends Factory{

   public function definition(){
     return [
           "table_number" => fake()->words(2, true),
           "capacity" => fake()->numberBetween(5, 12),
           "restaurant_id" => 1,
           "status" => TableStatus::AVAILABLE,
     ];
   }

}