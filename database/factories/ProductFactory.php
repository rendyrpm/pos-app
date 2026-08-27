<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        $buyPrice = fake()->randomFloat(2, 1000, 500000);
        return [
            'category_id' => \App\Models\Category::factory(),
            'sku' => fake()->unique()->bothify('SKU-####-????'),
            'barcode' => fake()->unique()->numerify('899##########'),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'buy_price' => $buyPrice,
            'sell_price' => $buyPrice * fake()->randomFloat(2, 1.1, 2.0),
            'stock' => fake()->numberBetween(0, 100),
            'unit' => fake()->randomElements(['pcs', 'kg', 'liter', 'box', 'pack'])[0],
            'min_stock' => fake()->numberBetween(5, 20),
            'is_active' => true,
        ];
    }
}
