<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Episode;
use App\Models\VideoSource;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function downloadMovie(Content $content, VideoSource $source)
    {
        return $this->processDownload($content, null, $source);
    }

    public function downloadEpisode(Content $content, Episode $episode, VideoSource $source)
    {
        return $this->processDownload($content, $episode, $source);
    }

    private function processDownload(Content $content, ?Episode $episode, VideoSource $source)
    {
        // 1. Verify parent content is published
        if (!$content->is_published || ($content->published_at && $content->published_at->isFuture())) {
            abort(404);
        }

        // 2. For episodes, verify episode is published and belongs to content
        if ($episode) {
            if (!$episode->is_published || ($episode->published_at && $episode->published_at->isFuture())) {
                abort(404);
            }
            if ($episode->season->content_id !== $content->id) {
                abort(404);
            }
        } else {
            // If it's a movie, it shouldn't have an episode
            if ($content->type->value !== 'movie') {
                abort(404);
            }
        }

        // 3. Verify source belongs to the requested media (IDOR check)
        $expectedType = $episode ? 'episode' : 'content';
        $expectedId = $episode ? $episode->id : $content->id;

        if ($source->sourceable_type !== $expectedType || $source->sourceable_id !== $expectedId) {
            abort(404);
        }

        // 4. Verify source is active
        if (!$source->is_active) {
            abort(404);
        }

        // 5. Verify source is downloadable
        if (!$source->is_downloadable) {
            abort(404);
        }

        // 6. Verify provider is mp4
        if ($source->provider !== 'mp4') {
            abort(404);
        }

        // Redirect to the actual URL to initiate download
        // In a real-world scenario with S3, this might generate a signed URL.
        // For direct URLs, we can redirect or stream.
        return redirect()->away($source->url);
    }
}
