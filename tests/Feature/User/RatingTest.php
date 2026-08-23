<?php

use App\Models\Content;
use App\Models\Rating;
use App\Models\User;

test('guest cannot rate content', function () {
    $content = Content::factory()->create();
    
    $this->post(route('ratings.store', $content), [
        'rating' => 8
    ])->assertRedirect(route('login'));
});

test('authenticated user can rate content', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    
    $this->actingAs($user)
        ->post(route('ratings.store', $content), [
            'rating' => 8
        ])
        ->assertSessionHasNoErrors();
        
    expect(Rating::where('user_id', $user->id)->where('content_id', $content->id)->value('rating'))->toBe(8);
});

test('rating updates average on content', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $content = Content::factory()->create();
    
    $this->actingAs($user1)->post(route('ratings.store', $content), ['rating' => 6]);
    $this->actingAs($user2)->post(route('ratings.store', $content), ['rating' => 10]);
    
    $content->refresh();
    
    expect((float)$content->average_rating)->toBe(8.0);
    expect($content->ratings_count)->toBe(2);
});

test('duplicate rating updates existing rating', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    
    $this->actingAs($user)->post(route('ratings.store', $content), ['rating' => 6]);
    $this->actingAs($user)->post(route('ratings.store', $content), ['rating' => 10]);
    
    expect(Rating::where('user_id', $user->id)->where('content_id', $content->id)->count())->toBe(1);
    expect(Rating::where('user_id', $user->id)->where('content_id', $content->id)->value('rating'))->toBe(10);
});

test('invalid rating is rejected', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    
    $this->actingAs($user)->post(route('ratings.store', $content), ['rating' => 11])->assertSessionHasErrors('rating');
    $this->actingAs($user)->post(route('ratings.store', $content), ['rating' => 0])->assertSessionHasErrors('rating');
});
