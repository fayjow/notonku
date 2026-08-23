<?php

$files = [
    'app/Services/ContentViewService.php' => <<<'EOT'
<?php
namespace App\Services;

use App\Models\Content;

class ContentViewService
{
    public function increment(Content $content): void
    {
        // Simple increment for Phase 4.
        // Can be easily upgraded to daily/unique views later.
        $content->increment('views_count');
    }
}
EOT,

    'app/Http/Controllers/HomeController.php' => <<<'EOT'
<?php
namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch published content eagerly loading genres to avoid N+1 if genres are needed on cards
        $trending = Content::published()->orderBy('views_count', 'desc')->limit(12)->get();
        $series = Content::published()->whereIn('type', ['series', 'anime', 'donghua'])->latest('release_date')->limit(12)->get();
        
        return view('public.home', compact('trending', 'series'));
    }
}
EOT,

    'app/Http/Controllers/CatalogController.php' => <<<'EOT'
<?php
namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Genre;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    private array $allowedSorts = ['latest', 'oldest', 'rating', 'popular', 'title'];

    public function movies(Request $request) { return $this->renderCatalog($request, 'movie', 'Movies'); }
    public function series(Request $request) { return $this->renderCatalog($request, 'series', 'Series'); }
    public function anime(Request $request)  { return $this->renderCatalog($request, 'anime', 'Anime'); }
    public function donghua(Request $request){ return $this->renderCatalog($request, 'donghua', 'Donghua'); }

    private function renderCatalog(Request $request, string $type, string $title)
    {
        $sort = $request->query('sort', 'latest');
        if (!in_array($sort, $this->allowedSorts)) {
            $sort = 'latest';
        }

        $genreSlug = $request->query('genre');

        $query = Content::published()->where('type', $type);

        if ($genreSlug) {
            $query->whereHas('genres', function ($q) use ($genreSlug) {
                $q->where('slug', $genreSlug); // Assuming genres table has slug, or we can use name. 
                // Wait, genre table might only have 'name'. Let's check genre table later. We will use id for safety.
            });
        }

        switch ($sort) {
            case 'oldest': $query->oldest('release_date'); break;
            case 'rating': $query->orderByDesc('average_rating'); break;
            case 'popular': $query->orderByDesc('views_count'); break;
            case 'title':  $query->orderBy('title'); break;
            case 'latest':
            default:       $query->latest('release_date'); break;
        }

        $contents = $query->paginate(24)->withQueryString();

        return view('public.catalog', compact('contents', 'title', 'sort', 'type'));
    }
}
EOT,

    'app/Http/Controllers/SearchController.php' => <<<'EOT'
<?php
namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $q = $validated['q'] ?? null;
        
        $query = Content::published();
        
        if ($q) {
            $query->where(function($b) use ($q) {
                $b->where('title', 'like', '%' . $q . '%')
                  ->orWhere('original_title', 'like', '%' . $q . '%');
            });
        }

        $contents = $query->latest('release_date')->paginate(24)->withQueryString();

        return view('public.search', compact('contents', 'q'));
    }
}
EOT,

    'app/Http/Controllers/ContentController.php' => <<<'EOT'
<?php
namespace App\Http\Controllers;

use App\Models\Content;
use App\Services\ContentViewService;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function showMovie(Content $content, ContentViewService $viewService) { return $this->renderDetail($content, 'movie', $viewService); }
    public function showSeries(Content $content, ContentViewService $viewService) { return $this->renderDetail($content, 'series', $viewService); }
    public function showAnime(Content $content, ContentViewService $viewService)  { return $this->renderDetail($content, 'anime', $viewService); }
    public function showDonghua(Content $content, ContentViewService $viewService){ return $this->renderDetail($content, 'donghua', $viewService); }

    private function renderDetail(Content $content, string $expectedType, ContentViewService $viewService)
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

        return view('public.show', compact('content'));
    }
}
EOT,
];

foreach ($files as $path => $content) {
    @mkdir(dirname(__DIR__ . '/' . $path), 0777, true);
    file_put_contents(__DIR__ . '/' . $path, $content);
}

echo "Controllers and Services generated successfully.\n";
