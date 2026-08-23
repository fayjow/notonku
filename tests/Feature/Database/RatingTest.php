<?php
use App\Models\User;
use App\Models\Content;
use Illuminate\Database\UniqueConstraintViolationException;

it('user can rate content uniquely', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();
    
    $user->ratings()->create([
        'content_id' => $content->id,
        'rating' => 8,
    ]);
    
    expect($user->ratings)->toHaveCount(1)
        ->and($user->ratings->first()->rating)->toBe(8);
        
    $this->expectException(UniqueConstraintViolationException::class);
    $user->ratings()->create([
        'content_id' => $content->id,
        'rating' => 10,
    ]);
});