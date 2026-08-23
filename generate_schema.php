<?php

// Migrations
$migrations = [
    '2026_08_18_160653_create_contents_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('contents', function (Blueprint \$table) {
            \$table->id();
            \$table->string('type');
            \$table->string('title')->index();
            \$table->string('slug')->unique();
            \$table->string('original_title')->nullable();
            \$table->text('description')->nullable();
            \$table->string('poster_path')->nullable();
            \$table->string('backdrop_path')->nullable();
            \$table->date('release_date')->nullable();
            \$table->string('status');
            \$table->integer('duration_minutes')->nullable();
            \$table->string('age_rating')->nullable();
            \$table->decimal('average_rating', 4, 2)->default(0);
            \$table->unsignedBigInteger('ratings_count')->default(0);
            \$table->unsignedBigInteger('views_count')->default(0);
            \$table->boolean('is_featured')->default(false);
            \$table->boolean('is_published')->default(false);
            \$table->timestamp('published_at')->nullable();
            \$table->timestamps();
            \$table->index(['type', 'is_published']);
        });
    }
    public function down(): void { Schema::dropIfExists('contents'); }
};
EOT,

    '2026_08_18_160654_create_genres_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('genres', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('genres'); }
};
EOT,

    '2026_08_18_160655_create_content_genre_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('content_genre', function (Blueprint \$table) {
            \$table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            \$table->foreignId('genre_id')->constrained('genres')->cascadeOnDelete();
            \$table->primary(['content_id', 'genre_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('content_genre'); }
};
EOT,

    '2026_08_18_160656_create_seasons_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('seasons', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            \$table->integer('season_number');
            \$table->string('title')->nullable();
            \$table->text('description')->nullable();
            \$table->string('poster_path')->nullable();
            \$table->timestamps();
            \$table->unique(['content_id', 'season_number']);
        });
    }
    public function down(): void { Schema::dropIfExists('seasons'); }
};
EOT,

    '2026_08_18_160657_create_episodes_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('episodes', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            \$table->integer('episode_number');
            \$table->string('title')->nullable();
            \$table->text('description')->nullable();
            \$table->string('thumbnail_path')->nullable();
            \$table->integer('duration_minutes')->nullable();
            \$table->date('release_date')->nullable();
            \$table->boolean('is_published')->default(false);
            \$table->timestamp('published_at')->nullable();
            \$table->timestamps();
            \$table->unique(['season_id', 'episode_number']);
            \$table->index(['season_id', 'is_published', 'episode_number'], 'episodes_season_pub_ep_num_idx');
        });
    }
    public function down(): void { Schema::dropIfExists('episodes'); }
};
EOT,

    '2026_08_18_160658_create_video_sources_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('video_sources', function (Blueprint \$table) {
            \$table->id();
            \$table->unsignedBigInteger('sourceable_id');
            \$table->string('sourceable_type');
            \$table->string('provider');
            \$table->text('url');
            \$table->string('quality')->nullable();
            \$table->string('server_name');
            \$table->string('language')->nullable();
            \$table->integer('priority')->default(0);
            \$table->boolean('is_active')->default(true);
            \$table->timestamps();
            \$table->index(['sourceable_type', 'sourceable_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('video_sources'); }
};
EOT,

    '2026_08_18_160659_create_download_sources_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('download_sources', function (Blueprint \$table) {
            \$table->id();
            \$table->unsignedBigInteger('sourceable_id');
            \$table->string('sourceable_type');
            \$table->string('provider');
            \$table->text('url');
            \$table->string('quality');
            \$table->string('server_name');
            \$table->unsignedBigInteger('file_size_bytes')->nullable();
            \$table->integer('priority')->default(0);
            \$table->boolean('is_active')->default(true);
            \$table->timestamps();
            \$table->index(['sourceable_type', 'sourceable_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('download_sources'); }
};
EOT,

    '2026_08_18_160700_create_subtitles_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('subtitles', function (Blueprint \$table) {
            \$table->id();
            \$table->unsignedBigInteger('sourceable_id');
            \$table->string('sourceable_type');
            \$table->string('language');
            \$table->string('label');
            \$table->text('file_path');
            \$table->timestamps();
            \$table->index(['sourceable_type', 'sourceable_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('subtitles'); }
};
EOT,

    '2026_08_18_160701_create_favorites_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('favorites', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            \$table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            \$table->timestamps();
            \$table->unique(['user_id', 'content_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('favorites'); }
};
EOT,

    '2026_08_18_160702_create_watchlists_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('watchlists', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            \$table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            \$table->timestamps();
            \$table->unique(['user_id', 'content_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('watchlists'); }
};
EOT,

    '2026_08_18_160703_create_watch_histories_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('watch_histories', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            \$table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            \$table->foreignId('episode_id')->nullable()->constrained('episodes')->cascadeOnDelete();
            \$table->integer('progress_seconds')->default(0);
            \$table->integer('duration_seconds')->nullable();
            \$table->boolean('is_completed')->default(false);
            \$table->timestamp('last_watched_at')->useCurrent();
            \$table->timestamps();
            \$table->index(['user_id', 'content_id']);
            \$table->index(['user_id', 'episode_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('watch_histories'); }
};
EOT,

    '2026_08_18_160704_create_episode_bookmarks_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('episode_bookmarks', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            \$table->foreignId('episode_id')->constrained('episodes')->cascadeOnDelete();
            \$table->timestamps();
            \$table->unique(['user_id', 'episode_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('episode_bookmarks'); }
};
EOT,

    '2026_08_18_160705_create_ratings_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('ratings', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            \$table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            \$table->integer('rating');
            \$table->timestamps();
            \$table->unique(['user_id', 'content_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('ratings'); }
};
EOT,

    '2026_08_18_160706_create_comments_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('comments', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            \$table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            \$table->foreignId('episode_id')->nullable()->constrained('episodes')->cascadeOnDelete();
            \$table->text('body');
            \$table->boolean('is_approved')->default(true);
            \$table->timestamps();
            \$table->index(['content_id', 'is_approved']);
            \$table->index(['episode_id', 'is_approved']);
        });
    }
    public function down(): void { Schema::dropIfExists('comments'); }
};
EOT,

    '2026_08_18_160707_create_reports_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('reports', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            \$table->unsignedBigInteger('reportable_id');
            \$table->string('reportable_type');
            \$table->string('reason');
            \$table->text('details')->nullable();
            \$table->string('status')->default('pending');
            \$table->timestamps();
            \$table->index(['reportable_type', 'reportable_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('reports'); }
};
EOT,

    '2026_08_18_160708_create_banners_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('banners', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('content_id')->nullable()->constrained('contents')->nullOnDelete();
            \$table->string('image_path');
            \$table->string('title')->nullable();
            \$table->string('link_url')->nullable();
            \$table->integer('priority')->default(0);
            \$table->boolean('is_active')->default(true);
            \$table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('banners'); }
};
EOT,

    '2026_08_18_160709_create_settings_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('settings', function (Blueprint \$table) {
            \$table->string('key')->primary();
            \$table->text('value')->nullable();
            \$table->string('type');
            \$table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('settings'); }
};
EOT,
];

foreach ($migrations as $file => $content) {
    file_put_contents(__DIR__ . '/database/migrations/' . $file, $content);
}

echo "Migrations generated successfully.\n";

$models = [
    'Content.php' => <<<EOT
<?php
namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'type', 'title', 'slug', 'original_title', 'description', 'poster_path', 
    'backdrop_path', 'release_date', 'status', 'duration_minutes', 'age_rating',
    'average_rating', 'ratings_count', 'views_count', 'is_featured', 'is_published', 'published_at'
])]
class Content extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => ContentType::class,
            'status' => ContentStatus::class,
            'release_date' => 'date',
            'average_rating' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function genres() { return \$this->belongsToMany(Genre::class); }
    public function seasons() { return \$this->hasMany(Season::class); }
    public function videoSources() { return \$this->morphMany(VideoSource::class, 'sourceable'); }
    public function downloadSources() { return \$this->morphMany(DownloadSource::class, 'sourceable'); }
    public function subtitles() { return \$this->morphMany(Subtitle::class, 'sourceable'); }
    public function favorites() { return \$this->hasMany(Favorite::class); }
    public function watchlists() { return \$this->hasMany(Watchlist::class); }
    public function watchHistories() { return \$this->hasMany(WatchHistory::class); }
    public function ratings() { return \$this->hasMany(Rating::class); }
    public function comments() { return \$this->hasMany(Comment::class); }
    public function banners() { return \$this->hasMany(Banner::class); }
}
EOT,

    'Genre.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug'])]
class Genre extends Model
{
    use HasFactory;
    public function contents() { return \$this->belongsToMany(Content::class); }
}
EOT,

    'Season.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['content_id', 'season_number', 'title', 'description', 'poster_path'])]
class Season extends Model
{
    use HasFactory;
    public function content() { return \$this->belongsTo(Content::class); }
    public function episodes() { return \$this->hasMany(Episode::class); }
}
EOT,

    'Episode.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[Fillable(['season_id', 'episode_number', 'title', 'description', 'thumbnail_path', 'duration_minutes', 'release_date', 'is_published', 'published_at'])]
class Episode extends Model
{
    use HasFactory;
    protected function casts(): array {
        return [
            'release_date' => 'date',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
    public function season() { return \$this->belongsTo(Season::class); }
    public function videoSources() { return \$this->morphMany(VideoSource::class, 'sourceable'); }
    public function downloadSources() { return \$this->morphMany(DownloadSource::class, 'sourceable'); }
    public function subtitles() { return \$this->morphMany(Subtitle::class, 'sourceable'); }
    public function watchHistories() { return \$this->hasMany(WatchHistory::class); }
    public function episodeBookmarks() { return \$this->hasMany(EpisodeBookmark::class); }
    public function comments() { return \$this->hasMany(Comment::class); }
    
    // Convenient accessor to Content
    protected function content(): Attribute {
        return Attribute::make(get: fn () => \$this->season->content);
    }
}
EOT,

    'VideoSource.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['provider', 'url', 'quality', 'server_name', 'language', 'priority', 'is_active'])]
class VideoSource extends Model
{
    use HasFactory;
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function sourceable() { return \$this->morphTo(); }
}
EOT,

    'DownloadSource.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['provider', 'url', 'quality', 'server_name', 'file_size_bytes', 'priority', 'is_active'])]
class DownloadSource extends Model
{
    use HasFactory;
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function sourceable() { return \$this->morphTo(); }
}
EOT,

    'Subtitle.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['language', 'label', 'file_path'])]
class Subtitle extends Model
{
    use HasFactory;
    public function sourceable() { return \$this->morphTo(); }
}
EOT,

    'Favorite.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'content_id'])]
class Favorite extends Model
{
    use HasFactory;
    public function user() { return \$this->belongsTo(User::class); }
    public function content() { return \$this->belongsTo(Content::class); }
}
EOT,

    'Watchlist.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'content_id'])]
class Watchlist extends Model
{
    use HasFactory;
    public function user() { return \$this->belongsTo(User::class); }
    public function content() { return \$this->belongsTo(Content::class); }
}
EOT,

    'WatchHistory.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'content_id', 'episode_id', 'progress_seconds', 'duration_seconds', 'is_completed', 'last_watched_at'])]
class WatchHistory extends Model
{
    use HasFactory;
    protected function casts(): array {
        return ['is_completed' => 'boolean', 'last_watched_at' => 'datetime'];
    }
    public function user() { return \$this->belongsTo(User::class); }
    public function content() { return \$this->belongsTo(Content::class); }
    public function episode() { return \$this->belongsTo(Episode::class); }
}
EOT,

    'EpisodeBookmark.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'episode_id'])]
class EpisodeBookmark extends Model
{
    use HasFactory;
    public function user() { return \$this->belongsTo(User::class); }
    public function episode() { return \$this->belongsTo(Episode::class); }
}
EOT,

    'Rating.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'content_id', 'rating'])]
class Rating extends Model
{
    use HasFactory;
    public function user() { return \$this->belongsTo(User::class); }
    public function content() { return \$this->belongsTo(Content::class); }
}
EOT,

    'Comment.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'content_id', 'episode_id', 'body', 'is_approved'])]
class Comment extends Model
{
    protected function casts(): array { return ['is_approved' => 'boolean']; }
    public function user() { return \$this->belongsTo(User::class); }
    public function content() { return \$this->belongsTo(Content::class); }
    public function episode() { return \$this->belongsTo(Episode::class); }
}
EOT,

    'Report.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'reason', 'details', 'status'])]
class Report extends Model
{
    public function user() { return \$this->belongsTo(User::class); }
    public function reportable() { return \$this->morphTo(); }
}
EOT,

    'Banner.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['content_id', 'image_path', 'title', 'link_url', 'priority', 'is_active'])]
class Banner extends Model
{
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function content() { return \$this->belongsTo(Content::class); }
}
EOT,

    'Setting.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value', 'type'])]
class Setting extends Model
{
    protected \$primaryKey = 'key';
    public \$incrementing = false;
    protected \$keyType = 'string';
}
EOT,

];

foreach ($models as $file => $content) {
    file_put_contents(__DIR__ . '/app/Models/' . $file, $content);
}
echo "Models generated successfully.\n";

// Append relationships to User model
$userFile = __DIR__ . '/app/Models/User.php';
$userContent = file_get_contents($userFile);

if (!str_contains($userContent, 'public function favorites()')) {
    $relations = <<<EOT

    public function favorites() { return \$this->hasMany(Favorite::class); }
    public function watchlists() { return \$this->hasMany(Watchlist::class); }
    public function watchHistories() { return \$this->hasMany(WatchHistory::class); }
    public function episodeBookmarks() { return \$this->hasMany(EpisodeBookmark::class); }
    public function ratings() { return \$this->hasMany(Rating::class); }
    public function comments() { return \$this->hasMany(Comment::class); }
    public function reports() { return \$this->hasMany(Report::class); }
}
EOT;
    $userContent = preg_replace('/}\s*$/', $relations, $userContent);
    file_put_contents($userFile, $userContent);
    echo "User model updated successfully.\n";
}

// AppServiceProvider Morph Map
$providerFile = __DIR__ . '/app/Providers/AppServiceProvider.php';
$providerContent = file_get_contents($providerFile);

if (!str_contains($providerContent, 'Relation::enforceMorphMap')) {
    $providerContent = str_replace(
        'use Illuminate\Support\ServiceProvider;',
        "use Illuminate\Support\ServiceProvider;\nuse Illuminate\Database\Eloquent\Relations\Relation;",
        $providerContent
    );
    $map = <<<EOT
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'content' => \App\Models\Content::class,
            'episode' => \App\Models\Episode::class,
            'comment' => \App\Models\Comment::class,
            'video_source' => \App\Models\VideoSource::class,
            'download_source' => \App\Models\DownloadSource::class,
            'subtitle' => \App\Models\Subtitle::class,
        ]);
EOT;
    $providerContent = str_replace('public function boot(): void' . "\n" . '    {', $map, $providerContent);
    file_put_contents($providerFile, $providerContent);
    echo "AppServiceProvider updated successfully.\n";
}
