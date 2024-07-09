<?php

namespace Database\Factories;

use App\Models\PriceList;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PriceList>
 */
class PriceListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words($this->faker->numberBetween(2, 3),true),
        ];
    }

    public function configure(): ProductFactory|Factory
    {
        return $this->afterCreating(function (PriceList $priceList) {
            $products = Product::inRandomOrder()->take(rand(25, 200))->get();
            foreach ($products as $product) {
                $priceList->products()->attach($product->SKU, [
                    'price' => $this->faker->numberBetween(
                        round($product->price * 0.7), round($product->price * 0.95)
                    )
                ]);
            }
        });
    }
}
