<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

test('guests can view the login screen', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Welcome back')
        ->assertSee('wire:submit="authenticate"', false);
});

test('active users can log in', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->set('remember', true)
        ->call('authenticate')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

test('login honors the intended destination', function () {
    $user = User::factory()->create();
    session()->put('url.intended', '/intended-page');

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertRedirect('/intended-page');
});

test('invalid credentials are rejected without revealing which value failed', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'incorrect-password')
        ->call('authenticate')
        ->assertHasErrors(['email'])
        ->assertSet('password', '');

    $this->assertGuest();
});

test('inactive users cannot log in', function () {
    $user = User::factory()->inactive()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertHasErrors(['email']);

    $this->assertGuest();
});

test('login attempts are rate limited', function () {
    $user = User::factory()->create();
    $throttleKey = strtolower($user->email).'|127.0.0.1';

    RateLimiter::clear($throttleKey);

    for ($attempt = 0; $attempt < 5; $attempt++) {
        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'incorrect-password')
            ->call('authenticate');
    }

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'incorrect-password')
        ->call('authenticate')
        ->assertHasErrors(['email']);

    expect(RateLimiter::tooManyAttempts($throttleKey, 5))->toBeTrue();
});

test('authenticated users can log out', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});

test('authenticated users are redirected away from the login screen', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('login'))
        ->assertRedirect(route('home'));
});
