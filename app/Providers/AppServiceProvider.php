<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
        public function boot(): void
    {
        Relation::enforceMorphMap([
            'content' => \App\Models\Content::class,
            'episode' => \App\Models\Episode::class,
            'comment' => \App\Models\Comment::class,
            'video_source' => \App\Models\VideoSource::class,
            'download_source' => \App\Models\DownloadSource::class,
            'subtitle' => \App\Models\Subtitle::class,
        ]);
        \Illuminate\Support\Facades\Gate::define('admin', function (\App\Models\User $user) {
            return $user->role === 'admin';
        });

        RateLimiter::for('search', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
