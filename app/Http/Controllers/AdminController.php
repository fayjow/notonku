<?php

namespace App\Http\Controllers;

use App\Enums\ContentType;
use App\Models\Content;
use App\Models\Episode;
use App\Models\User;
use App\Models\VideoSource;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $typeCounts = Content::selectRaw('type, count(*) as count')->groupBy('type')->pluck('count', 'type')->toArray();
        
        $stats = [
            'total_content' => Content::count(),
            'total_users' => User::count(),
            'total_sources' => VideoSource::count(),
            'total_episodes' => Episode::count(),
            
            'movies' => $typeCounts[ContentType::Movie->value] ?? 0,
            'series' => $typeCounts[ContentType::Series->value] ?? 0,
            'anime' => $typeCounts[ContentType::Anime->value] ?? 0,
            'donghua' => $typeCounts[ContentType::Donghua->value] ?? 0,
        ];

        $recentContent = Content::latest()->limit(5)->get();
        $recentUsers = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentContent', 'recentUsers'));
    }
}
