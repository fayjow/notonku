<?php

$tests = [
    'ContentTest.php' => <<<'EOT'
<?php
use App\Models\Content;
use App\Enums\ContentType;
use App\Enums\ContentStatus;

it('can create content and cast enums', function () {
    $content = Content::factory()->create([
        'type' => ContentType::Movie,
        'status' => ContentStatus::Ongoing,
        'is_featured' => true,
    ]);
    
    expect($content->type)->toBe(ContentType::Movie)
        ->and($content->status)->toBe(ContentStatus::Ongoing)
        ->and($content->is_featured)->toBeTrue();
});
EOT,

    'RelationshipTest.php' => <<<'EOT'
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
EOT,

    'UserFeatureTest.php' => <<<'EOT'
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
EOT,

    'WatchHistoryServiceTest.php' => <<<'EOT'
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
EOT,

    'RatingTest.php' => <<<'EOT'
<?php
use App\Models\User;
use App\Models\Content;
use Illuminate\Database\UniqueConstraintViolationException;

it('user can rate content uniquely', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    
    $user->ratings()->create([
        'content_id' => $content->id,
        'rating' => 8,
    ]);
    
    expect($user->ratings)->toHaveCount(1)
        ->and($user->ratings->first()->rating)->toBe(8);
        
    $this->expectException(UniqueConstraintViolationException::class);
    $user->ratings()->create([
        'content_id' => $content->id,
        'rating' => 10,
    ]);
});
EOT,

    'CascadeDeleteTest.php' => <<<'EOT'
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
EOT,

    'PolymorphicRelationTest.php' => <<<'EOT'
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
EOT,
];

@mkdir(__DIR__ . '/tests/Feature/Database', 0777, true);

foreach ($tests as $file => $content) {
    file_put_contents(__DIR__ . '/tests/Feature/Database/' . $file, $content);
}

echo "Tests generated successfully.\n";
