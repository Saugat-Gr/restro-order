<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\MenuItem\CreateRequest;
use App\Http\Requests\MenuItem\UpdateRequest;
use App\Models\MenuItem;
use App\Models\MenuItemCategory;
use App\Services\MenuItemService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;

class MenuItemController extends Controller
{
    protected MenuItemService $menuItemService;

    public function __construct(MenuItemService $menuItemService)
    {
        $this->menuItemService = $menuItemService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu_items = $this->menuItemService->getAll();

        return Inertia::render('Restaurant/Menu/Items/Index', [
            "app" => [
                'title' => 'Menu Items'
            ],

            'menu_items' => $menu_items

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menuCategories = MenuItemCategory::all();
        $statuses = Status::cases();
        return Inertia::render('Restaurant/Menu/Items/Create', [
            'app' => [
                'title' => 'Create Item'
            ],
            'categories' => $menuCategories,
            'statuses' => $statuses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        try {
            $this->menuItemService->createItem($request);
            return redirect()->route('menu.menu-items.index');
        } catch (Exception $e) {
            Log::info($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $menuItem)
    {
        return Inertia::render('Restaurant/Menu/Items/Show',[
             'app' => [
                 'title' => 'Details',
             ],
             'menu_item' => $menuItem
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItem $menuItem)
    {

        $categories = MenuItemCategory::all();
        $statuses = Status::cases();

        return Inertia::render(
            'Restaurant/Menu/Items/Edit',
            [
                'app' => [
                    'title' => 'Edit Item'
                ],
                'item' => $menuItem,
                'categories' => $categories,
                'statuses' => $statuses
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, MenuItem $menuItem)
    {
        try {

            $this->menuItemService->updateItem($request, $menuItem);
            return redirect()->route('menu.menu-items.index');
        }catch(Exception $e){
             Log::info($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $menuItem)
    {
         try{
        $this->menuItemService->destroy($menuItem);
        return redirect()->route('menu.menu-items.index');
         }catch(Exception $e){
              Log::info($e);
         }
    }

}
