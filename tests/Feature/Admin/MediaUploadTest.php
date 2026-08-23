<?php

use App\Models\User;
use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->user = User::factory()->create(['role' => 'user']);
});

it('allows admin to upload poster and backdrop when creating content', function () {
    $poster = UploadedFile::fake()->image('poster.jpg');
    $backdrop = UploadedFile::fake()->image('backdrop.png');

    $data = [
        'title' => 'Test Upload Content',
        'slug' => 'test-upload-content',
        'type' => 'movie',
        'status' => 'completed',
        'is_published' => '1',
        'poster' => $poster,
        'backdrop' => $backdrop,
    ];

    $this->actingAs($this->admin)
         ->post(route('admin.content.store'), $data)
         ->assertRedirect(route('admin.content.index'));

    $content = Content::where('slug', 'test-upload-content')->first();
    
    expect($content->poster_path)->not->toBeNull()
          ->and($content->backdrop_path)->not->toBeNull();

    Storage::disk('public')->assertExists($content->poster_path);
    Storage::disk('public')->assertExists($content->backdrop_path);
});

it('cleans up old images when admin replaces them', function () {
    $content = Content::factory()->create([
        'poster_path' => 'content/posters/old-poster.jpg',
        'backdrop_path' => 'content/backdrops/old-backdrop.jpg',
    ]);
    
    // Fake the old files existing
    Storage::disk('public')->put($content->poster_path, 'old content');
    Storage::disk('public')->put($content->backdrop_path, 'old content');

    $newPoster = UploadedFile::fake()->image('new-poster.webp');

    $data = [
        'title' => 'Updated Title',
        'slug' => $content->slug,
        'type' => 'movie',
        'status' => 'completed',
        'poster' => $newPoster,
    ];

    $this->actingAs($this->admin)
         ->put(route('admin.content.update', $content), $data)
         ->assertRedirect(route('admin.content.index'));

    $content->refresh();
    
    // Check old poster deleted, new poster exists
    Storage::disk('public')->assertMissing('content/posters/old-poster.jpg');
    Storage::disk('public')->assertExists($content->poster_path);
    
    // Check backdrop wasn't deleted because we didn't replace it
    Storage::disk('public')->assertExists($content->backdrop_path);
});

it('cleans up images when content is deleted', function () {
    $content = Content::factory()->create([
        'poster_path' => 'content/posters/delete-poster.jpg',
    ]);
    
    Storage::disk('public')->put($content->poster_path, 'content');

    $this->actingAs($this->admin)
         ->delete(route('admin.content.destroy', $content))
         ->assertRedirect(route('admin.content.index'));

    Storage::disk('public')->assertMissing('content/posters/delete-poster.jpg');
});

it('rejects invalid image files', function () {
    $invalidFile = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    $data = [
        'title' => 'Invalid File',
        'slug' => 'invalid-file',
        'type' => 'movie',
        'status' => 'completed',
        'poster' => $invalidFile,
    ];

    $this->actingAs($this->admin)
         ->post(route('admin.content.store'), $data)
         ->assertSessionHasErrors('poster');
});

it('allows admin to upload episode thumbnail', function () {
    $content = Content::factory()->create(['type' => 'series']);
    $season = Season::factory()->create(['content_id' => $content->id, 'season_number' => 1]);
    
    $thumbnail = UploadedFile::fake()->image('thumb.jpg');

    $data = [
        'episode_number' => 1,
        'title' => 'Pilot',
        'thumbnail' => $thumbnail,
    ];

    $this->actingAs($this->admin)
         ->post(route('admin.content.seasons.episodes.store', [$content, $season]), $data)
         ->assertRedirect(route('admin.content.seasons.episodes.index', [$content, $season]));

    $episode = Episode::where('season_id', $season->id)->first();
    
    expect($episode->thumbnail_path)->not->toBeNull();
    Storage::disk('public')->assertExists($episode->thumbnail_path);
});

it('prevents non-admin from uploading media', function () {
    $poster = UploadedFile::fake()->image('poster.jpg');

    $data = [
        'title' => 'Test Upload',
        'slug' => 'test',
        'type' => 'movie',
        'status' => 'completed',
        'poster' => $poster,
    ];

    $this->actingAs($this->user)
         ->post(route('admin.content.store'), $data)
         ->assertForbidden();
});
