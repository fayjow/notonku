<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Models\Content;
use App\Models\Genre;
use App\Enums\ContentType;
use App\Enums\ContentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Content::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('original_title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $contents = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.content.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        $types = ContentType::cases();
        $statuses = ContentStatus::cases();

        return view('admin.content.create', compact('genres', 'types', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContentRequest $request)
    {
        $data = $request->validated();
        
        $data['is_featured'] = $request->has('is_featured');
        $data['is_published'] = $request->has('is_published');
        
        if ($data['is_published']) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('content/posters', 'public');
        }

        if ($request->hasFile('backdrop')) {
            $data['backdrop_path'] = $request->file('backdrop')->store('content/backdrops', 'public');
        }

        $content = Content::create($data);

        if (isset($data['genres'])) {
            $content->genres()->sync($data['genres']);
        }

        return redirect()->route('admin.content.index')->with('status', 'Content created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $content)
    {
        $genres = Genre::orderBy('name')->get();
        $types = ContentType::cases();
        $statuses = ContentStatus::cases();

        return view('admin.content.edit', compact('content', 'genres', 'types', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContentRequest $request, Content $content)
    {
        $data = $request->validated();

        $data['is_featured'] = $request->has('is_featured');
        $data['is_published'] = $request->has('is_published');
        
        if ($data['is_published'] && !$content->is_published) {
            $data['published_at'] = now();
        } elseif (!$data['is_published']) {
            $data['published_at'] = null;
        }

        if ($request->hasFile('poster')) {
            if ($content->poster_path) {
                Storage::disk('public')->delete($content->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('content/posters', 'public');
        }

        if ($request->hasFile('backdrop')) {
            if ($content->backdrop_path) {
                Storage::disk('public')->delete($content->backdrop_path);
            }
            $data['backdrop_path'] = $request->file('backdrop')->store('content/backdrops', 'public');
        }

        $content->update($data);

        if (isset($data['genres'])) {
            $content->genres()->sync($data['genres']);
        } else {
            $content->genres()->detach();
        }

        return redirect()->route('admin.content.index')->with('status', 'Content updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $content)
    {
        if ($content->poster_path) {
            Storage::disk('public')->delete($content->poster_path);
        }
        if ($content->backdrop_path) {
            Storage::disk('public')->delete($content->backdrop_path);
        }

        $content->delete();

        return redirect()->route('admin.content.index')->with('status', 'Content deleted successfully.');
    }
}
