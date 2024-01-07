<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();

        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->syncPermissions($permissions);

        $roleCashier = Role::create(['name' => 'cashier']);
        $roleCashier->syncPermissions('show products', 'show clients', 'show categories', 'show warehouses', 'create sales');
    }
}
