<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
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
            'SKU' => $this->faker->uuid(),
            'name' => $this->faker->words($this->faker->numberBetween(2, 3),true),
            'description' => $this->faker->text(),
            'price' => $this->faker->numberBetween(100, 100000),
            'published_at' => $this->faker->boolean(75) ? $this->faker->date() : null,
        ];
    }

    public function configure(): ProductFactory|Factory
    {
        return $this->afterCreating(function (Product $product) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $product->categories()->attach($categories);
        });
    }
}
