<?php

namespace App\Services;

use App\Http\Requests\Restaurant\CreateRequest;
use App\Http\Requests\Restaurant\UpdateRequest;
use App\Repositories\Restaurant\RestaurantInterface;
use Exception;
use Illuminate\Database\QueryException;
use Log;

class RestaurantService
{

    protected RestaurantInterface $restaurantRepository;

    public function __construct(RestaurantInterface $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
    }

    public function storeRestaurant(CreateRequest $request)
    {
        try {
            $validated_data = $request->validated();

            if ($request->hasFile('logo')) {
                $validated_data['logo'] = $request->file('logo')->store('restaurant/logos', 'public');
            }

            $this->restaurantRepository->createRestaurant($validated_data);

            return redirect()->back()->with('success', 'Restaurant Created.');
        } catch (QueryException $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error', 'Restaurant May Be Already Created.');
        } catch (Exception $e) {
            Log::error('Unexpected error creating restaurant: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could Not Create Restaurant.');
        }
    }

    public function updateRestaurant(UpdateRequest $request, $restaurant)
    {
        try {
            Log::info($request);
            $validated_data = $request->validated();
            if ($request->hasFile('logo')) {
                $validated_data['logo'] = $request->file('logo')->store('restaurant/logos', 'public');
            }
            $this->restaurantRepository->updateRestaurant($validated_data, $restaurant);
            return redirect()->route('dashboard');
        } catch (QueryException $e) {
            Log::error('Error updating restaurant: ' . $e->getMessage());
            return redirect()->route('welcome');
        } catch (Exception $e) {
            Log::error('Unexpected error updating restaurant: ' . $e->getMessage());
            return redirect()->route('welcome');
        }
    }

}