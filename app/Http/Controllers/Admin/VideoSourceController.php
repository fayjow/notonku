<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoSourceRequest;
use App\Http\Requests\UpdateVideoSourceRequest;
use App\Models\VideoSource;
use App\Models\Content;
use App\Models\Episode;
use Illuminate\Http\Request;

class VideoSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = VideoSource::query();

        if ($request->filled('sourceable_type') && $request->filled('sourceable_id')) {
            $query->where('sourceable_type', $request->sourceable_type)
                  ->where('sourceable_id', $request->sourceable_id);
        }

        $videoSources = $query->latest()->paginate(20)->withQueryString();
        
        $sourceable = null;
        if ($request->filled('sourceable_type') && $request->filled('sourceable_id')) {
            $type = $request->sourceable_type;
            if ($type === 'App\\Models\\Content' || $type === 'Content' || $type === 'content') {
                $sourceable = Content::find($request->sourceable_id);
            } elseif ($type === 'App\\Models\\Episode' || $type === 'Episode' || $type === 'episode') {
                $sourceable = Episode::with('season.content')->find($request->sourceable_id);
            }
        }

        return view('admin.video-sources.index', compact('videoSources', 'sourceable'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $sourceable_type = $request->sourceable_type;
        $sourceable_id = $request->sourceable_id;
        
        $sourceable = null;
        if ($sourceable_type === 'App\\Models\\Content' || $sourceable_type === 'Content' || $sourceable_type === 'content') {
            $sourceable = Content::findOrFail($sourceable_id);
            $sourceable_type = 'content';
        } elseif ($sourceable_type === 'App\\Models\\Episode' || $sourceable_type === 'Episode' || $sourceable_type === 'episode') {
            $sourceable = Episode::with('season.content')->findOrFail($sourceable_id);
            $sourceable_type = 'episode';
        } else {
            abort(404, 'Invalid sourceable type');
        }

        return view('admin.video-sources.create', compact('sourceable', 'sourceable_type', 'sourceable_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoSourceRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        $data['is_downloadable'] = $request->has('is_downloadable');
        $data['supports_autoplay'] = $request->has('supports_autoplay');
        
        if ($data['provider'] !== 'mp4') {
            $data['is_downloadable'] = false;
        }
        
        $sourceable_type = $request->input('sourceable_type');
        $sourceable_id = $request->input('sourceable_id');
        
        $sourceable = null;
        if ($sourceable_type === 'App\\Models\\Content' || $sourceable_type === 'Content' || $sourceable_type === 'content') {
            $sourceable = Content::findOrFail($sourceable_id);
            $sourceable_type = 'content';
        } elseif ($sourceable_type === 'App\\Models\\Episode' || $sourceable_type === 'Episode' || $sourceable_type === 'episode') {
            $sourceable = Episode::findOrFail($sourceable_id);
            $sourceable_type = 'episode';
        } else {
            abort(404, 'Invalid sourceable type');
        }

        $sourceable->videoSources()->create($data);

        return redirect()->route('admin.video-sources.index', [
            'sourceable_type' => $sourceable_type,
            'sourceable_id' => $sourceable_id
        ])->with('status', 'Video Source created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VideoSource $videoSource)
    {
        $sourceable = $videoSource->sourceable;
        
        return view('admin.video-sources.edit', compact('videoSource', 'sourceable'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoSourceRequest $request, VideoSource $videoSource)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        $data['is_downloadable'] = $request->has('is_downloadable');
        $data['supports_autoplay'] = $request->has('supports_autoplay');

        if ($data['provider'] !== 'mp4') {
            $data['is_downloadable'] = false;
        }

        $videoSource->update($data);

        return redirect()->route('admin.video-sources.index', [
            'sourceable_type' => $videoSource->sourceable_type,
            'sourceable_id' => $videoSource->sourceable_id
        ])->with('status', 'Video Source updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VideoSource $videoSource)
    {
        $type = $videoSource->sourceable_type;
        $id = $videoSource->sourceable_id;
        
        $videoSource->delete();

        return redirect()->route('admin.video-sources.index', [
            'sourceable_type' => $type,
            'sourceable_id' => $id
        ])->with('status', 'Video Source deleted successfully.');
    }
}
