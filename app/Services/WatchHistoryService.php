<?php

namespace App\Services;

use App\Models\WatchHistory;
use Illuminate\Support\Carbon;

class WatchHistoryService
{
    /**
     * Update or create a watch history record for a user and content/episode.
     */
    public function updateProgress(int $userId, int $contentId, ?int $episodeId, int $progressSeconds, ?int $durationSeconds = null): WatchHistory
    {
        $query = WatchHistory::where('user_id', $userId)
            ->where('content_id', $contentId);
            
        if ($episodeId !== null) {
            $query->where('episode_id', $episodeId);
        } else {
            $query->whereNull('episode_id');
        }

        $history = $query->first();

        $isCompleted = false;
        if ($durationSeconds !== null && $durationSeconds > 0) {
            // Consider completed if watched more than 90%
            $isCompleted = ($progressSeconds / $durationSeconds) >= 0.9;
        }

        if ($history) {
            $history->update([
                'progress_seconds' => $progressSeconds,
                'duration_seconds' => $durationSeconds ?? $history->duration_seconds,
                'is_completed' => $history->is_completed || $isCompleted,
                'last_watched_at' => Carbon::now(),
            ]);
            
            return $history;
        }

        return WatchHistory::create([
            'user_id' => $userId,
            'content_id' => $contentId,
            'episode_id' => $episodeId,
            'progress_seconds' => $progressSeconds,
            'duration_seconds' => $durationSeconds,
            'is_completed' => $isCompleted,
            'last_watched_at' => Carbon::now(),
        ]);
    }

    /**
     * Mark a watch history as completed.
     */
    public function markAsCompleted(int $userId, int $contentId, ?int $episodeId): WatchHistory
    {
        $query = WatchHistory::where('user_id', $userId)
            ->where('content_id', $contentId);
            
        if ($episodeId !== null) {
            $query->where('episode_id', $episodeId);
        } else {
            $query->whereNull('episode_id');
        }

        $history = $query->first();

        if ($history) {
            $history->update([
                'is_completed' => true,
                'last_watched_at' => Carbon::now(),
            ]);
            return $history;
        }

        return WatchHistory::create([
            'user_id' => $userId,
            'content_id' => $contentId,
            'episode_id' => $episodeId,
            'progress_seconds' => 0,
            'is_completed' => true,
            'last_watched_at' => Carbon::now(),
        ]);
    }
}
