<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class, // required
            ProductSeeder::class,
            WarehouseSeeder::class,
            SupplierSeeder::class,
            ClientSeeder::class, // required
            PermissionSeeder::class, // required
            RoleSeeder::class, // required
            ProviderSeeder::class, // required
            PaymentSeeder::class // required
        ]);
    }
}
