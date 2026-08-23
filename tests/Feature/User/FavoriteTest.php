<?php

use App\Models\Content;
use App\Models\Favorite;
use App\Models\User;

test('guest cannot favorite content', function () {
    $content = Content::factory()->create();
    
    $this->postJson(route('favorites.store', $content))
        ->assertUnauthorized();
});

test('authenticated user can favorite content', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    
    $this->actingAs($user)
        ->postJson(route('favorites.store', $content))
        ->assertSuccessful();
        
    expect(Favorite::where('user_id', $user->id)->where('content_id', $content->id)->exists())->toBeTrue();
});

test('user can remove favorite', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    Favorite::create(['user_id' => $user->id, 'content_id' => $content->id]);
    
    $this->actingAs($user)
        ->deleteJson(route('favorites.destroy', $content))
        ->assertSuccessful();
        
    expect(Favorite::where('user_id', $user->id)->where('content_id', $content->id)->exists())->toBeFalse();
});

test('duplicate favorite is prevented without crashing', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    Favorite::create(['user_id' => $user->id, 'content_id' => $content->id]);
    
    $this->actingAs($user)
        ->postJson(route('favorites.store', $content))
        ->assertSuccessful();
        
    expect(Favorite::where('user_id', $user->id)->where('content_id', $content->id)->count())->toBe(1);
});

test('favorites page works and only shows own favorites', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $content1 = Content::factory()->create(['is_published' => true, 'published_at' => now()->subDay()]);
    $content2 = Content::factory()->create(['is_published' => true, 'published_at' => now()->subDay()]);
    
    Favorite::create(['user_id' => $user1->id, 'content_id' => $content1->id]);
    Favorite::create(['user_id' => $user2->id, 'content_id' => $content2->id]);
    
    $this->actingAs($user1)
        ->get(route('favorites.index'))
        ->assertSuccessful()
        ->assertSee($content1->title)
        ->assertDontSee($content2->title);
});
