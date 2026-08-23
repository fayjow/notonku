<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class EpisodeFactory extends Factory {
    public function definition(): array {
        return [
            'episode_number' => fake()->numberBetween(1, 24),
            'is_published' => true,
            'published_at' => now(),
        ];
    }
}