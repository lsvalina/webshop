<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->country(),
            'default_vat' => $this->faker->randomFloat(2, 0.17, 0.28)
        ];
    }

    public function configure(): ProductFactory|Factory
    {
        return $this->afterCreating(function (Country $country) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            foreach ($categories as $category) {
                $country->categories()->attach($category, [
                    'vat_percentage' => $this->faker->randomFloat(2, 0.0, $country->default_vat)
                ]);
            }
        });
    }
}
