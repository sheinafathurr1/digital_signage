<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Content>
 */
class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'type' => 'text',
            'text_body' => fake()->paragraph(),
            'duration' => fake()->numberBetween(5, 20),
            'order' => 0,
            'is_active' => true,
            'is_priority' => false,
        ];
    }
}
