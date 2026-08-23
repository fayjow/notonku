<?php
namespace App\Http\Controllers;

use App\Models\Content;
use App\Services\ContentViewService;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function showMovie(Content $content, ContentViewService $viewService, \App\Services\RecommendationService $recommendationService) { return $this->renderDetail($content, 'movie', $viewService, $recommendationService); }
    public function showSeries(Content $content, ContentViewService $viewService, \App\Services\RecommendationService $recommendationService) { return $this->renderDetail($content, 'series', $viewService, $recommendationService); }
    public function showAnime(Content $content, ContentViewService $viewService, \App\Services\RecommendationService $recommendationService)  { return $this->renderDetail($content, 'anime', $viewService, $recommendationService); }
    public function showDonghua(Content $content, ContentViewService $viewService, \App\Services\RecommendationService $recommendationService){ return $this->renderDetail($content, 'donghua', $viewService, $recommendationService); }

    private function renderDetail(Content $content, string $expectedType, ContentViewService $viewService, \App\Services\RecommendationService $recommendationService)
    {
        if ($content->type->value !== $expectedType) {
            abort(404);
        }

        // Must be published or unpublished logic will abort
        if (!$content->is_published || ($content->published_at && $content->published_at->isFuture())) {
            abort(404);
        }

        // Increment views
        $viewService->increment($content);

        // Load relations efficiently
        $content->load(['genres', 'videoSources']);

        if (in_array($expectedType, ['series', 'anime', 'donghua'])) {
            $content->load(['seasons' => function ($query) {
                $query->with(['episodes' => function ($q) {
                    $q->published()->orderBy('episode_number', 'asc');
                }]);
            }]);
        }

        $userFavorite = false;
        $userRating = null;
        $bookmarkedEpisodes = [];

        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $userFavorite = $user->favorites()->where('content_id', $content->id)->exists();
            $userRating = $user->ratings()->where('content_id', $content->id)->value('rating');
            
            if (in_array($expectedType, ['series', 'anime', 'donghua'])) {
                // Fetch bookmarked episode IDs for this content's episodes
                $episodeIds = collect();
                foreach ($content->seasons as $season) {
                    foreach ($season->episodes as $episode) {
                        $episodeIds->push($episode->id);
                    }
                }
                
                if ($episodeIds->isNotEmpty()) {
                    $bookmarkedEpisodes = $user->episodeBookmarks()
                        ->whereIn('episode_id', $episodeIds)
                        ->pluck('episode_id')
                        ->toArray();
                }
            }
        }

        $relatedContent = $recommendationService->getSimilarContent($content, 6);

        return view('public.show', compact('content', 'userFavorite', 'userRating', 'bookmarkedEpisodes', 'relatedContent'));
    }
}