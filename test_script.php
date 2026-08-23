<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Content;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\Artisan;

Artisan::call('migrate:fresh');

$user = User::factory()->create();
$content = Content::factory()->create(['is_published' => true]);

$history = WatchHistory::create([
    'user_id' => $user->id,
    'content_id' => $content->id,
    'progress_seconds' => 100,
    'duration_seconds' => 100,
    'is_completed' => true,
    'last_watched_at' => now(),
]);

print_r($user->watchHistories()->where('is_completed', false)->get()->toArray());
