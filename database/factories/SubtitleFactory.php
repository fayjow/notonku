<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class SubtitleFactory extends Factory {
    public function definition(): array {
        return [
            'language' => 'en',
            'label' => 'English',
            'file_path' => 'https://example.com/sub.vtt',
        ];
    }
}