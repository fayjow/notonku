<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    public function season() { return $this->belongsTo(Season::class); }
    public function videoSources() { return $this->morphMany(VideoSource::class, 'sourceable'); }
    public function downloadSources() { return $this->morphMany(DownloadSource::class, 'sourceable'); }
    public function subtitles() { return $this->morphMany(Subtitle::class, 'sourceable'); }
    public function watchHistories() { return $this->hasMany(WatchHistory::class); }
    public function episodeBookmarks() { return $this->hasMany(EpisodeBookmark::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
                     ->where(function ($q) {
                         $q->whereNull('published_at')
                           ->orWhere('published_at', '<=', now());
                     });
    }

    // Convenient accessor to Content
    protected function content(): Attribute {
        return Attribute::make(get: fn () => $this->season->content);
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->thumbnail_path 
                ? (Str::startsWith($this->thumbnail_path, ['http://', 'https://']) ? $this->thumbnail_path : Storage::url($this->thumbnail_path)) 
                : null
        );
    }
}