<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\VerifyEmail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

test('unverified users can view the verification notice', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('verification.notice'))
        ->assertOk()
        ->assertSee('Verify your email address')
        ->assertSee($user->email);
});

test('guests cannot view the verification notice', function () {
    $this->get(route('verification.notice'))
        ->assertRedirect(route('login'));
});

test('unverified users can request another verification email', function () {
    Notification::fake();
    $user = User::factory()->unverified()->create();

    Livewire::actingAs($user)
        ->test(VerifyEmail::class)
        ->call('sendVerificationEmail')
        ->assertHasNoErrors()
        ->assertSet('verificationLinkSent', true);

    Notification::assertSentTo($user, VerifyEmailNotification::class);
});

test('users can verify their email with a valid signed link', function () {
    Event::fake([Verified::class]);
    $user = User::factory()->unverified()->create();
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())],
    );

    $this->actingAs($user)
        ->get($verificationUrl)
        ->assertRedirect(route('home', ['verified' => 1]));

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    Event::assertDispatched(Verified::class);
});

test('email verification requires a valid signature and matching user', function () {
    $user = User::factory()->unverified()->create();
    $otherUser = User::factory()->unverified()->create();
    $otherUserUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $otherUser->getKey(), 'hash' => sha1($otherUser->getEmailForVerification())],
    );

    $this->actingAs($user)
        ->get(route('verification.verify', ['id' => $user->getKey(), 'hash' => 'invalid']))
        ->assertForbidden();

    $this->get($otherUserUrl)->assertForbidden();
});

test('verification email requests are rate limited', function () {
    Notification::fake();
    $user = User::factory()->unverified()->create();
    $throttleKey = 'verification-email:'.$user->getAuthIdentifier();
    RateLimiter::clear($throttleKey);

    $component = Livewire::actingAs($user)->test(VerifyEmail::class);

    for ($attempt = 0; $attempt < 3; $attempt++) {
        $component->call('sendVerificationEmail')->assertHasNoErrors();
    }

    $component
        ->call('sendVerificationEmail')
        ->assertHasErrors(['verification']);

    expect(RateLimiter::tooManyAttempts($throttleKey, 3))->toBeTrue();
});

test('unverified users are directed to the verification notice after login', function () {
    $user = User::factory()->unverified()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertRedirect(route('verification.notice'));

    $this->assertAuthenticatedAs($user);
});

test('verified users are redirected when requesting another verification email', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(VerifyEmail::class)
        ->call('sendVerificationEmail')
        ->assertRedirect(route('home'));
});
