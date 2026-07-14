<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArlistaTetel>
 */
class ArlistaTetelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kategoria_id' => KategoriaFactory::new(),
            'muveletnev' => fake()->words(3, true),
            'ar' => (string) fake()->numberBetween(5000, 100000),
            'kiegeszites' => null,
        ];
    }
}
