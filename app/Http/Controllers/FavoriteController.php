<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()->favorites()
            ->whereHas('content', function ($q) {
                $q->published();
            })
            ->with('content')
            ->latest()
            ->paginate(24);

        return view('public.favorites', compact('favorites'));
    }

    public function store(Request $request, Content $content)
    {
        Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'content_id' => $content->id,
        ]);
        return response()->json(['message' => 'Added to favorites.']);
    }

    public function destroy(Request $request, Content $content)
    {
        $request->user()->favorites()->where('content_id', $content->id)->delete();
        return response()->json(['message' => 'Removed from favorites.']);
    }
}
