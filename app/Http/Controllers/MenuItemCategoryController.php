<?php

namespace App\Http\Controllers;

use App\Models\MenuItemCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Log;

class MenuItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MenuItemCategory::withCount('menuItems')->latest()->get();

        return Inertia::render('Restaurant/Menu/Categories/Index', [
            'app' => [
                'title' => 'Menu Category',
            ],
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valdiated_data = $request->validate(
            [
                'name' => 'required|unique:menu_item_categories,name',
            ]
        );

        $valdiated_data['restaurant_id'] = auth()->user()->restaurant_id;

        MenuItemCategory::create($valdiated_data);

        return redirect()->route('menu.category')->with('success', 'Menu Category Created.');


    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItemCategory $menuItemCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItemCategory $menuItemCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuItemCategory $menuItemCategory)
    {
        $validated_data = $request->validate([
            'name' => 'required', Rule::unique('menu_item_categories', 'name')->ignore($menuItemCategory) ,
        ]);
        
         $updated = $menuItemCategory->update($validated_data);

        return redirect()->route('menu.category')->with('success', 'Menu Category Updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItemCategory $menuItemCategory)
    {

        if($menuItemCategory->menuItems()->count() > 0){
              return redirect()->back()->with('error', 'Cannot delete menu Category');
        }

         $menuItemCategory->delete();

         return redirect()->route('menu.category')->with('success', 'Category Deleted Successfully.');
    }
}
