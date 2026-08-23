<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class DownloadSourceFactory extends Factory {
    public function definition(): array {
        return [
            'provider' => 'direct',
            'url' => 'https://example.com/download.mp4',
            'server_name' => 'Download Server 1',
            'quality' => '1080p',
            'is_active' => true,
        ];
    }
}