<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class SeasonFactory extends Factory {
    public function definition(): array {
        return [
            'season_number' => fake()->numberBetween(1, 10),
        ];
    }
}