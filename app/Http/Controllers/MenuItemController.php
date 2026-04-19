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
        $this->middleware('permission:view-menu-items', ['only' => ['index']]);
        $this->middleware('permission:create-menu-item', ['only' => ['create','store']]);
        $this->middleware('permission:update-menu-item', ['only' => ['edit', 'update']]);

    }

    /**
     * Display a listing of the resource.
     */
    // app/Http/Controllers/MenuItemController.php
    public function index(Request $request)
    {
        $menuItems = $this->menuItemService->getFiltered($request->all(), 10);

        return Inertia::render('Restaurant/Menu/Items/Index', [   // Use Inertia::render instead of inertia()
            "app" => ["title" => "Menu Items"],
            'menu_items' => $menuItems,
            'filters' => $request->only(['search', 'status', 'stock', 'perPage'])
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
            return redirect()->route('menu.menu-items.index')->with('success', 'Item Created Successfully.');
        } catch (Exception $e) {
            Log::info($e);
            return redirect()->route('menu.menu-items.index')->with('error', $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $menuItem)
    {
        return Inertia::render('Restaurant/Menu/Items/Show', [
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
            return redirect()->route('menu.menu-items.index')->with('success', 'Item Updated.');
        } catch (Exception $e) {
            Log::info($e);
            return redirect()->route('menu.menu-items.index')->with('error', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $menuItem)
    {
        try {
            $this->menuItemService->destroy($menuItem);
            return redirect()->route('menu.menu-items.index')->with('success', 'Item Removed Successfully');
        } catch (Exception $e) {
            Log::info($e);
        }
    }

}
