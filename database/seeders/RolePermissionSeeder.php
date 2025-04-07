<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'cashier']);
        Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'admin-user']);
        Role::create(['name' => 'report-viewer']);


        Permission::create(['name' => 'view category']);
        Permission::create(['name' => 'delete category']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'create category']);

        Permission::create(['name' => 'view customer']);
        Permission::create(['name' => 'delete customer']);
        Permission::create(['name' => 'edit customer']);
        Permission::create(['name' => 'create customer']);

        Permission::create(['name' => 'view daily sales']);
        Permission::create(['name' => 'delete daily sales']);
        Permission::create(['name' => 'edit daily sales']);
        Permission::create(['name' => 'create daily sales']);

        Permission::create(['name' => 'view expense']);
        Permission::create(['name' => 'delete expense']);
        Permission::create(['name' => 'edit expense']);
        Permission::create(['name' => 'create expense']);

        Permission::create(['name' => 'view tax info']);
        Permission::create(['name' => 'delete tax info']);
        Permission::create(['name' => 'edit tax info']);
        Permission::create(['name' => 'create tax info']);

        Permission::create(['name' => 'view other income']);
        Permission::create(['name' => 'delete other income']);
        Permission::create(['name' => 'edit other income']);
        Permission::create(['name' => 'create other income']);

        Permission::create(['name' => 'view stock']);
        Permission::create(['name' => 'delete stock']);
        Permission::create(['name' => 'edit stock']);
        Permission::create(['name' => 'create stock']);

        Permission::create(['name' => 'view product sales report']);
        Permission::create(['name' => 'view sales report']);
        Permission::create(['name' => 'view summary(kilos/box) report']);
        Permission::create(['name' => 'view summary(wholesale/retail) report']);
        Permission::create(['name' => 'view sales on credit report']);
        Permission::create(['name' => 'view stock report']);

        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'create users']);
    }
}
