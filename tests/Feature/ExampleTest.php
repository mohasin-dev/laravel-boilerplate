<?php

test('renders the guest landing page with accessible application chrome', function () {
    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee(config('app.name'))
        ->assertSee('A production-ready Laravel foundation.')
        ->assertSee('href="#main-content"', false)
        ->assertSee('id="main-content"', false)
        ->assertSee('wire:navigate', false);
});
