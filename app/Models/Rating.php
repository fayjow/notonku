<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'content_id', 'rating'])]
class Rating extends Model
{
    use HasFactory;
    public function user() { return $this->belongsTo(User::class); }
    public function content() { return $this->belongsTo(Content::class); }
}