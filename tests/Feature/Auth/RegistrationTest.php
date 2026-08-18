<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('guests can view the registration screen', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee('Create your account')
        ->assertSee('wire:submit="register"', false);
});

test('guests can create an account', function () {
    Event::fake([Registered::class]);

    Livewire::test(Register::class)
        ->set('name', '  Jane Doe  ')
        ->set('email', 'jane@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('verification.notice'));

    $user = User::query()->where('email', 'jane@example.com')->firstOrFail();

    expect($user->name)
        ->toBe('Jane Doe')
        ->and($user->is_active)
        ->toBeTrue()
        ->and(Hash::check('password', $user->password))
        ->toBeTrue();

    $this->assertAuthenticatedAs($user);
    Event::assertDispatched(Registered::class, fn (Registered $event): bool => $event->user->is($user));
});

test('registration requires a unique lowercase email address', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    Livewire::test(Register::class)
        ->set('name', 'Jane Doe')
        ->set('email', 'existing@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('register')
        ->assertHasErrors(['email' => ['unique']]);

    Livewire::test(Register::class)
        ->set('name', 'Jane Doe')
        ->set('email', 'JANE@EXAMPLE.COM')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('register')
        ->assertHasErrors(['email' => ['lowercase']]);
});

test('registration requires password confirmation', function () {
    Livewire::test(Register::class)
        ->set('name', 'Jane Doe')
        ->set('email', 'jane@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'different-password')
        ->call('register')
        ->assertHasErrors(['password' => ['confirmed']]);

    $this->assertGuest();
});

test('authenticated users are redirected away from registration', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('register'))
        ->assertRedirect(route('home'));
});
