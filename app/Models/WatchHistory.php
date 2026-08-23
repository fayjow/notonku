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
    public function user() { return $this->belongsTo(User::class); }
    public function content() { return $this->belongsTo(Content::class); }
    public function episode() { return $this->belongsTo(Episode::class); }
}