<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['sourceable_type', 'sourceable_id', 'provider', 'url', 'quality', 'server_name', 'language', 'priority', 'is_active', 'is_downloadable', 'supports_autoplay'])]
class VideoSource extends Model
{
    use HasFactory;
    protected function casts(): array { return ['is_active' => 'boolean', 'is_downloadable' => 'boolean', 'supports_autoplay' => 'boolean']; }
    public function sourceable() { return $this->morphTo(); }
}