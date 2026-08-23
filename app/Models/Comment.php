<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'content_id', 'episode_id', 'body', 'is_approved'])]
class Comment extends Model
{
    protected function casts(): array { return ['is_approved' => 'boolean']; }
    public function user() { return $this->belongsTo(User::class); }
    public function content() { return $this->belongsTo(Content::class); }
    public function episode() { return $this->belongsTo(Episode::class); }
}