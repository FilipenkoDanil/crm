<?php

namespace Database\Seeders;

use App\Models\ProductWarehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        ProductWarehouse::factory(30)->create();
    }
}
