<?php

namespace App\Services;

use App\Http\Requests\Restaurant\CreateRequest;
use App\Repositories\Restaurant\RestaurantInterface;
use Exception;
use Illuminate\Database\QueryException;
use Log;

class RestaurantService
{

    protected RestaurantInterface $restaurantRepository;

    public function __construct(RestaurantInterface $restaurantRepository)
    {
        $restaurant = $this->restaurantRepository = $restaurantRepository;
    }

    public function storeRestaurant(CreateRequest $request)
    {
        try {
            $validated_data = $request->validated();
            $validated_data['owner_id'] = auth()->id();

            $this->restaurantRepository->createRestaurant($validated_data);

            return redirect()->route('dashboard');
        } catch (QueryException $e) {
            Log::error('Error creating restaurant: ' . $e->getMessage());
            return redirect()->route('welcome');
        } catch (Exception $e) {
            Log::error('Unexpected error creating restaurant: ' . $e->getMessage());
            return redirect()->route('welcome');
        }
    }

}