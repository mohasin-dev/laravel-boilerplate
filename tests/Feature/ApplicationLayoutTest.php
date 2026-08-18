<?php

use Illuminate\Support\Facades\Blade;

test('renders the application layout with page slots and navigation controls', function () {
    $html = Blade::render(<<<'BLADE'
        <x-layouts.app title="Users">
            <x-slot:header>
                <h1>User management</h1>
            </x-slot:header>

            <x-slot:actions>
                <button type="button">Create user</button>
            </x-slot:actions>

            <p>Application content</p>
        </x-layouts.app>
    BLADE);

    expect($html)
        ->toContain('<title>Users · '.config('app.name').'</title>')
        ->toContain('aria-label="Application navigation"')
        ->toContain('aria-label="Open navigation"')
        ->toContain('User management')
        ->toContain('Create user')
        ->toContain('Application content')
        ->toContain('id="main-content"');
});
