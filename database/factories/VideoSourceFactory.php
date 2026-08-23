<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class VideoSourceFactory extends Factory {
    public function definition(): array {
        return [
            'provider' => 'direct',
            'url' => 'https://example.com/video.mp4',
            'server_name' => 'Server 1',
            'is_active' => true,
        ];
    }
}