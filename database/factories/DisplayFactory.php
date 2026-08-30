<?php

namespace Database\Factories;

use App\Models\Display;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Display>
 */
class DisplayFactory extends Factory
{
    protected $model = Display::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true).' Display',
            'location' => fake()->streetAddress(),
            'unique_code' => Display::generateUniqueCode(),
            'orientation' => 'landscape',
            'playlist_id' => null,
        ];
    }
}
