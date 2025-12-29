<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
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
        $totalAmount = $this->faker->randomFloat(2, 50, 1000);
        $discount = $this->faker->randomFloat(2, 0, $totalAmount * 0.2);
        $paidAmount = $totalAmount - $discount;

        return [
            'customer_id' => Customer::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'discount' => $discount,
        ];
    }

    public function withoutCustomer()
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => null,
        ]);
    }
}
