<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeasonRequest;
use App\Http\Requests\UpdateSeasonRequest;
use App\Models\Content;
use App\Models\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Content $content)
    {
        $seasons = $content->seasons()->orderBy('season_number')->paginate(20);

        return view('admin.seasons.index', compact('content', 'seasons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Content $content)
    {
        $nextSeasonNumber = $content->seasons()->max('season_number') + 1;
        
        return view('admin.seasons.create', compact('content', 'nextSeasonNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSeasonRequest $request, Content $content)
    {
        $content->seasons()->create($request->validated());

        return redirect()->route('admin.content.seasons.index', $content)->with('status', 'Season created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $content, Season $season)
    {
        return view('admin.seasons.edit', compact('content', 'season'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSeasonRequest $request, Content $content, Season $season)
    {
        $season->update($request->validated());

        return redirect()->route('admin.content.seasons.index', $content)->with('status', 'Season updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $content, Season $season)
    {
        $season->delete();

        return redirect()->route('admin.content.seasons.index', $content)->with('status', 'Season deleted successfully.');
    }
}
