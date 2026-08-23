<?php

use App\Models\User;

it('renders custom 404 page', function () {
    $response = $this->get('/non-existent-route-12345');
    
    $response->assertNotFound();
    $response->assertSee('404');
    $response->assertSee('Page not found');
});
