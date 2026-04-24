<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Restaurant\CreateRequest;
use App\Models\Restaurant;
use App\Facade\SuperAdminRestaurantFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FacadeRestaurantController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-restaurants', ['only' => ['index']]);
        $this->middleware('permission:create-restaurant', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-restaurant', ['only' => ['edit', 'update']]);
        $this->middleware('permission:remove-restaurant', ['only' => ['destroy']]);


    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $status = $request->get('status', 'all');

        $restaurants = SuperAdminRestaurantFacade::getRestaurantWithOwner($status);


        return Inertia::render('SuperAdmin/Restaurant/Index', [
            "app" => [
                "title" => "Restaurants"
            ],
            "restaurants" => $restaurants
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        SuperAdminRestaurantFacade::createRestaurant($request);

        return redirect()->back()->with('success', 'Restaurant Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {


        return Inertia::render('SuperAdmin/Restaurant/Show', [
            "app" => [
                "title" => $restaurant->name
            ],
            "show_restaurant" => $restaurant->load('user')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        SuperAdminRestaurantFacade::updateRequest($request, $restaurant);

        return redirect()->back()->with('success', 'Restaurant Status Updated');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SuperAdminRestaurantFacade::removeRestaurant($id);

        return back()->with('success', 'Restaurant Removed');
    }
}
