<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolePermissionSeeder extends Seeder
{

    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $owner = Role::firstOrCreate(['name' => 'owner']);
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);

        $permissions = [

            // Dashboard
            "view-dashboard",

            // Categories
            "view-categories",
            "create-category",
            "update-category",
            "delete-category",

            // Menu Items
            "view-menu-items",
            "create-menu-item",
            "update-menu-item",
            "delete-menu-item",

            // Tables
            "view-tables",
            "create-table",
            "update-table",
            "delete-table",

            // Orders
            "view-orders",
            "create-order",
            "update-order",
            "complete-order", // IMPORTANT

            // Transactions
            "view-transactions",

            // App Settings
            "update-app-settings",
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // OWNER → full control
        $owner->syncPermissions($permissions);

        // STAFF → limited control
        $staff->syncPermissions([
            "view-dashboard",
            "view-menu-items",
            "view-tables",
            "view-orders",
            "create-order",
            "update-order",
            "complete-order"
        ]);


    }
}
