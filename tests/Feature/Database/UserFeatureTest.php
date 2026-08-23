<?php
use App\Models\User;
use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;
use Illuminate\Database\UniqueConstraintViolationException;

it('user can favorite content and prevents duplicates', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    
    $user->favorites()->create(['content_id' => $content->id]);
    expect($user->favorites)->toHaveCount(1);
    
    $this->expectException(UniqueConstraintViolationException::class);
    $user->favorites()->create(['content_id' => $content->id]);
});

it('user can bookmark episodes and prevents duplicates', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id]);
    
    $user->episodeBookmarks()->create(['episode_id' => $episode->id]);
    expect($user->episodeBookmarks)->toHaveCount(1);
    
    $this->expectException(UniqueConstraintViolationException::class);
    $user->episodeBookmarks()->create(['episode_id' => $episode->id]);
});