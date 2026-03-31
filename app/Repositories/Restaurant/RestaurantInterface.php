<?php

namespace App\Repositories\Restaurant;

use Illuminate\Http\Request;
interface RestaurantInterface
{
    public function createRestaurant(Request $request);
    public function getRestaurantById($id);
    public function updateRestaurant($id, array $data);
    public function deleteRestaurant($id);
}