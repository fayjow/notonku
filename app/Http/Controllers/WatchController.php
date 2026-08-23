<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchController extends Controller
{
    public function watchMovie(Content $content)
    {
        return $this->renderPlayer($content, 'movie', null);
    }

    public function watchSeries(Content $content, Episode $episode)
    {
        return $this->renderPlayer($content, 'series', $episode);
    }

    public function watchAnime(Content $content, Episode $episode)
    {
        return $this->renderPlayer($content, 'anime', $episode);
    }

    public function watchDonghua(Content $content, Episode $episode)
    {
        return $this->renderPlayer($content, 'donghua', $episode);
    }

    private function renderPlayer(Content $content, string $expectedType, ?Episode $episode)
    {
        if ($content->type->value !== $expectedType) {
            abort(404);
        }

        if (!$content->is_published || ($content->published_at && $content->published_at->isFuture())) {
            abort(404);
        }

        if ($episode) {
            if (!$episode->is_published || ($episode->published_at && $episode->published_at->isFuture())) {
                abort(404);
            }
            // Ensure episode actually belongs to this content
            if ($episode->season->content_id !== $content->id) {
                abort(404);
            }
        }

        // Fetch video sources (eager loading)
        $videoSources = $episode 
            ? $episode->videoSources()->where('is_active', true)->orderByDesc('priority')->get()
            : $content->videoSources()->where('is_active', true)->orderByDesc('priority')->get();

        // Safely determine the active source (IDOR safe)
        $activeSource = $videoSources->first();
        if (app()->runningUnitTests()) {
            \Log::info('VideoSources Count: ' . $videoSources->count());
            \Log::info('ActiveSource: ' . json_encode($activeSource));
        }
        if (request()->has('source_id')) {
            $requestedSource = $videoSources->firstWhere('id', request('source_id'));
            if ($requestedSource) {
                $activeSource = $requestedSource;
            }
        }

        // Load all seasons & episodes for navigation (for non-movies)
        $episodes = collect();
        $nextEpisode = null;
        $prevEpisode = null;

        if (in_array($expectedType, ['series', 'anime', 'donghua'])) {
            $content->load(['seasons' => function ($q) {
                $q->orderBy('season_number', 'asc')->with(['episodes' => function ($q2) {
                    $q2->published()->orderBy('episode_number', 'asc');
                }]);
            }]);

            // Flatten all published episodes into a single list to easily find prev/next
            foreach ($content->seasons as $season) {
                foreach ($season->episodes as $ep) {
                    $episodes->push($ep);
                }
            }

            if ($episode) {
                $currentIndex = $episodes->search(fn($ep) => $ep->id === $episode->id);
                if ($currentIndex !== false) {
                    $prevEpisode = $currentIndex > 0 ? $episodes[$currentIndex - 1] : null;
                    $nextEpisode = $currentIndex < $episodes->count() - 1 ? $episodes[$currentIndex + 1] : null;
                }
            }
        }

        // Fetch watch history
        $watchHistory = null;
        if (Auth::check()) {
            $query = Auth::user()->watchHistories()->where('content_id', $content->id);
            if ($episode) {
                $query->where('episode_id', $episode->id);
            } else {
                $query->whereNull('episode_id');
            }
            $watchHistory = $query->first();
        }

        return view('public.watch', compact(
            'content', 
            'episode', 
            'videoSources', 
            'episodes', 
            'nextEpisode', 
            'prevEpisode', 
            'watchHistory',
            'activeSource'
        ));
    }
}
