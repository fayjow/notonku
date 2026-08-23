<?php

use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;
use App\Models\User;
use App\Models\VideoSource;
use App\Models\WatchHistory;

test('authenticated user saves history', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create(['type' => 'movie', 'is_published' => true, 'published_at' => now()->subDay()]);

    $this->actingAs($user)
        ->postJson(route('watch-history.store'), [
            'content_id' => $content->id,
            'progress_seconds' => 120,
            'duration_seconds' => 3600,
        ])->assertSuccessful();

    $this->assertDatabaseHas('watch_histories', [
        'user_id' => $user->id,
        'content_id' => $content->id,
        'progress_seconds' => 120,
        'is_completed' => false,
    ]);
});

test('progress cannot exceed duration in history service', function () {
    // Actually the logic in the view prevents sending progress > duration, but let's test the endpoint behavior.
    $user = User::factory()->create();
    $content = Content::factory()->create(['type' => 'movie', 'is_published' => true, 'published_at' => now()->subDay()]);

    $this->actingAs($user)
        ->postJson(route('watch-history.store'), [
            'content_id' => $content->id,
            'progress_seconds' => 4000, // exceeds
            'duration_seconds' => 3600,
        ])->assertStatus(422); // Expected 422 Unprocessable Entity
});

test('progress is isolated to the correct user', function () {
    $user1 = User::factory()->create();
    $content = Content::factory()->create(['type' => 'movie', 'is_published' => true, 'published_at' => now()->subDay()]);
    VideoSource::factory()->create(['sourceable_id' => $content->id, 'sourceable_type' => $content->getMorphClass()]);

    $this->actingAs($user1)
        ->postJson(route('watch-history.store'), [
            'content_id' => $content->id,
            'progress_seconds' => 500,
            'duration_seconds' => 3600,
        ])->assertSuccessful();

    $this->actingAs($user1)
        ->get(route('watch.movie', $content->slug))
        ->assertSuccessful()
        ->assertSee('resumeTime: 500', false);
});

test('progress is not visible to other users', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $content = Content::factory()->create(['type' => 'movie', 'is_published' => true, 'published_at' => now()->subDay()]);
    VideoSource::factory()->create(['sourceable_id' => $content->id, 'sourceable_type' => $content->getMorphClass()]);

    $this->actingAs($user1)
        ->postJson(route('watch-history.store'), [
            'content_id' => $content->id,
            'progress_seconds' => 500,
            'duration_seconds' => 3600,
        ])->assertSuccessful();

    $this->actingAs($user2)
        ->get(route('watch.movie', $content->slug))
        ->assertSuccessful()
        ->assertSee('resumeTime: 0', false);
});
