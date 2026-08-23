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