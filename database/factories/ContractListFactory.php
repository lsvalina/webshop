<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContractList>
 */
class ContractListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'SKU' => $product->SKU,
            'price' => $this->faker->numberBetween(100, $product->price),
        ];
    }
}
