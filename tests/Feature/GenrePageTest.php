<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenrePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_genres_index_loads_successfully()
    {
        Genre::factory()->count(3)->create();

        $response = $this->get('/genres');
        $response->assertStatus(200);
        $response->assertViewHas('genres');
    }

    public function test_genre_show_loads_successfully_with_content()
    {
        $genre = Genre::factory()->create(['name' => 'Horror']);
        $content = Content::factory()->create(['title' => 'Scary Movie', 'is_published' => true]);
        $content->genres()->attach($genre);

        $response = $this->get('/genres/' . $genre->slug);
        $response->assertStatus(200);
        $response->assertSee('Horror');
        $response->assertSee('Scary Movie');
    }

    public function test_genre_show_hides_unpublished_content()
    {
        $genre = Genre::factory()->create(['name' => 'Horror']);
        $content = Content::factory()->create(['title' => 'Scary Movie Draft', 'is_published' => false]);
        $content->genres()->attach($genre);

        $response = $this->get('/genres/' . $genre->slug);
        $response->assertStatus(200);
        $response->assertDontSee('Scary Movie Draft');
    }
}
