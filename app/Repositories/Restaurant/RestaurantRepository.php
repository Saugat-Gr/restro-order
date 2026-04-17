<?php

namespace App\Repositories\Restaurant;

use App\Repositories\Restaurant\RestaurantInterface;
use App\Models\Restaurant;
use Log;

class RestaurantRepository implements RestaurantInterface
{
    public function createRestaurant($request)
    {

        $restaurant = Restaurant::create($request);
        $user = auth()->user();
        $user->restaurant_id = $restaurant->id;
        $user->save();
        return $restaurant;
    }

    public function getRestaurantById($id)
    {
    }

    public function updateRestaurant($data, $restaurant)
    {
        $restaurant->update($data);
        return $restaurant;
    }

    public function deleteRestaurant($id)
    {
    }
}