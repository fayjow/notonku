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
            'type' => ['nullable', 'string', 'in:movie,series,anime,donghua'],
            'genre' => ['nullable', 'string', 'exists:genres,slug'],
            'status' => ['nullable', 'string', 'in:ongoing,completed,hiatus,upcoming'],
            'sort' => ['nullable', 'string', 'in:latest,oldest,rating,popular,az'],
        ]);

        $q = $validated['q'] ?? null;
        $type = $validated['type'] ?? null;
        $genreSlug = $validated['genre'] ?? null;
        $status = $validated['status'] ?? null;
        $sort = $validated['sort'] ?? 'latest';
        
        $genres = \App\Models\Genre::orderBy('name')->get();

        $query = Content::published()->with('genres');

        if ($q) {
            $query->where(function($b) use ($q) {
                $b->where('title', 'like', '%' . $q . '%')
                  ->orWhere('original_title', 'like', '%' . $q . '%');
            });
        }
        
        if ($type) {
            $query->where('type', $type);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($genreSlug) {
            $query->whereHas('genres', function($q) use ($genreSlug) {
                $q->where('slug', $genreSlug);
            });
        }
        
        match ($sort) {
            'oldest' => $query->oldest('release_date'),
            'rating' => $query->orderByDesc('average_rating'),
            'popular' => $query->orderByDesc('views_count'),
            'az' => $query->orderBy('title'),
            default => $query->latest('release_date') // latest
        };

        $contents = $query->paginate(24)->withQueryString();

        return view('public.search', compact('contents', 'q', 'type', 'genreSlug', 'status', 'sort', 'genres'));
    }
}