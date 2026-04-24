<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Http\Requests\Staffs\CreateRequest;
use App\Http\Requests\Staffs\UpdateRequest;
use App\Models\User;
use Hash;

class StaffService
{

    public function getStaffs()
    {
        return User::where('restaurant_id', auth()->user()->restaurant_id)->role(UserRole::STAFF)->paginate(10);
    }

    public function createStaff(CreateRequest $request)
    {
        $validated_data = $request->validated();
        $filePath = null;

        if ($request->hasFile('avatar')) {
            $filePath = $request->file('avatar')->store('user/avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $filePath,
            'restaurant_id' => auth()->user()->restaurant_id,
        ]);

        $user->assignRole(UserRole::STAFF->value);
    }


    public function updateStaff(UpdateRequest $request, string $id)
    {

        $user = $this->getStaff($id);

        $validated_data = $request->validated();

        $filePath = null;

        if ($request->hasFile('avatar')) {
            $filePath = $request->file('avatar')->store('user/avatars', 'public');
        }

        $user = User::findOrFail($id);

        if (!empty($validated_data['password'])) {
            $validated_data['password'] = Hash::make($validated_data['password']);
        } else {
            unset($validated_data['password']);
        }

        if ($request->hasFile('avatar')) {
            $validated_data['avatar'] = $request->file('avatar')->store('user/avatars', 'public');
        }

        $user->update($validated_data);
    }

    public function removeStaff(string $id)
    {
        $staff = $this->getStaff($id);

        $staff->delete();
    }


    public function getStaff(string $id)
    {
        return User::findOrFail($id);

    }



}