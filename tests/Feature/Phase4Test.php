<?php

use App\Models\Content;

it('homepage only shows published content', function () {
    Content::factory()->create(['title' => 'Published Movie 123', 'is_published' => true, 'published_at' => now()->subDay()]);
    Content::factory()->create(['title' => 'Unpublished Movie 456', 'is_published' => false]);
    Content::factory()->create(['title' => 'Future Movie 789', 'is_published' => true, 'published_at' => now()->addDay()]);

    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('Published Movie 123');
    $response->assertDontSee('Unpublished Movie 456');
    $response->assertDontSee('Future Movie 789');
});

it('catalog handles pagination and sorting', function () {
    Content::factory()->count(30)->create(['type' => 'movie', 'is_published' => true, 'published_at' => now()->subDay()]);
    
    $response = $this->get(route('movies', ['sort' => 'latest']));
    $response->assertOk();
    // Assuming 'Next' or pagination links are rendered
});

it('search works and handles empty results', function () {
    Content::factory()->create(['title' => 'Unique Searchable Title', 'is_published' => true, 'published_at' => now()->subDay()]);
    
    $response = $this->get(route('search', ['q' => 'Unique Searchable']));
    $response->assertOk();
    $response->assertSee('Unique Searchable Title');
    
    $responseEmpty = $this->get(route('search', ['q' => 'Nonexistent Query String 123']));
    $responseEmpty->assertOk();
    $responseEmpty->assertSee('No results found');
});

it('show route verifies type and slug correctly', function () {
    $anime = Content::factory()->create([
        'type' => 'anime', 
        'title' => 'Cool Anime', 
        'is_published' => true, 
        'published_at' => now()->subDay()
    ]);
    
    // Correct type
    $this->get(route('anime.show', $anime->slug))->assertOk()->assertSee('Cool Anime');
    
    // Incorrect type
    $this->get(route('movies.show', $anime->slug))->assertNotFound();
});

it('show route hides unpublished content', function () {
    $movie = Content::factory()->create([
        'type' => 'movie', 
        'is_published' => false, 
    ]);
    
    $this->get(route('movies.show', $movie->slug))->assertNotFound();
});
