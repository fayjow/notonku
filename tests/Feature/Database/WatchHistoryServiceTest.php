<?php
use App\Models\User;
use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;
use App\Services\WatchHistoryService;

it('updates progress for movie correctly', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    $service = new WatchHistoryService();
    
    // First update creates history
    $history = $service->updateProgress($user->id, $content->id, null, 100, 3600);
    expect($history->progress_seconds)->toBe(100)
        ->and($history->is_completed)->toBeFalse();
        
    // Second update updates history
    $history2 = $service->updateProgress($user->id, $content->id, null, 200, 3600);
    expect($history2->id)->toBe($history->id)
        ->and($history2->progress_seconds)->toBe(200);
});

it('marks as completed when progress is near duration', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    $service = new WatchHistoryService();
    
    $history = $service->updateProgress($user->id, $content->id, null, 3500, 3600);
    expect($history->is_completed)->toBeTrue();
});

it('updates progress for episode correctly', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id]);
    
    $service = new WatchHistoryService();
    
    $history = $service->updateProgress($user->id, $content->id, $episode->id, 500, 1800);
    expect($history->episode_id)->toBe($episode->id)
        ->and($history->progress_seconds)->toBe(500);
});