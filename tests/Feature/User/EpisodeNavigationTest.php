<?php

use App\Models\Content;
use App\Models\Episode;
use App\Models\Season;
use App\Models\VideoSource;

test('renders next and previous episode correctly', function () {
    $content = Content::factory()->create(['type' => 'series', 'is_published' => true, 'published_at' => now()->subDay()]);
    $season = Season::factory()->create(['content_id' => $content->id, 'season_number' => 1]);
    
    $ep1 = Episode::factory()->create(['season_id' => $season->id, 'episode_number' => 1, 'is_published' => true, 'published_at' => now()->subDay()]);
    $ep2 = Episode::factory()->create(['season_id' => $season->id, 'episode_number' => 2, 'is_published' => true, 'published_at' => now()->subDay()]);
    $ep3 = Episode::factory()->create(['season_id' => $season->id, 'episode_number' => 3, 'is_published' => true, 'published_at' => now()->subDay()]);
    
    // Create VideoSources for each episode so the player renders
    \App\Models\VideoSource::factory()->create(['sourceable_id' => $ep1->id, 'sourceable_type' => $ep1->getMorphClass()]);
    \App\Models\VideoSource::factory()->create(['sourceable_id' => $ep2->id, 'sourceable_type' => $ep2->getMorphClass()]);
    \App\Models\VideoSource::factory()->create(['sourceable_id' => $ep3->id, 'sourceable_type' => $ep3->getMorphClass()]);

    // Episode 1 (No Prev, Has Next)
    $this->get(route('watch.series', ['content' => $content->slug, 'episode' => $ep1->id]))
        ->assertSuccessful()
        ->assertDontSee('title="Previous Episode"', false)
        ->assertSee('title="Next Episode"', false);
        
    // Episode 2 (Has Prev, Has Next)
    $this->get(route('watch.series', ['content' => $content->slug, 'episode' => $ep2->id]))
        ->assertSuccessful()
        ->assertSee('title="Previous Episode"', false)
        ->assertSee('title="Next Episode"', false);
        
    // Episode 3 (Has Prev, No Next)
    $this->get(route('watch.series', ['content' => $content->slug, 'episode' => $ep3->id]))
        ->assertSuccessful()
        ->assertSee('title="Previous Episode"', false)
        ->assertDontSee('title="Next Episode"', false);
});
