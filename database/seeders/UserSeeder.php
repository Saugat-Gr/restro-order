<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Enums\UserRole;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@saas.com',
            'avatar' => null,
            'email_verified_at' => now(),
            'status' => Status::ACTIVE->value,
            'password' => Hash::make('password'),
        ]);

        $owner = User::create([
            'name' => 'Restaurant Owner',
            'email' => 'owner@restaurant.com',
            'avatar' => null,
            'email_verified_at' => now(),
            'status' => Status::ACTIVE->value,
            'password' => Hash::make('password'),
        ]);

        $staff = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@restaurant.com',
            'avatar' => null,
            'email_verified_at' => now(),
            'status' => Status::ACTIVE->value,
            'password' => Hash::make('password'),
        ]);



        $superAdmin->assignRole(UserRole::SUPER_ADMIN->value);

        $owner->assignRole(UserRole::OWNER->value);

        $staff->assignRole(UserRole::STAFF->value);

    }

}
