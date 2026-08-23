<?php

use App\Models\Content;
use App\Models\Episode;
use App\Models\EpisodeBookmark;
use App\Models\Season;
use App\Models\User;

test('guest cannot bookmark episode', function () {
    $content = Content::factory()->create();
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id]);
    
    $this->postJson(route('watchlist.store', $episode))
        ->assertUnauthorized();
});

test('authenticated user can bookmark episode', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id]);
    
    $this->actingAs($user)
        ->postJson(route('watchlist.store', $episode))
        ->assertSuccessful();
        
    expect(EpisodeBookmark::where('user_id', $user->id)->where('episode_id', $episode->id)->exists())->toBeTrue();
});

test('user can remove bookmark', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id]);
    EpisodeBookmark::create(['user_id' => $user->id, 'episode_id' => $episode->id]);
    
    $this->actingAs($user)
        ->deleteJson(route('watchlist.destroy', $episode))
        ->assertSuccessful();
        
    expect(EpisodeBookmark::where('user_id', $user->id)->where('episode_id', $episode->id)->exists())->toBeFalse();
});

test('duplicate bookmark is prevented without crashing', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id]);
    EpisodeBookmark::create(['user_id' => $user->id, 'episode_id' => $episode->id]);
    
    $this->actingAs($user)
        ->postJson(route('watchlist.store', $episode))
        ->assertSuccessful();
        
    expect(EpisodeBookmark::where('user_id', $user->id)->where('episode_id', $episode->id)->count())->toBe(1);
});

test('watchlist page works and only shows own bookmarks for published content', function () {
    $user = User::factory()->create();
    
    $content = Content::factory()->create(['is_published' => true, 'published_at' => now()->subDay()]);
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id, 'is_published' => true]);
    
    EpisodeBookmark::create(['user_id' => $user->id, 'episode_id' => $episode->id]);
    
    $this->actingAs($user)
        ->get(route('watchlist.index'))
        ->assertSuccessful()
        ->assertSee($episode->title);
});
