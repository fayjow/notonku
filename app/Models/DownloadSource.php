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
    public function sourceable() { return $this->morphTo(); }
}