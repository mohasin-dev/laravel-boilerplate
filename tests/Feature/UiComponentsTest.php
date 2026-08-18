<?php

use Illuminate\Support\Facades\Blade;

test('renders button variants as buttons and links', function () {
    $button = Blade::render('<x-ui.button variant="danger" size="sm" wire:click="remove">Delete</x-ui.button>');
    $link = Blade::render('<x-ui.button variant="secondary" href="/users">Users</x-ui.button>');
    $disabledLink = Blade::render('<x-ui.button href="/users" disabled>Users</x-ui.button>');

    expect($button)
        ->toContain('<button type="button"')
        ->toContain('bg-red-600')
        ->toContain('px-3 py-2 text-xs')
        ->toContain('wire:click="remove"')
        ->and($link)
        ->toContain('<a')
        ->toContain('href="/users"')
        ->toContain('border-slate-300')
        ->and($disabledLink)
        ->not->toContain('href="/users"')
        ->toContain('aria-disabled="true"');
});

test('renders accessible form controls and validation feedback', function () {
    $html = Blade::render(<<<'BLADE'
        <x-form.label for="email" required>Email</x-form.label>
        <x-form.input id="email" type="email" wire:model="email" invalid aria-describedby="email-error" />
        <x-form.error id="email-error" :messages="['Email is required.']" />
    BLADE);

    expect($html)
        ->toContain('for="email"')
        ->toContain('required</span>')
        ->toContain('type="email"')
        ->toContain('wire:model="email"')
        ->toContain('aria-invalid="true"')
        ->toContain('aria-describedby="email-error"')
        ->toContain('Email is required.');
});

test('renders card slots and dismissible alerts', function () {
    $card = Blade::render(<<<'BLADE'
        <x-ui.card>
            <x-slot:header>Profile</x-slot:header>
            Details
            <x-slot:footer>Last updated today</x-slot:footer>
        </x-ui.card>
    BLADE);
    $alert = Blade::render('<x-ui.alert variant="danger" title="Error" dismissible>Try again.</x-ui.alert>');

    expect($card)
        ->toContain('<header')
        ->toContain('Profile')
        ->toContain('Details')
        ->toContain('<footer')
        ->toContain('Last updated today')
        ->and($alert)
        ->toContain('role="alert"')
        ->toContain('x-data="{ visible: true }"')
        ->toContain('aria-label="Dismiss message"')
        ->toContain('Try again.');
});
