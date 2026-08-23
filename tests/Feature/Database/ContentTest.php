<?php
use App\Models\Content;
use App\Enums\ContentType;
use App\Enums\ContentStatus;

it('can create content and cast enums', function () {
    $content = Content::factory()->create([
        'type' => ContentType::Movie,
        'status' => ContentStatus::Ongoing,
        'is_featured' => true,
    ]);
    
    expect($content->type)->toBe(ContentType::Movie)
        ->and($content->status)->toBe(ContentStatus::Ongoing)
        ->and($content->is_featured)->toBeTrue();
});