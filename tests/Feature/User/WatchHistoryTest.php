<?php

use App\Models\Content;
use App\Models\User;
use App\Models\WatchHistory;

test('guest cannot update history', function () {
    $this->postJson(route('watch-history.store'), [
        'content_id' => 1,
        'progress_seconds' => 10,
        'duration_seconds' => 100
    ])->assertUnauthorized();
});

test('authenticated user can save progress', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    
    $this->actingAs($user)
        ->postJson(route('watch-history.store'), [
            'content_id' => $content->id,
            'progress_seconds' => 50,
            'duration_seconds' => 100
        ])
        ->assertSuccessful();
        
    $history = WatchHistory::where('user_id', $user->id)->where('content_id', $content->id)->first();
    expect($history->progress_seconds)->toBe(50);
});

test('progress cannot exceed duration', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    
    $this->actingAs($user)
        ->postJson(route('watch-history.store'), [
            'content_id' => $content->id,
            'progress_seconds' => 150,
            'duration_seconds' => 100
        ])
        ->assertUnprocessable();
});

test('history page works and shows own history', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $content1 = Content::factory()->create();
    $content2 = Content::factory()->create();
    
    WatchHistory::create([
        'user_id' => $user1->id,
        'content_id' => $content1->id,
        'progress_seconds' => 10,
        'duration_seconds' => 100,
        'is_completed' => false,
        'last_watched_at' => now(),
    ]);
    
    WatchHistory::create([
        'user_id' => $user2->id,
        'content_id' => $content2->id,
        'progress_seconds' => 10,
        'duration_seconds' => 100,
        'is_completed' => false,
        'last_watched_at' => now(),
    ]);
    
    $this->actingAs($user1)
        ->get(route('history.index'))
        ->assertSuccessful()
        ->assertSee($content1->title)
        ->assertDontSee($content2->title);
});
