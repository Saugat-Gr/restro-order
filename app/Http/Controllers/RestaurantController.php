<?php

namespace App\Http\Controllers;

use App\Http\Requests\Restaurant\CreateRequest;
use App\Http\Requests\Restaurant\UpdateRequest;
use App\Models\Restaurant;
use App\Services\RestaurantService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;

class RestaurantController extends Controller
{
    protected RestaurantService $restaurantService;


    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render(
            'Restaurant/Create',
            [
                'app' => [
                    'title' => 'Restaurant',
                ],
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        return $this->restaurantService->storeRestaurant($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        return Inertia::render(
            'Restaurant/Edit',
            [
                'app' => [
                    'title' => 'Edit Restaurant',
                ],
                'restaurant' => $restaurant,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Restaurant $restaurant)
    {
        Log::info($request->all());
        return $this->restaurantService->updateRestaurant($request, $restaurant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
