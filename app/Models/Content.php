<?php
namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function genres() { return $this->belongsToMany(Genre::class); }
    public function seasons() { return $this->hasMany(Season::class); }
    public function videoSources() { return $this->morphMany(VideoSource::class, 'sourceable'); }
    public function downloadSources() { return $this->morphMany(DownloadSource::class, 'sourceable'); }
    public function subtitles() { return $this->morphMany(Subtitle::class, 'sourceable'); }
    public function favorites() { return $this->hasMany(Favorite::class); }
    public function watchlists() { return $this->hasMany(Watchlist::class); }
    public function watchHistories() { return $this->hasMany(WatchHistory::class); }
    public function ratings() { return $this->hasMany(Rating::class); }
    public function comments() { return $this->morphMany(Comment::class, 'commentable'); }
    public function reports() { return $this->morphMany(Report::class, 'reportable'); }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
                     ->where(function ($q) {
                         $q->whereNull('published_at')
                           ->orWhere('published_at', '<=', now());
                     });
    }

    public function scopePopular(Builder $query): Builder
    {
        // Simple deterministic popularity based on views and ratings
        return $query->orderByDesc('views_count')
                     ->orderByDesc('average_rating')
                     ->orderByDesc('ratings_count');
    }

    public function updateAverageRating(): void
    {
        $aggregate = $this->ratings()
            ->selectRaw('IFNULL(AVG(rating), 0) as average, COUNT(*) as count')
            ->first();

        $this->update([
            'average_rating' => $aggregate->average,
            'ratings_count' => $aggregate->count,
        ]);
    }

    protected function posterUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->poster_path 
                ? (Str::startsWith($this->poster_path, ['http://', 'https://']) ? $this->poster_path : Storage::url($this->poster_path)) 
                : null
        );
    }

    protected function backdropUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->backdrop_path 
                ? (Str::startsWith($this->backdrop_path, ['http://', 'https://']) ? $this->backdrop_path : Storage::url($this->backdrop_path)) 
                : null
        );
    }
}