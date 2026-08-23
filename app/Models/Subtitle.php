<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['language', 'label', 'file_path'])]
class Subtitle extends Model
{
    use HasFactory;
    public function sourceable() { return $this->morphTo(); }
}