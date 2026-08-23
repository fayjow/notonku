<?php

use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;
use App\Models\VideoSource;

test('guest can access video player for published movie', function () {
    $content = Content::factory()->create(['type' => 'movie', 'is_published' => true, 'published_at' => now()->subDay()]);
    VideoSource::factory()->create(['sourceable_id' => $content->id, 'sourceable_type' => Content::class]);

    $this->get(route('watch.movie', $content->slug))
        ->assertSuccessful()
        ->assertSee($content->title);
});

test('guest can access video player for published series episode', function () {
    $content = Content::factory()->create(['type' => 'series', 'is_published' => true, 'published_at' => now()->subDay()]);
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id, 'is_published' => true, 'published_at' => now()->subDay()]);
    VideoSource::factory()->create(['sourceable_id' => $episode->id, 'sourceable_type' => Episode::class]);

    $this->get(route('watch.series', ['content' => $content->slug, 'episode' => $episode->id]))
        ->assertSuccessful()
        ->assertSee($content->title);
});

test('empty video source does not crash the player page', function () {
    $content = Content::factory()->create(['type' => 'movie', 'is_published' => true, 'published_at' => now()->subDay()]);
    // Note: No VideoSource created

    $this->get(route('watch.movie', $content->slug))
        ->assertSuccessful()
        ->assertSee('Video Source Unavailable');
});

test('invalid content type returns 404', function () {
    $content = Content::factory()->create(['type' => 'series', 'is_published' => true, 'published_at' => now()->subDay()]);
    
    // Requesting a series through the movie route
    $this->get(route('watch.movie', $content->slug))
        ->assertNotFound();
});
