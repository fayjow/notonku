<?php
use App\Models\User;
use App\Models\Content;
use App\Models\Season;
use App\Models\Episode;
use App\Models\Report;

it('deletes child models when content is deleted', function () {
    $content = Content::factory()->create();
    $season = Season::factory()->create(['content_id' => $content->id]);
    $episode = Episode::factory()->create(['season_id' => $season->id]);
    
    $content->delete();
    
    expect(Season::count())->toBe(0)
        ->and(Episode::count())->toBe(0);
});

it('nullifies user_id on report when user is deleted', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    
    $report = Report::create([
        'user_id' => $user->id,
        'reportable_id' => $content->id,
        'reportable_type' => 'content',
        'reason' => 'spam',
    ]);
    
    $user->delete();
    
    $report->refresh();
    expect($report->user_id)->toBeNull();
});