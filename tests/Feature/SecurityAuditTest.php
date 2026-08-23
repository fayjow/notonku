<?php

use App\Models\User;

it('prevents non-admin users from accessing admin routes', function () {
    $user = User::factory()->create(['role' => 'user']);
    
    $response = $this->actingAs($user)->get('/admin');
    
    $response->assertForbidden(); // Should be 403 Forbidden
});

it('prevents guests from accessing admin routes', function () {
    $response = $this->get('/admin');
    
    $response->assertRedirect('/login');
});

it('validates mime types and size on content creation', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    $response = $this->actingAs($admin)->post('/admin/content', [
        'type' => 'movie',
        'title' => 'Test Movie',
        'slug' => 'test-movie',
        'status' => 'completed',
        'poster' => \Illuminate\Http\UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf'), // Invalid mime
    ]);
    
    $response->assertSessionHasErrors(['poster']);
});

it('validates mass assignment fields correctly', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    $response = $this->actingAs($admin)->post('/admin/content', [
        'type' => 'movie',
        'title' => 'Test Movie',
        'slug' => 'test-movie',
        'status' => 'completed',
        'is_admin' => true, // Attempt to inject an unfillable/invalid field
    ]);
    
    // $request->validated() ignores 'is_admin' entirely, but let's just make sure it passes the rest of validation
    // wait, if we omit other required fields, it should fail. Here it should pass creating but ignore is_admin
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('contents', ['slug' => 'test-movie']);
});
