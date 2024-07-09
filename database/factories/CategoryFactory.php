<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $allCategories = Category::all();
        $parentId =  count($allCategories) ? $allCategories->random() : null;

        $parent_id = $this->faker->boolean(25) ? $parentId : null;
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text(),
            'parent_id' => $parent_id,
        ];
    }
}
