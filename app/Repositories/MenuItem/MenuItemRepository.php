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

    public function getAll()
    {
        return MenuItem::with('menuItemCategory')->get();
    }

    public function findById(MenuItem $menuItem)
    {
        return $menuItem;
    }
    public function getFiltered(array $filters = [], $perPage = 10)
    {

        $query = MenuItem::with('menuItemCategory');

        if (!empty($filters['search'] ?? '')) {
            $query->where(function ($q) use ($filters) {
                $q->where('item_name', 'LIKE', "%{$filters['search']}%")
                    ->orWhere('description', 'LIKE', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'] ?? '')) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['stock']) && $filters['stock'] !== '') {
            $query->where('is_in_stock', $filters['stock'] === 'in_stock' ? 1 : 0);
        }

        $perPage = (int) ($filters['perPage'] ?? $perPage);

        return $query
    ->orderBy('id', 'desc') // 🔥 REQUIRED
    ->paginate($perPage)
    ->withQueryString();
    }
}