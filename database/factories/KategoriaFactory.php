<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kategoria>
 */
class KategoriaFactory extends Factory
{
    public function definition(): array
    {
        $nev = fake()->unique()->words(2, true);

        return [
            'nev' => $nev,
            'leiras' => fake()->sentence(),
            'icon' => 'heroicon-o-star',
            'szolgaltatas' => true,
            'slug' => (string) str($nev)->slug(),
        ];
    }
}
