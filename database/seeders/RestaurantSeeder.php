<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Restaurant;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    private function getRandomImage(string $directory, string $default = null): ?string
    {
        $fullPath = storage_path("app/public/{$directory}");

        if (!is_dir($fullPath)) {
            return $default;
        }

        $files = array_filter(
            scandir($fullPath),
            fn($file) =>
            !in_array($file, ['.', '..']) && is_file($fullPath . '/' . $file)
        );

        if (empty($files)) {
            return $default;
        }

        $randomFile = $files[array_rand($files)];
        return "{$directory}/{$randomFile}";
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate([
            'email' => 'usere@gmail.com',
            'name' => "User E",
            'password' => Hash::make('password'),
            'status' => Status::ACTIVE->value,
            'created_at' => now()->subMonths(3),
        ]);

        $user->assignRole('owner');

        Restaurant::create([
            'email' => 'restaurante@gmail.com',
            'owner_id' => $user->id,
            'name' => 'Restaurant E',
            'phone' => '9812345678',
            'address' => 'Kathmandu, Nepal',
            'logo' => $this->getRandomImage('restaurants/logos'),
            'created_at' => now()->subMonths(3),
        ]);

    }
}
