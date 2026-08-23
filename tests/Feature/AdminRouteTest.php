<?php

use App\Models\User;

it('redirects unauthenticated users from admin area', function () {
    $response = $this->get('/admin');

    $response->assertRedirect('/login');
});

it('forbids normal users from accessing admin area', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $response = $this->actingAs($user)->get('/admin');

    $response->assertForbidden();
});

it('allows admin users to access admin area', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->get('/admin');

    $response->assertOk();
    $response->assertViewIs('admin.dashboard');
});
