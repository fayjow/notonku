<?php

use App\Models\Content;
use App\Models\User;
use App\Models\WatchHistory;

test('guest homepage does not query user history', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertDontSee('Continue Watching');
});

test('authenticated user sees continue watching', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create(['is_published' => true, 'published_at' => now()->subDay()]);
    
    WatchHistory::create([
        'user_id' => $user->id,
        'content_id' => $content->id,
        'progress_seconds' => 10,
        'duration_seconds' => 100,
        'is_completed' => false,
        'last_watched_at' => now(),
    ]);
    
    $this->actingAs($user)
        ->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Continue Watching')
        ->assertSee($content->title);
});

test('completed items are excluded from continue watching', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create(['is_published' => true, 'published_at' => now()->subDay()]);
    
    WatchHistory::create([
        'user_id' => $user->id,
        'content_id' => $content->id,
        'progress_seconds' => 100,
        'duration_seconds' => 100,
        'is_completed' => true,
        'last_watched_at' => now(),
    ]);
    
    $this->actingAs($user)
        ->get(route('home'))
        ->assertSuccessful()
        ->assertDontSee('Continue Watching');
});
