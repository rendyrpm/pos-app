<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10000, 1000000);
        $discount = fake()->randomFloat(2, 0, $subtotal * 0.2);
        $total = $subtotal - $discount;
        $payment = ceil($total / 1000) * 1000;

        return [
            'transaction_number' => 'TRX-' . now()->format('Ymd') . '-' . fake()->unique()->numerify('#####'),
            'user_id' => \App\Models\User::factory(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'payment' => $payment,
            'change_amount' => $payment - $total,
            'status' => 'completed',
        ];
    }
}
