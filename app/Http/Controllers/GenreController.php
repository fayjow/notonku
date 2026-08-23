<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of all genres.
     */
    public function index()
    {
        // Load all genres with count of published content
        $genres = Genre::withCount(['contents' => function ($query) {
            $query->published();
        }])->orderBy('name')->get();

        return view('public.genres.index', compact('genres'));
    }

    /**
     * Display content for a specific genre.
     */
    public function show(Request $request, Genre $genre)
    {
        $sort = $request->query('sort', 'latest');
        
        $query = $genre->contents()->published()->with('genres');
        
        match ($sort) {
            'oldest' => $query->oldest('release_date'),
            'rating' => $query->orderByDesc('average_rating'),
            'popular' => $query->orderByDesc('views_count'),
            'az' => $query->orderBy('title'),
            default => $query->latest('release_date')
        };
        
        $contents = $query->paginate(24)->withQueryString();

        return view('public.genres.show', compact('genre', 'contents', 'sort'));
    }
}
