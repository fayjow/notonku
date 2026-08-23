<?php

use App\Models\User;

it('returns 200 for public pages', function () {
    $this->get(route('home'))->assertOk();
    $this->get(route('movies'))->assertOk();
    $this->get(route('series'))->assertOk();
    $this->get(route('anime'))->assertOk();
    $this->get(route('donghua'))->assertOk();
});

it('renders public pages successfully for authenticated users', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)->get(route('home'))->assertOk();
    $this->actingAs($user)->get(route('movies'))->assertOk();
    $this->actingAs($user)->get(route('series'))->assertOk();
    $this->actingAs($user)->get(route('anime'))->assertOk();
    $this->actingAs($user)->get(route('donghua'))->assertOk();
});

it('guest users see login and register links', function () {
    $response = $this->get(route('home'));
    $response->assertSee(route('login'));
    $response->assertSee(route('register'));
});

it('authenticated users see user profile link', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    
    $response = $this->actingAs($user)->get(route('home'));
    $response->assertSee('John Doe');
    $response->assertSee(route('profile.edit'));
});

it('admin link is only visible to admins', function () {
    $user = User::factory()->create(['role' => 'user']);
    $admin = User::factory()->create(['role' => 'admin']);
    
    $this->actingAs($user)->get(route('home'))->assertDontSee(route('admin.dashboard'));
    $this->actingAs($admin)->get(route('home'))->assertSee(route('admin.dashboard'));
});

it('contains theme toggle markup', function () {
    $response = $this->get(route('home'));
    $response->assertSee('darkMode = !darkMode', false);
});
