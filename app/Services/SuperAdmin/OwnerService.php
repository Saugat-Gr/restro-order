<?php

namespace App\Services\SuperAdmin;

use App\Enums\UserRole;
use App\Events\SuperAdmin\Owner\OwnerRemoved;
use App\Http\Requests\SuperAdmin\Owner\UpdateRequest;
use App\Models\User;


class OwnerService
{

    public function getOwners($request)
    {
        $query = User::with('restaurant')
            ->role(UserRole::OWNER);

        if ($request->assigned === 'assigned') {
            $query->whereHas('restaurant');
        }

        if ($request->assigned === 'unassigned') {
            $query->whereDoesntHave('restaurant');
        }

        if ($request->status === 'active') {
            $query->where('status', 'active');
        }

        if ($request->status === 'inactive') {
            $query->where('status', 'inactive');
        }

        return $query->get();
    }

    public function getUnassignedOwners()
    {
        return User::role(UserRole::OWNER)->where('restaurant_id', null)->get();
    }

    public function updateOwnerData(UpdateRequest $request, string $id)
    {
        $validated_data = $request->validated();

        $user = User::findOrFail($id);

        $user->update($validated_data);
    }

    public function removeOwner(string $id)
    {
        $user = User::findOrFail($id);

        if (!empty($user->restaurant_id)) {
            return false;
        }

        event(new OwnerRemoved($user));
        $user->delete();
    }

}