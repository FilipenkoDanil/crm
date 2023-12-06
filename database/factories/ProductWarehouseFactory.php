<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductWarehouse>
 */
class ProductWarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'warehouse_id' => rand(1, 2),
            'product_id' => $this->faker->unique()->numberBetween(1, 30),
            'stock' => rand(1, 10),
        ];
    }
}
