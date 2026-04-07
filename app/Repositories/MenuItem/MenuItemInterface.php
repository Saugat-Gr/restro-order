<?php


namespace App\Repositories\MenuItem;

use App\Models\MenuItem;

interface MenuItemInterface
{

    public function getAll();

    public function findById(MenuItem $menuItem);

    public function createItem($data);

    public function updateItem($data, $item);

    public function deleteItem(MenuItem $item);

}