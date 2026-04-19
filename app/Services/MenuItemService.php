<?php

namespace App\Services;

use App\Events\Menu\Item\InStock;
use App\Events\Menu\Item\ItemAdded;
use App\Events\Menu\Item\ItemRemoved;
use App\Events\Menu\Item\OutOfStock;
use App\Http\Requests\MenuItem\CreateRequest;
use App\Http\Requests\MenuItem\UpdateRequest;
use App\Models\MenuItem;
use App\Repositories\MenuItem\MenuItemInterface;
use Exception;
use Illuminate\Database\QueryException;
use Log;

class MenuItemService
{

    protected MenuItemInterface $menuItemRepo;

    public function __construct(MenuItemInterface $menuItemRepo)
    {
        $this->menuItemRepo = $menuItemRepo;
    }


    public function getFiltered(array $filters = [], $perPage = 10)
    {
        return $this->menuItemRepo->getFiltered($filters, $perPage);
    }
    public function createItem(CreateRequest $request)
    {

        try {

            $validated_data = $request->validated();
            $filePath = null;

            if ($request->hasFile('image')) {
                $filePath = $request->file('image')
                    ->store('restaurant/items/images', 'public');
            }

            $validated_data['image'] = $filePath;
            $validated_data['restaurant_id'] = auth()->user()->restaurant_id;
            $validated_data['is_in_stock'] = true;


            $item = $this->menuItemRepo->createItem($validated_data);

            event(new ItemAdded($item));

            return $item;

        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function updateItem(UpdateRequest $request, MenuItem $menuItem)
    {
        $validated_data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('restaurant/items/images', 'public');
            $validated_data['image'] = $path;
        }

        $item = $this->menuItemRepo->updateItem($validated_data, $menuItem);


        if (isset($validated_data['is_in_stock']) && $validated_data['is_in_stock'] !== true) {
            event(new OutOfStock($item));
        }

         if (isset($validated_data['is_in_stock']) && $validated_data['is_in_stock'] === true) {
            event(new InStock($item));
        }

        return $item;
    }

    public function destroy(MenuItem $item)
    {
        $item =  $this->menuItemRepo->deleteItem($item);
        event(new ItemRemoved($item));
    }

    public function getAll()
    {
        return $this->menuItemRepo->getAll();
    }

}