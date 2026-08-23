<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $ratings = $request->user()->ratings()
            ->with('content')
            ->orderBy('updated_at', 'desc')
            ->paginate(24);

        return view('public.ratings', compact('ratings'));
    }

    public function store(Request $request, Content $content)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        Rating::updateOrCreate(
            ['user_id' => $request->user()->id, 'content_id' => $content->id],
            ['rating' => $validated['rating']]
        );

        $content->updateAverageRating();

        return back()->with('status', 'Rating saved successfully.');
    }
}
