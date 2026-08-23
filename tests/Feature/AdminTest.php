<?php

use App\Models\User;
use App\Models\Content;
use App\Models\Season;
use App\Models\Genre;

it('redirects non-admin users from admin dashboard', function () {
    $user = User::factory()->create(['role' => 'user']);
    
    $this->actingAs($user)
         ->get('/admin')
         ->assertForbidden();
});

it('allows admin users to access admin dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    $this->actingAs($admin)
         ->get('/admin')
         ->assertStatus(200);
});

it('allows admin to see content list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Content::factory()->count(3)->create();
    
    $this->actingAs($admin)
         ->get(route('admin.content.index'))
         ->assertStatus(200);
});

it('allows admin to create content', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    $data = [
        'title' => 'Test Content',
        'slug' => 'test-content',
        'type' => 'movie',
        'status' => 'completed',
        'is_published' => '1',
    ];
    
    $this->actingAs($admin)
         ->post(route('admin.content.store'), $data)
         ->assertRedirect(route('admin.content.index'));
         
    $this->assertDatabaseHas('contents', ['title' => 'Test Content']);
});

it('allows admin to view seasons for series', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $content = Content::factory()->create(['type' => 'series']);
    Season::factory()->count(2)
        ->sequence(fn ($sequence) => ['season_number' => $sequence->index + 1])
        ->create(['content_id' => $content->id]);
    
    $this->actingAs($admin)
         ->get(route('admin.content.seasons.index', $content))
         ->assertStatus(200);
});
