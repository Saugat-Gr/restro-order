<?php


namespace App\Services\SuperAdmin;

use App\Enums\Status;
use App\Http\Requests\SuperAdmin\Restaurant\CreateRequest;
use App\Models\Restaurant;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RestaurantService
{

    public function getRestaurantWithOwner($status = 'all')
    {
        $query = DB::table('restaurants')
            ->leftJoin('users', 'users.restaurant_id', '=', 'restaurants.id')
            ->select('restaurants.*')
            ->selectSub(function ($query) {
                $query->from('users')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('users.restaurant_id', 'restaurants.id');
            }, 'user_count')
            ->selectSub(function ($q) {
                $q->from('users')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('users.restaurant_id', 'restaurants.id')
                    ->where('users.status', Status::ACTIVE);
            }, 'active_count')
            ->selectSub(function ($q) {
                $q->from('users')
                    ->select('name')
                    ->whereColumn('users.restaurant_id', 'restaurants.id')
                    ->limit(1);
            }, 'owner_name')
            ->groupBy('restaurants.id');

            if($status !== 'all'){
                 $query->where('restaurants.status', $status);
            }
           return $query->get();
    }

    public function createRestaurant(CreateRequest $request)
    {

        $validated_data = $request->validated();

        if ($request->hasFile('logo')) {
            $validated_data['logo'] = $request->file('logo')->store('restaurant/logos', 'public');
        }

        $user = User::findOrFail($validated_data['owner_id']);

        $restaurant = Restaurant::create($validated_data);

        $user->restaurant_id = $restaurant->id;
        $user->save();

    }

    public function updateRequest(Request $request, Restaurant $restaurant)
    {
        $validated_data = $request->validate([
            "status" => ['sometimes', Rule::in(Status::values())]
        ]);

        $restaurant->update($validated_data);
    }

}