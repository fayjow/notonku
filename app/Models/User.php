<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function favorites() { return $this->hasMany(Favorite::class); }
    public function watchlists() { return $this->hasMany(Watchlist::class); }
    public function watchHistories() { return $this->hasMany(WatchHistory::class); }
    public function episodeBookmarks() { return $this->hasMany(EpisodeBookmark::class); }
    public function ratings() { return $this->hasMany(Rating::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function reports() { return $this->hasMany(Report::class); }
}