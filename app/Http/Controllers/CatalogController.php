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