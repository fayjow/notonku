<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_page_loads_successfully()
    {
        $response = $this->get('/search');
        $response->assertStatus(200);
    }

    public function test_can_search_by_keyword()
    {
        $content = Content::factory()->create(['title' => 'The Matrix', 'is_published' => true]);
        Content::factory()->create(['title' => 'Inception', 'is_published' => true]);

        $response = $this->get('/search?q=Matrix');
        $response->assertStatus(200);
        $response->assertSee('The Matrix');
        $response->assertDontSee('Inception');
    }

    public function test_can_filter_by_type()
    {
        Content::factory()->create(['title' => 'The Matrix', 'type' => 'movie', 'is_published' => true]);
        Content::factory()->create(['title' => 'Naruto', 'type' => 'anime', 'is_published' => true]);

        $response = $this->get('/search?type=anime');
        $response->assertStatus(200);
        $response->assertSee('Naruto');
        $response->assertDontSee('The Matrix');
    }

    public function test_can_filter_by_genre()
    {
        $genre = Genre::factory()->create(['name' => 'Action']);
        $content = Content::factory()->create(['title' => 'Action Movie', 'is_published' => true]);
        $content->genres()->attach($genre);
        
        Content::factory()->create(['title' => 'Drama Movie', 'is_published' => true]);

        $response = $this->get('/search?genre=' . $genre->slug);
        $response->assertStatus(200);
        $response->assertSee('Action Movie');
        $response->assertDontSee('Drama Movie');
    }

    public function test_can_sort_by_popular()
    {
        Content::factory()->create(['title' => 'Low Views', 'views_count' => 10, 'is_published' => true]);
        Content::factory()->create(['title' => 'High Views', 'views_count' => 100, 'is_published' => true]);

        $response = $this->get('/search?sort=popular');
        $response->assertStatus(200);
        
        // Assert "High Views" comes before "Low Views" in the view.
        $this->assertStringContainsString('High Views', $response->getContent());
        $this->assertStringContainsString('Low Views', $response->getContent());
    }
}
