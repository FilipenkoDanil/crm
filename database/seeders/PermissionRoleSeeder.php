<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productPermissions = [
            'show products',
            'create products',
            'edit products',
            'delete products'
        ];

        $categoryPermissions = [
            'show categories',
            'create categories',
            'edit categories',
            'delete categories'
        ];

        $warehousePermissions = [
            'show warehouses',
            'create warehouses',
            'edit warehouses',
            'delete warehouses',
            'edit productWarehouses',
        ];

        $clientPermissions = [
            'show clients',
            'create clients',
            'edit clients',
            'delete clients'
        ];

        $supplierPermissions = [
            'show suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers'
        ];

        $purchasePermissions = [
            'show purchases',
            'create purchases',
            'edit purchases',
        ];

        $salePermissions = [
            'show sales',
            'create sales'
        ];

        $trashPermissions = [
            'show trash',
            'restore trash'
        ];

        $allPerm = array_merge(
            $productPermissions,
            $categoryPermissions,
            $warehousePermissions,
            $clientPermissions,
            $supplierPermissions,
            $purchasePermissions,
            $salePermissions,
            $trashPermissions
        );

        foreach ($allPerm as $perm) {
            Permission::create([
                'name' => $perm
            ]);
        }

        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->syncPermissions($allPerm);
        User::find(1)->assignRole($roleAdmin);

        $roleCashier = Role::create(['name' => 'cashier']);
        $roleCashier->syncPermissions('show products', 'show clients', 'show categories', 'show warehouses', 'create sales');
        User::find(2)->assignRole($roleCashier);
    }
}
