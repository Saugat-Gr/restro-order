<?php

namespace App\Repositories\Restaurant;

interface RestaurantInterface
{
    public function createRestaurant($request);
    public function getRestaurantById($restaurant);
    public function updateRestaurant(array $data, $restaurant);
    public function deleteRestaurant($restaurant);
}