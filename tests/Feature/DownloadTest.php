<?php

use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;
use App\Models\User;
use App\Models\VideoSource;

it('allows downloading a downloadable mp4 movie source', function () {
    $movie = Content::factory()->create([
        'type' => 'movie',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    $source = VideoSource::factory()->create([
        'sourceable_id' => $movie->id,
        'sourceable_type' => 'content',
        'provider' => 'mp4',
        'is_downloadable' => true,
        'is_active' => true,
        'url' => 'https://example.com/video.mp4',
    ]);

    $response = $this->get(route('watch.download.movie', ['content' => $movie->slug, 'source' => $source->id]));

    $response->assertRedirect('https://example.com/video.mp4');
});

it('prevents downloading an hls source even if is_downloadable is true', function () {
    $movie = Content::factory()->create([
        'type' => 'movie',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    $source = VideoSource::factory()->create([
        'sourceable_id' => $movie->id,
        'sourceable_type' => 'content',
        'provider' => 'hls',
        'is_downloadable' => true,
        'is_active' => true,
        'url' => 'https://example.com/video.m3u8',
    ]);

    $response = $this->get(route('watch.download.movie', ['content' => $movie->slug, 'source' => $source->id]));

    $response->assertNotFound();
});

it('prevents downloading an inactive source', function () {
    $movie = Content::factory()->create([
        'type' => 'movie',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    $source = VideoSource::factory()->create([
        'sourceable_id' => $movie->id,
        'sourceable_type' => 'content',
        'provider' => 'mp4',
        'is_downloadable' => true,
        'is_active' => false, // inactive
        'url' => 'https://example.com/video.mp4',
    ]);

    $response = $this->get(route('watch.download.movie', ['content' => $movie->slug, 'source' => $source->id]));

    $response->assertNotFound();
});

it('prevents downloading an unpublished movie', function () {
    $movie = Content::factory()->create([
        'type' => 'movie',
        'is_published' => false,
    ]);

    $source = VideoSource::factory()->create([
        'sourceable_id' => $movie->id,
        'sourceable_type' => 'content',
        'provider' => 'mp4',
        'is_downloadable' => true,
        'is_active' => true,
        'url' => 'https://example.com/video.mp4',
    ]);

    $response = $this->get(route('watch.download.movie', ['content' => $movie->slug, 'source' => $source->id]));

    $response->assertNotFound();
});

it('prevents IDOR by validating sourceable_id', function () {
    $movie1 = Content::factory()->create(['type' => 'movie', 'is_published' => true, 'published_at' => now()->subDay()]);
    $movie2 = Content::factory()->create(['type' => 'movie', 'is_published' => true, 'published_at' => now()->subDay()]);

    $source = VideoSource::factory()->create([
        'sourceable_id' => $movie2->id, // belongs to movie2
        'sourceable_type' => 'content',
        'provider' => 'mp4',
        'is_downloadable' => true,
        'is_active' => true,
        'url' => 'https://example.com/video.mp4',
    ]);

    // Requesting movie1 with movie2's source
    $response = $this->get(route('watch.download.movie', ['content' => $movie1->slug, 'source' => $source->id]));

    $response->assertNotFound();
});

it('allows downloading a downloadable mp4 episode source', function () {
    $series = Content::factory()->create([
        'type' => 'series',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);
    $season = Season::factory()->create(['content_id' => $series->id]);
    $episode = Episode::factory()->create([
        'season_id' => $season->id,
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    $source = VideoSource::factory()->create([
        'sourceable_id' => $episode->id,
        'sourceable_type' => 'episode',
        'provider' => 'mp4',
        'is_downloadable' => true,
        'is_active' => true,
        'url' => 'https://example.com/episode.mp4',
    ]);

    $response = $this->get(route('watch.download.series', ['content' => $series->slug, 'episode' => $episode->id, 'source' => $source->id]));

    $response->assertRedirect('https://example.com/episode.mp4');
});
