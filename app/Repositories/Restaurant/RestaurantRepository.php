<?php

namespace App\Repositories\Restaurant;

use App\Repositories\Restaurant\RestaurantInterface;
use App\Models\Restaurant;

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
        // Implementation for retrieving a restaurant by ID
    }

    public function updateRestaurant($id, array $data)
    {
        // Implementation for updating a restaurant
    }

    public function deleteRestaurant($id)
    {
        // Implementation for deleting a restaurant
    }
}