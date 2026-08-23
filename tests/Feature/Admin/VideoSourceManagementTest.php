<?php

use App\Models\User;
use App\Models\Content;
use App\Models\VideoSource;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->user = User::factory()->create(['role' => 'user']);
    $this->content = Content::factory()->create(['type' => 'movie']);
});

it('allows admin to create MP4 video source', function () {
    $data = [
        'sourceable_type' => 'content',
        'sourceable_id' => $this->content->id,
        'provider' => 'mp4',
        'server_name' => 'VidCloud',
        'url' => 'https://example.com/video.mp4',
        'quality' => '1080p',
        'language' => 'English Sub',
        'is_active' => '1',
    ];

    $this->actingAs($this->admin)
         ->post(route('admin.video-sources.store'), $data)
         ->assertRedirect();

    $this->assertDatabaseHas('video_sources', [
        'provider' => 'mp4',
        'server_name' => 'VidCloud',
        'url' => 'https://example.com/video.mp4'
    ]);
});

it('allows admin to create HLS video source', function () {
    $data = [
        'sourceable_type' => 'content',
        'sourceable_id' => $this->content->id,
        'provider' => 'hls',
        'server_name' => 'StreamServer',
        'url' => 'https://example.com/stream.m3u8',
        'is_active' => '1',
    ];

    $this->actingAs($this->admin)
         ->post(route('admin.video-sources.store'), $data)
         ->assertRedirect();

    $this->assertDatabaseHas('video_sources', ['provider' => 'hls', 'server_name' => 'StreamServer']);
});

it('allows admin to create Embed video source', function () {
    $data = [
        'sourceable_type' => 'content',
        'sourceable_id' => $this->content->id,
        'provider' => 'embed',
        'server_name' => 'YouTube',
        'url' => 'https://youtube.com/embed/12345',
        'is_active' => '1',
    ];

    $this->actingAs($this->admin)
         ->post(route('admin.video-sources.store'), $data)
         ->assertRedirect();

    $this->assertDatabaseHas('video_sources', ['provider' => 'embed']);
});

it('rejects invalid provider type', function () {
    $data = [
        'sourceable_type' => 'content',
        'sourceable_id' => $this->content->id,
        'provider' => 'invalid_type',
        'server_name' => 'Test',
        'url' => 'https://example.com/video.mp4',
    ];

    $this->actingAs($this->admin)
         ->post(route('admin.video-sources.store'), $data)
         ->assertSessionHasErrors('provider');
});

it('prevents normal users from managing video sources', function () {
    $data = [
        'sourceable_type' => 'content',
        'sourceable_id' => $this->content->id,
        'provider' => 'mp4',
        'server_name' => 'Test',
        'url' => 'https://example.com/video.mp4',
    ];

    $this->actingAs($this->user)
         ->post(route('admin.video-sources.store'), $data)
         ->assertForbidden();
});
