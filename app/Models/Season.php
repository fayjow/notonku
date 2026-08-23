<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['content_id', 'season_number', 'title', 'description', 'poster_path'])]
class Season extends Model
{
    use HasFactory;
    public function content() { return $this->belongsTo(Content::class); }
    public function episodes() { return $this->hasMany(Episode::class); }
}