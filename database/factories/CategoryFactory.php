<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
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
        return [
            'name' => $name = fake()->unique()->word(),
            'slug' => str($name)->slug(),
            'description' => fake()->sentence(),
            'color' => fake()->hexColor(),
        ];
    }
}
