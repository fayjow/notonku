<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Content $content, Season $season)
    {
        $episodes = $season->episodes()->orderBy('episode_number')->paginate(20);

        return view('admin.episodes.index', compact('content', 'season', 'episodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Content $content, Season $season)
    {
        $nextEpisodeNumber = $season->episodes()->max('episode_number') + 1;
        
        return view('admin.episodes.create', compact('content', 'season', 'nextEpisodeNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEpisodeRequest $request, Content $content, Season $season)
    {
        $data = $request->validated();
        
        $data['is_published'] = $request->has('is_published');
        
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('episodes/thumbnails', 'public');
        }

        $season->episodes()->create($data);

        return redirect()->route('admin.content.seasons.episodes.index', [$content, $season])->with('status', 'Episode created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $content, Season $season, Episode $episode)
    {
        return view('admin.episodes.edit', compact('content', 'season', 'episode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEpisodeRequest $request, Content $content, Season $season, Episode $episode)
    {
        $data = $request->validated();
        
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('thumbnail')) {
            if ($episode->thumbnail_path) {
                Storage::disk('public')->delete($episode->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('episodes/thumbnails', 'public');
        }

        $episode->update($data);

        return redirect()->route('admin.content.seasons.episodes.index', [$content, $season])->with('status', 'Episode updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $content, Season $season, Episode $episode)
    {
        if ($episode->thumbnail_path) {
            Storage::disk('public')->delete($episode->thumbnail_path);
        }

        $episode->delete();

        return redirect()->route('admin.content.seasons.episodes.index', [$content, $season])->with('status', 'Episode deleted successfully.');
    }
}
