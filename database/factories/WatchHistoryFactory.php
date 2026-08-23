<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class WatchHistoryFactory extends Factory {
    public function definition(): array {
        return [
            'progress_seconds' => 100,
            'last_watched_at' => now(),
        ];
    }
}