<?php

use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;

test('guest cannot access unpublished movie', function () {
    $content = Content::factory()->create(['type' => 'movie', 'is_published' => false]);
    $this->get(route('watch.movie', $content->slug))
        ->assertNotFound();
});

test('guest cannot access unpublished episode even if content is published', function () {
    $content = Content::factory()->create(['type' => 'series', 'is_published' => true, 'published_at' => now()->subDay()]);
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id, 'is_published' => false]);

    $this->get(route('watch.series', ['content' => $content->slug, 'episode' => $episode->id]))
        ->assertNotFound();
});

test('user cannot access episode belonging to different content', function () {
    $contentA = Content::factory()->create(['type' => 'series', 'is_published' => true, 'published_at' => now()->subDay()]);
    $seasonA = Season::factory()->create(['content_id' => $contentA->id]);
    $episodeA = Episode::factory()->create(['season_id' => $seasonA->id, 'is_published' => true, 'published_at' => now()->subDay()]);

    $contentB = Content::factory()->create(['type' => 'series', 'is_published' => true, 'published_at' => now()->subDay()]);

    // Requesting Episode A using Content B's slug
    $this->get(route('watch.series', ['content' => $contentB->slug, 'episode' => $episodeA->id]))
        ->assertNotFound();
});
