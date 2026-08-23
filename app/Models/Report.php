<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'reportable_id', 'reportable_type', 'reason', 'details', 'status'])]
class Report extends Model
{
    public function user() { return $this->belongsTo(User::class); }
    public function reportable() { return $this->morphTo(); }
}