<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Episode;
use App\Services\WatchHistoryService;
use Illuminate\Http\Request;

class WatchHistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = $request->user()->watchHistories()
            ->with(['content', 'episode'])
            ->orderBy('last_watched_at', 'desc')
            ->paginate(24);

        return view('public.history', compact('histories'));
    }

    public function store(Request $request, WatchHistoryService $watchHistoryService)
    {
        $validated = $request->validate([
            'content_id' => ['required_without:episode_id', 'nullable', 'exists:contents,id'],
            'episode_id' => ['required_without:content_id', 'nullable', 'exists:episodes,id'],
            'progress_seconds' => ['required', 'integer', 'min:0'],
            'duration_seconds' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['progress_seconds'] > $validated['duration_seconds']) {
            return response()->json(['message' => 'Progress cannot exceed duration.'], 422);
        }

        $contentId = $validated['content_id'] ?? null;
        if (!$contentId && $validated['episode_id']) {
            $episode = Episode::findOrFail($validated['episode_id']);
            $contentId = $episode->season->content_id;
        }

        $watchHistoryService->updateProgress(
            $request->user()->id,
            $contentId,
            $validated['episode_id'] ?? null,
            $validated['progress_seconds'],
            $validated['duration_seconds']
        );

        return response()->json(['message' => 'Progress saved successfully.']);
    }

    public function destroy(Request $request, \App\Models\WatchHistory $history)
    {
        if ($history->user_id !== $request->user()->id) {
            abort(403);
        }

        $history->delete();

        return back()->with('status', 'History removed successfully.');
    }
}
