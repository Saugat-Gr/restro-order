<?php

namespace App\Services;

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


            return $this->menuItemRepo->createItem($validated_data);

        } catch (QueryException $e) {
            Log::error($e);
            return redirect()->route('menu.items.index');
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route('menu.items.index');

        }
    }


    public function updateItem(UpdateRequest $request, MenuItem $menuItem)
    {
        $validated_data = $request->validated();


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('restaurant/items/images', 'public');
            $validated_data['image'] = $path;
        }

        return $this->menuItemRepo->updateItem($validated_data, $menuItem);
    }
    public function destroy(MenuItem $item)
    {
        return $this->menuItemRepo->deleteItem($item);
    }

    public function getAll()
    {
        return $this->menuItemRepo->getAll();
    }

}