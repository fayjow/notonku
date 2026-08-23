<?php
namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request, \App\Services\RecommendationService $recommendationService)
    {
        $user = Auth::user();
        
        $heroContent = Content::published()->with('genres')->orderByDesc('is_featured')->latest('release_date')->limit(5)->get();
        
        $latestMovies = Content::published()->where('type', 'movie')->latest()->limit(12)->get();
        $latestSeries = Content::published()->where('type', 'series')->latest()->limit(12)->get();
        $latestAnime = Content::published()->where('type', 'anime')->latest()->limit(12)->get();
        $latestDonghua = Content::published()->where('type', 'donghua')->latest()->limit(12)->get();
        
        $popular = Content::published()->popular()->limit(12)->get();
        $recentlyAdded = Content::published()->latest()->limit(12)->get();
        
        $recommendations = $recommendationService->getRecommendations($user, 12);
        
        $continueWatching = collect();
        $myFavorites = collect();
        $myWatchlist = collect();
        $becauseYouWatched = collect();
        $lastWatched = null;

        if ($user) {
            $continueWatching = $user->watchHistories()
                ->with(['content', 'episode.season'])
                ->where('is_completed', false)
                ->orderBy('last_watched_at', 'desc')
                ->limit(6)
                ->get();
                
            $myFavorites = $user->favorites()->with('content')->latest()->limit(12)->get()->pluck('content')->filter(fn($c) => $c->is_published);
            $myWatchlist = $user->watchlists()->with('content')->latest()->limit(12)->get()->pluck('content')->filter(fn($c) => $c->is_published);
            
            $lastHistory = $user->watchHistories()->orderByDesc('last_watched_at')->first();
            if ($lastHistory) {
                // If it's an episode, find the content
                if ($lastHistory->episode_id) {
                    $lastWatched = \App\Models\Episode::with('season.content')->find($lastHistory->episode_id)?->season?->content;
                } else {
                    $lastWatched = \App\Models\Content::find($lastHistory->content_id);
                }
                
                if ($lastWatched && $lastWatched->is_published) {
                    $becauseYouWatched = $recommendationService->getSimilarContent($lastWatched, 12);
                }
            }
        }

        return view('public.home', compact(
            'heroContent',
            'latestMovies',
            'latestSeries',
            'latestAnime',
            'latestDonghua',
            'popular',
            'recentlyAdded',
            'recommendations',
            'continueWatching',
            'myFavorites',
            'myWatchlist',
            'becauseYouWatched',
            'lastWatched'
        ));
    }
}