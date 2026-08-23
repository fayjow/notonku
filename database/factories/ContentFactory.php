<?php
namespace Database\Factories;
use App\Enums\ContentStatus;
use App\Enums\ContentType;
use Illuminate\Database\Eloquent\Factories\Factory;
class ContentFactory extends Factory {
    public function definition(): array {
        return [
            'type' => fake()->randomElement(ContentType::cases()),
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(ContentStatus::cases()),
            'is_published' => true,
            'published_at' => now(),
        ];
    }
}