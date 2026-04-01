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

    public function updateRestaurant($data, $restaurant)
    {
        // Implementation for updating a restaurant
        $restaurant->update($data);
        return $restaurant;
    }

    public function deleteRestaurant($id)
    {
        // Implementation for deleting a restaurant
    }
}