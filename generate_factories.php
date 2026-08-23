<?php

$factoriesAndSeeders = [
    'ContentFactory.php' => <<<'EOT'
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
EOT,
    'GenreFactory.php' => <<<'EOT'
<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class GenreFactory extends Factory {
    public function definition(): array {
        return [
            'name' => fake()->unique()->word(),
            'slug' => fake()->unique()->slug(),
        ];
    }
}
EOT,
    'SeasonFactory.php' => <<<'EOT'
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
EOT,
    'EpisodeFactory.php' => <<<'EOT'
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
EOT,
    'VideoSourceFactory.php' => <<<'EOT'
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
EOT,
    'DownloadSourceFactory.php' => <<<'EOT'
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
EOT,
    'SubtitleFactory.php' => <<<'EOT'
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
EOT,
    'FavoriteFactory.php' => <<<'EOT'
<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class FavoriteFactory extends Factory {
    public function definition(): array {
        return [];
    }
}
EOT,
    'WatchlistFactory.php' => <<<'EOT'
<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class WatchlistFactory extends Factory {
    public function definition(): array {
        return [];
    }
}
EOT,
    'WatchHistoryFactory.php' => <<<'EOT'
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
EOT,
    'EpisodeBookmarkFactory.php' => <<<'EOT'
<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class EpisodeBookmarkFactory extends Factory {
    public function definition(): array {
        return [];
    }
}
EOT,
    'RatingFactory.php' => <<<'EOT'
<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class RatingFactory extends Factory {
    public function definition(): array {
        return [
            'rating' => fake()->numberBetween(1, 10),
        ];
    }
}
EOT,
];

foreach ($factoriesAndSeeders as $file => $content) {
    file_put_contents(__DIR__ . '/database/factories/' . $file, $content);
}

$seeder = <<<'EOT'
<?php
namespace Database\Seeders;
use App\Models\User;
use App\Models\Content;
use App\Models\Genre;
use App\Models\Season;
use App\Models\Episode;
use App\Enums\ContentType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Create Normal User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
        ]);

        // Create Genres
        $action = Genre::factory()->create(['name' => 'Action', 'slug' => 'action']);
        $drama = Genre::factory()->create(['name' => 'Drama', 'slug' => 'drama']);

        // Create Movie
        $movie = Content::factory()->create(['type' => ContentType::Movie]);
        $movie->genres()->attach([$action->id]);
        $movie->videoSources()->create([
            'provider' => 'direct',
            'url' => 'https://example.com/movie.mp4',
            'server_name' => 'Main Server',
        ]);

        // Create Series
        $series = Content::factory()->create(['type' => ContentType::Series]);
        $series->genres()->attach([$drama->id]);
        $season = Season::factory()->create(['content_id' => $series->id, 'season_number' => 1]);
        $episode = Episode::factory()->create(['season_id' => $season->id, 'episode_number' => 1]);
        $episode->videoSources()->create([
            'provider' => 'direct',
            'url' => 'https://example.com/episode.mp4',
            'server_name' => 'Main Server',
        ]);

        // Create Anime
        $anime = Content::factory()->create(['type' => ContentType::Anime]);
        $animeSeason = Season::factory()->create(['content_id' => $anime->id, 'season_number' => 1]);
        Episode::factory()->create(['season_id' => $animeSeason->id, 'episode_number' => 1]);

        // Create Donghua
        $donghua = Content::factory()->create(['type' => ContentType::Donghua]);
        $donghuaSeason = Season::factory()->create(['content_id' => $donghua->id, 'season_number' => 1]);
        Episode::factory()->create(['season_id' => $donghuaSeason->id, 'episode_number' => 1]);
    }
}
EOT;
file_put_contents(__DIR__ . '/database/seeders/DatabaseSeeder.php', $seeder);

echo "Factories and Seeder generated successfully.\n";
