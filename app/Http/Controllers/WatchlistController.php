<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\EpisodeBookmark;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index(Request $request)
    {
        $bookmarks = $request->user()->episodeBookmarks()
            ->whereHas('episode', function($q) {
                $q->published()->whereHas('season', function($q2) {
                    $q2->whereHas('content', function($q3) {
                        $q3->published();
                    });
                });
            })
            ->with(['episode.season.content'])
            ->latest()
            ->paginate(24);

        return view('public.watchlist', compact('bookmarks'));
    }

    public function store(Request $request, Episode $episode)
    {
        EpisodeBookmark::firstOrCreate([
            'user_id' => $request->user()->id,
            'episode_id' => $episode->id,
        ]);
        return response()->json(['message' => 'Added to watchlist.']);
    }

    public function destroy(Request $request, Episode $episode)
    {
        $request->user()->episodeBookmarks()->where('episode_id', $episode->id)->delete();
        return response()->json(['message' => 'Removed from watchlist.']);
    }
}
