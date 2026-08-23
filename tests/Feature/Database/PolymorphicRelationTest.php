<?php
use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;
use App\Models\VideoSource;

it('can assign video source to content', function () {
    $content = Content::factory()->create();
    $source = $content->videoSources()->create([
        'provider' => 'direct',
        'url' => 'http://example.com/movie.mp4',
        'server_name' => 'Main',
    ]);
    
    expect($content->videoSources)->toHaveCount(1)
        ->and($source->sourceable_type)->toBe('content'); // Tests MorphMap
});

it('can assign video source to episode', function () {
    $content = Content::factory()->create();
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id]);
    
    $source = $episode->videoSources()->create([
        'provider' => 'direct',
        'url' => 'http://example.com/episode.mp4',
        'server_name' => 'Main',
    ]);
    
    expect($episode->videoSources)->toHaveCount(1)
        ->and($source->sourceable_type)->toBe('episode');
});