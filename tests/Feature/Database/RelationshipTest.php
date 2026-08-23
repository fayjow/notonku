<?php
use App\Models\Content;
use App\Models\Genre;
use App\Models\Season;
use App\Models\Episode;

it('content can have genres', function () {
    $content = Content::factory()->create();
    $genre = Genre::factory()->create();
    
    $content->genres()->attach($genre);
    
    expect($content->genres)->toHaveCount(1)
        ->and($content->genres->first()->id)->toBe($genre->id);
});

it('content can have seasons and seasons have episodes', function () {
    $content = Content::factory()->create();
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id]);
    
    expect($content->seasons)->toHaveCount(1)
        ->and($season->episodes)->toHaveCount(1)
        ->and($episode->season->id)->toBe($season->id)
        ->and($episode->content->id)->toBe($content->id);
});