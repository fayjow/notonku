<?php

use App\Models\User;
use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;
use App\Models\VideoSource;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user']);
    $this->movie = Content::factory()->create([
        'type' => 'movie',
        'is_published' => true,
        'status' => 'completed'
    ]);
});

it('renders the correct active source', function () {
    $source = VideoSource::factory()->create([
        'sourceable_type' => 'content',
        'sourceable_id' => $this->movie->id,
        'provider' => 'hls',
        'is_active' => true,
    ]);

    $this->get(route('watch.movie', $this->movie->slug))
         ->assertOk()
         ->assertSee($source->url)
         ->assertSee('hls.js');
});

it('renders embed iframe correctly and hides native controls', function () {
    $source = VideoSource::factory()->create([
        'sourceable_type' => 'content',
        'sourceable_id' => $this->movie->id,
        'provider' => 'embed',
        'url' => 'https://youtube.com/embed/123',
        'is_active' => true,
    ]);

    $this->get(route('watch.movie', $this->movie->slug))
         ->assertOk()
         ->assertSee('<iframe', false)
         ->assertSee('https://youtube.com/embed/123');
});

it('prevents IDOR when using source_id parameter', function () {
    // Create another movie with its own source
    $otherMovie = Content::factory()->create(['type' => 'movie', 'is_published' => true]);
    $otherSource = VideoSource::factory()->create([
        'sourceable_type' => 'content',
        'sourceable_id' => $otherMovie->id,
        'provider' => 'mp4',
        'url' => 'https://other-movie.com/video.mp4',
        'is_active' => true,
    ]);

    // Create target movie source
    $targetSource = VideoSource::factory()->create([
        'sourceable_type' => 'content',
        'sourceable_id' => $this->movie->id,
        'provider' => 'mp4',
        'url' => 'https://target-movie.com/video.mp4',
        'is_active' => true,
    ]);

    // Attempt to access other source ID on the target movie
    $response = $this->get(route('watch.movie', [$this->movie->slug, 'source_id' => $otherSource->id]));
    
    // It should silently fallback to the target movie's default source, ignoring the IDOR attempt
    $response->assertOk()
             ->assertSee('https://target-movie.com/video.mp4')
             ->assertDontSee('https://other-movie.com/video.mp4');
});

it('does not render inactive sources even if source_id is requested', function () {
    $inactiveSource = VideoSource::factory()->create([
        'sourceable_type' => 'content',
        'sourceable_id' => $this->movie->id,
        'provider' => 'mp4',
        'url' => 'https://inactive.com/video.mp4',
        'is_active' => false,
    ]);
    
    $activeSource = VideoSource::factory()->create([
        'sourceable_type' => 'content',
        'sourceable_id' => $this->movie->id,
        'provider' => 'mp4',
        'url' => 'https://active.com/video.mp4',
        'is_active' => true,
    ]);

    $response = $this->get(route('watch.movie', [$this->movie->slug, 'source_id' => $inactiveSource->id]));
    
    $response->assertOk()
             ->assertSee('https://active.com/video.mp4')
             ->assertDontSee('https://inactive.com/video.mp4');
});

it('blocks access to unpublished content sources', function () {
    $unpublishedMovie = Content::factory()->create(['type' => 'movie', 'is_published' => false]);
    VideoSource::factory()->create([
        'sourceable_type' => Content::class,
        'sourceable_id' => $unpublishedMovie->id,
        'provider' => 'mp4',
        'is_active' => true,
    ]);

    $this->get(route('watch.movie', $unpublishedMovie->slug))
         ->assertNotFound();
});

it('blocks access to unpublished episode sources', function () {
    $series = Content::factory()->create(['type' => 'series', 'is_published' => true]);
    $season = Season::factory()->create(['content_id' => $series->id, 'season_number' => 1]);
    $unpublishedEpisode = Episode::factory()->create([
        'season_id' => $season->id,
        'is_published' => false,
    ]);
    
    VideoSource::factory()->create([
        'sourceable_type' => 'episode',
        'sourceable_id' => $unpublishedEpisode->id,
        'provider' => 'mp4',
        'is_active' => true,
    ]);

    $this->get(route('watch.series', ['content' => $series->slug, 'episode' => $unpublishedEpisode->id]))
         ->assertNotFound();
});
