<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(rand(1, 2)),
            'barcode' => $this->faker->ean13(),
            'code' => $this->faker->postcode(),
            'category_id' => $this->faker->numberBetween(1, 6),
            'purchase_price' => $this->faker->numberBetween(1, 10),
            'selling_price' => $this->faker->numberBetween(10, 20)
        ];
    }
}
