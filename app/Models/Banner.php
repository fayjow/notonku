<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['content_id', 'image_path', 'title', 'link_url', 'priority', 'is_active'])]
class Banner extends Model
{
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function content() { return $this->belongsTo(Content::class); }
}