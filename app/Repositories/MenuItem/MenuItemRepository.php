<?php

namespace App\Repositories\MenuItem;

use App\Models\MenuItem;
use App\Repositories\MenuItem\MenuItemInterface;
use Exception;
use Illuminate\Database\QueryException;
use Log;

class MenuItemRepository implements MenuItemInterface
{

    public function createItem($data)
    {

        try {
            $menu_item = MenuItem::create($data);
            return $menu_item;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }


    }

    public function updateItem($data, $item)
    {
        $item->update($data); 
        return redirect()->route('menu.menu-items.index');
    }
    public function deleteItem(MenuItem $item)
    {
        return $item->delete();
    }

}