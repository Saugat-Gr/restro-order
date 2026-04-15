<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Bips Ale';
        $user->email = 'alestaff@example.com';
        $user->password = bcrypt('password');
        $user->restaurant_id = 1;

        $user->save();
        $user->assignRole('staff');
    }
}
