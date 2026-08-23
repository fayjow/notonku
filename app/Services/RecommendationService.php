<?php

namespace App\Services;

use App\Models\Content;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    /**
     * Get personalized recommendations for a user.
     * If user is null (guest), return generic popular recommendations.
     *
     * @param User|null $user
     * @param int $limit
     * @return Collection
     */
    public function getRecommendations(?User $user, int $limit = 10): Collection
    {
        if (!$user) {
            return $this->getGenericRecommendations($limit);
        }

        return $this->getPersonalizedRecommendations($user, $limit);
    }

    /**
     * Generic recommendations based purely on popularity.
     */
    protected function getGenericRecommendations(int $limit): Collection
    {
        return Content::published()
            ->with('genres')
            ->popular()
            ->limit($limit)
            ->get();
    }

    /**
     * Personalized recommendations based on history and favorites.
     */
    protected function getPersonalizedRecommendations(User $user, int $limit): Collection
    {
        // 1. Gather User Preferences
        // Genres from favorites and watch history
        $favoriteContentIds = $user->favorites()->pluck('content_id');
        
        $historyContentIds = $user->watchHistories()
            ->whereNotNull('watch_histories.episode_id')
            ->join('episodes', 'watch_histories.episode_id', '=', 'episodes.id')
            ->join('seasons', 'episodes.season_id', '=', 'seasons.id')
            ->pluck('seasons.content_id')
            ->concat(
                $user->watchHistories()
                ->whereNull('watch_histories.episode_id')
                ->whereNotNull('watch_histories.content_id')
                ->pluck('watch_histories.content_id')
            )->unique();

        $allInteractedIds = $favoriteContentIds->concat($historyContentIds)->unique();

        // If no interactions, fallback to generic
        if ($allInteractedIds->isEmpty()) {
            return $this->getGenericRecommendations($limit);
        }

        // Find most frequent genres from interacted content
        $preferredGenreIds = DB::table('content_genre')
            ->whereIn('content_id', $allInteractedIds)
            ->select('genre_id', DB::raw('count(*) as count'))
            ->groupBy('genre_id')
            ->orderByDesc('count')
            ->limit(3) // Top 3 genres
            ->pluck('genre_id');

        // Identify completed content (rough heuristic: if it's a movie in history, or if they've watched multiple episodes, for simplicity we exclude all interacted items to ensure fresh recommendations)
        // Rule: Never recommend content already in favorites. Never recommend content they have in history (assuming they watched it).
        
        $excludedIds = $allInteractedIds;

        // Build the recommendation query
        $query = Content::published()
            ->with('genres')
            ->whereNotIn('id', $excludedIds);

        if ($preferredGenreIds->isNotEmpty()) {
            $query->whereHas('genres', function ($q) use ($preferredGenreIds) {
                $q->whereIn('genres.id', $preferredGenreIds);
            });
        }

        $recommendations = $query->popular()
            ->limit($limit)
            ->get();

        // If we don't have enough personalized recommendations, backfill with popular ones
        if ($recommendations->count() < $limit) {
            $needed = $limit - $recommendations->count();
            $backfill = Content::published()
                ->with('genres')
                ->whereNotIn('id', $excludedIds->concat($recommendations->pluck('id')))
                ->popular()
                ->limit($needed)
                ->get();
            
            $recommendations = $recommendations->concat($backfill);
        }

        return $recommendations;
    }

    /**
     * Contextual recommendation based on a specific content (Because you watched X)
     */
    public function getSimilarContent(Content $content, int $limit = 4): Collection
    {
        $genreIds = $content->genres->pluck('id');
        
        $query = Content::published()
            ->with('genres')
            ->where('id', '!=', $content->id)
            ->where('type', $content->type);
            
        if ($genreIds->isNotEmpty()) {
            $query->whereHas('genres', function($q) use ($genreIds) {
                $q->whereIn('genres.id', $genreIds);
            });
        }
        
        return $query->popular()->limit($limit)->get();
    }
}
