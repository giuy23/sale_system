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
        $purchase_price = fake()->randomFloat(2,5,80);

        return [
            'name' => fake()->name(),
            'description' => fake()->text(5),
            'purchase_price' => $purchase_price,
            'sale_price' => $purchase_price + fake()->numberBetween(1,3),
            'bar_code' => fake()->numerify('#############'),
            'quantity' => fake()->numberBetween(15,35),
            'minimum_quantity' => fake()->numberBetween(2,10),
            'state' => fake()->boolean(),
        ];
    }
}
