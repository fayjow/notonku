<?php
namespace App\Services;

use App\Models\Content;

class ContentViewService
{
    public function increment(Content $content): void
    {
        // Simple increment for Phase 4.
        // Can be easily upgraded to daily/unique views later.
        $content->increment('views_count');
    }
}