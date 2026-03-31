<?php

namespace App\Repositories\Restaurant;

interface RestaurantInterface
{
    public function createRestaurant($request);
    public function getRestaurantById($id);
    public function updateRestaurant($id, array $data);
    public function deleteRestaurant($id);
}